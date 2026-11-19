<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\HomeSection;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class HomeSectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_guest_cannot_open_home_sections(): void
    {
        $this->get(route('admin.home-sections.index'))->assertRedirect(route('login'));
    }

    public function test_admin_sees_preview_of_every_section(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.home-sections.index'))
            ->assertOk()
            ->assertSee(config('home.hero.title'))
            ->assertSee(config('home.services.title'))
            ->assertSee(config('home.features.title'))
            ->assertSee(config('home.cta.title'))
            ->assertSee(route('admin.home-sections.edit', 'hero'), false);
    }

    public function test_edit_page_shows_current_content_and_unknown_section_is_404(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get(route('admin.home-sections.edit', 'hero'))
            ->assertOk()->assertSee(config('home.hero.title'));

        $this->actingAs($admin)->get('/admin/home-sections/tidak-ada/edit')->assertNotFound();
    }

    public function test_admin_updates_hero_text_and_image_and_home_shows_it(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.home-sections.update', 'hero'), [
                'title' => 'Judul Baru',
                'description' => 'Teks baru.',
                'image' => UploadedFile::fake()->image('hero.jpg'),
            ])
            ->assertRedirect(route('admin.home-sections.index'))
            ->assertSessionHas('success');

        $content = HomeSection::where('section', 'hero')->firstOrFail()->content;
        $this->assertStringStartsWith('storage/home/', $content['image']);
        Storage::disk('public')->assertExists(substr($content['image'], strlen('storage/')));
        $this->assertDatabaseHas('activity_logs', ['subject_type' => HomeSection::class]);

        $this->get(route('home'))->assertSee('Judul Baru')->assertSee($content['image'], false);
    }

    public function test_image_is_kept_when_not_replaced_and_old_upload_is_deleted_when_replaced(): void
    {
        $admin = User::factory()->create();
        $url = route('admin.home-sections.update', 'cta');
        $base = ['title' => 'A', 'description' => 'B', 'label' => 'C'];

        $this->actingAs($admin)->put($url, $base + ['image' => UploadedFile::fake()->image('a.jpg')]);
        $first = HomeSection::where('section', 'cta')->firstOrFail()->content['image'];

        $this->actingAs($admin)->put($url, ['title' => 'A2'] + $base);
        $this->assertSame($first, HomeSection::where('section', 'cta')->firstOrFail()->content['image']);

        $this->actingAs($admin)->put($url, $base + ['image' => UploadedFile::fake()->image('b.jpg')]);
        Storage::disk('public')->assertMissing(substr($first, strlen('storage/')));
    }

    public function test_service_cards_page_offers_published_services_to_pick(): void
    {
        $services = Service::factory()->count(5)->create();
        Service::factory()->draft()->create(['title' => 'Layanan Draft Rahasia']);

        // Admin sudah melihat semua aktivitas, jadi pratinjau lonceng tidak ikut memuat judul layanan
        $this->actingAs(User::factory()->create(['activities_seen_at' => now()->addMinute()]))
            ->get(route('admin.home-sections.edit', 'services'))
            ->assertOk()
            ->assertSee('Kartu 4')
            ->assertSee($services[4]->title)
            ->assertDontSee('Layanan Draft Rahasia')
            ->assertSee('name="service_ids[0]"', false);
    }

    public function test_admin_picks_four_services_for_home_carousel(): void
    {
        $services = Service::factory()->count(6)->create();
        $ids = [$services[5]->id, $services[2]->id, $services[0]->id, $services[3]->id];

        $this->actingAs(User::factory()->create())
            ->put(route('admin.home-sections.update', 'services'), [
                'eyebrow' => 'Layanan', 'title' => 'Judul Layanan', 'service_ids' => $ids,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.home-sections.index'));

        $this->assertSame($ids, HomeSection::where('section', 'services')->firstOrFail()->content['service_ids']);

        $this->get(route('home'))
            ->assertSeeInOrder([$services[5]->title, $services[2]->title, $services[0]->title, $services[3]->title])
            // Judul juga ada di footer, jadi kartu yang tidak dipilih dicek lewat ringkasannya
            ->assertDontSee(Str::limit($services[1]->description, 110));
    }

    public function test_service_picks_must_be_four_distinct_published_services(): void
    {
        $services = Service::factory()->count(4)->create();
        $draft = Service::factory()->draft()->create();
        $url = route('admin.home-sections.update', 'services');
        $base = ['eyebrow' => 'a', 'title' => 'b'];
        $admin = User::factory()->create();

        $this->actingAs($admin)->put($url, $base + ['service_ids' => $services->pluck('id')->take(3)->all()])
            ->assertSessionHasErrors('service_ids');
        $this->actingAs($admin)->put($url, $base + ['service_ids' => [$services[0]->id, $services[0]->id, $services[1]->id, $services[2]->id]])
            ->assertSessionHasErrors('service_ids.1');
        $this->actingAs($admin)->put($url, $base + ['service_ids' => [$services[0]->id, $services[1]->id, $services[2]->id, $draft->id]])
            ->assertSessionHasErrors('service_ids.3');
    }

    public function test_home_falls_back_to_first_four_published_services(): void
    {
        $services = Service::factory()->count(5)->create();
        Service::factory()->draft()->create(['title' => 'Layanan Draft Rahasia']);

        $this->get(route('home'))
            ->assertSee(Str::limit($services[3]->description, 110))
            ->assertDontSee(Str::limit($services[4]->description, 110))
            ->assertDontSee('Layanan Draft Rahasia');
    }

    public function test_features_page_lists_cards_and_links_to_each_card_form(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.home-sections.edit', 'features'))
            ->assertOk()
            ->assertSee(config('home.features.items.0.title'))
            ->assertSee(config('home.features.items.3.title'))
            ->assertSee(route('admin.home-sections.items.edit', ['features', 3]), false)
            ->assertSee(route('admin.home-sections.edit', ['features', 'judul' => 1]), false);
    }

    public function test_feature_card_form_shows_current_values_and_unknown_card_is_404(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get(route('admin.home-sections.items.edit', ['features', 1]))
            ->assertOk()
            ->assertSee(config('home.features.items.1.title'));

        $this->actingAs($admin)->get(route('admin.home-sections.items.edit', ['features', 9]))->assertNotFound();
        $this->actingAs($admin)->get(route('admin.home-sections.items.edit', ['hero', 0]))->assertNotFound();
    }

    public function test_admin_updates_one_feature_card_and_others_stay(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create())
            ->put(route('admin.home-sections.items.update', ['features', 2]), [
                'title' => 'Keunggulan Baru',
                'description' => 'Deskripsi baru.',
                'image' => UploadedFile::fake()->image('k.jpg'),
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.home-sections.edit', 'features'));

        $items = HomeSection::where('section', 'features')->firstOrFail()->content['items'];
        $this->assertSame('Keunggulan Baru', $items[2]['title']);
        $this->assertStringStartsWith('storage/home/', $items[2]['image']);
        $this->assertSame(config('home.features.items.0.title'), $items[0]['title']);
        $this->assertSame(config('home.features.items.3.image'), $items[3]['image']);

        $this->get(route('home'))->assertSee('Keunggulan Baru');
    }

    public function test_feature_card_update_is_validated_and_heading_form_returns_to_cards(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.home-sections.items.update', ['features', 0]), ['title' => '', 'description' => ''])
            ->assertSessionHasErrors(['title', 'description']);

        $this->actingAs($admin)->get(route('admin.home-sections.edit', ['features', 'judul' => 1]))
            ->assertOk()->assertSee('name="eyebrow"', false);

        $this->actingAs($admin)
            ->put(route('admin.home-sections.update', 'features'), ['eyebrow' => 'Unggul', 'title' => 'Judul Baru'])
            ->assertRedirect(route('admin.home-sections.edit', 'features'));
    }

    public function test_update_is_validated(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.home-sections.update', 'hero'), ['title' => '', 'image' => UploadedFile::fake()->create('x.pdf', 10)])
            ->assertSessionHasErrors(['title', 'description', 'image']);

        $this->assertDatabaseCount('home_sections', 0);
        $this->assertSame(0, ActivityLog::count());
    }

    public function test_admin_sees_about_preview_and_every_section_links_to_its_edit_page(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.about.index'))
            ->assertOk()
            ->assertSee(config('about.banner.title'))
            ->assertSee(config('about.vision.text'))
            ->assertSee(config('about.contact.title'));

        foreach (['about-banner', 'about-background', 'about-vision', 'about-mission', 'about-contact'] as $section) {
            $response->assertSee(route('admin.home-sections.edit', $section), false);
        }
    }

    public function test_admin_updates_about_mission_and_background_lists_and_public_page_shows_them(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->put(route('admin.home-sections.update', 'about-mission'), [
                'title' => 'Misi Kami',
                'items' => "Poin satu.\n\n  Poin dua.  \n",
            ])
            ->assertRedirect(route('admin.about.index'));

        $this->actingAs($admin)
            ->put(route('admin.home-sections.update', 'about-background'), [
                'eyebrow' => 'Cerita',
                'title' => 'Judul Baru',
                'paragraphs' => 'Paragraf tunggal.',
                'image' => UploadedFile::fake()->image('latar.jpg'),
            ])
            ->assertRedirect(route('admin.about.index'));

        // Daftar yang lebih pendek dari bawaan tidak boleh tercampur sisa isi bawaan
        $this->assertSame(['Poin satu.', 'Poin dua.'], HomeSection::where('section', 'about-mission')->firstOrFail()->content['items']);
        $this->assertSame(['Paragraf tunggal.'], HomeSection::where('section', 'about-background')->firstOrFail()->content['paragraphs']);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Misi Kami')
            ->assertSee('Poin dua.')
            ->assertSee('Paragraf tunggal.')
            ->assertDontSee(config('about.mission.items.0'));
    }

    public function test_about_vision_requires_text(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.home-sections.update', 'about-vision'), ['title' => 'Visi', 'text' => ''])
            ->assertSessionHasErrors('text');
    }

    public function test_about_edit_forms_show_current_list_content_in_textarea(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get(route('admin.home-sections.edit', 'about-background'))
            ->assertOk()
            ->assertSee('<textarea id="paragraphs"', false)
            ->assertSee(config('about.background.paragraphs.0'))
            ->assertDontSee('<x-ui.textarea', false);

        $this->actingAs($admin)->get(route('admin.home-sections.edit', 'about-mission'))
            ->assertOk()
            ->assertSee(config('about.mission.items.1'));
    }

    public function test_admin_edits_about_contact_details_and_public_page_shows_them(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get(route('admin.home-sections.edit', 'about-contact'))
            ->assertOk()
            ->assertSee(config('company.phone'))
            ->assertSee(config('company.email'));

        $this->actingAs($admin)
            ->put(route('admin.home-sections.update', 'about-contact'), [
                'eyebrow' => 'Kontak',
                'title' => 'Hubungi Kami',
                'description' => 'Kami siap membantu.',
                'address' => 'Jalan Baru Nomor 1, Tangerang',
                'phone' => '081200001111',
                'email' => 'info@contoh.test',
            ])
            ->assertRedirect(route('admin.about.index'));

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Jalan Baru Nomor 1, Tangerang')
            ->assertSee('081200001111')
            ->assertSee('info@contoh.test');
    }

    public function test_about_contact_email_must_be_valid(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.home-sections.update', 'about-contact'), [
                'eyebrow' => 'Kontak', 'title' => 'Hubungi Kami', 'description' => 'x',
                'address' => 'Alamat', 'phone' => '0812', 'email' => 'bukan-email',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_sidebar_stays_on_the_page_whose_section_is_being_edited(): void
    {
        $admin = User::factory()->create();
        $menus = ['hero' => 'Home', 'service-banner' => 'Layanan', 'about-banner' => 'Tentang'];

        foreach ($menus as $section => $menu) {
            $html = $this->actingAs($admin)->get(route('admin.home-sections.edit', $section))->assertOk()->getContent();

            $this->assertSame(1, substr_count($html, 'aria-current="page"'), "Hanya satu menu aktif di form {$section}");
            $this->assertMatchesRegularExpression('/aria-current="page"[^>]*>.*?'.$menu.'<\/span>/s', $html, "Menu {$menu} aktif di form {$section}");
        }
    }
}
