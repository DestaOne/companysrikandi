<?php

namespace Tests\Feature;

use App\Models\HomeSection;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_guest_cannot_open_admin_services(): void
    {
        $this->get(route('admin.services.index'))->assertRedirect(route('login'));
    }

    public function test_index_shows_banner_table_with_actions_and_cta(): void
    {
        $service = Service::factory()->create(['title' => 'Layanan Uji']);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.services.index'))
            ->assertOk()
            ->assertSee(config('service.banner.title'))
            ->assertSee(config('service.cta.title'))
            ->assertSee('Layanan Uji')
            ->assertSee(route('admin.services.edit', $service), false)
            ->assertSee(route('admin.services.destroy', $service), false)
            ->assertSee(route('admin.home-sections.edit', 'service-banner'), false)
            ->assertSee(route('admin.home-sections.edit', 'service-cta'), false);
    }

    public function test_index_lists_all_services_in_one_scrollable_table(): void
    {
        $services = Service::factory()->count(15)->create();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.services.index'));

        foreach ($services as $service) {
            $response->assertSee('data-id="'.$service->id.'"', false);
        }
        $response->assertSee('overflow-y-auto', false);
    }

    public function test_admin_creates_service_with_slug_products_and_image(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.services.store'), [
                'title' => 'Layanan Baru',
                'description' => 'Deskripsi layanan.',
                'status' => 'published', 'products' => "Barang A\n\n Barang B \n",
                'image' => UploadedFile::fake()->image('a.jpg'),
            ])
            ->assertRedirect(route('admin.services.index'))
            ->assertSessionHas('success');

        $service = Service::firstWhere('slug', 'layanan-baru');
        $this->assertSame(['Barang A', 'Barang B'], $service->products);
        Storage::disk('public')->assertExists(substr($service->image, strlen('storage/')));
        $this->assertDatabaseHas('activity_logs', ['subject_type' => Service::class, 'action' => 'created']);

        $this->get(route('services.show', 'layanan-baru'))->assertOk()->assertSee('Barang B');
    }

    public function test_duplicate_title_gets_unique_slug(): void
    {
        $admin = User::factory()->create();
        $data = ['title' => 'Sama', 'description' => 'x', 'status' => 'published', 'products' => 'a'];

        $this->actingAs($admin)->post(route('admin.services.store'), $data + ['image' => UploadedFile::fake()->image('a.jpg')]);
        $this->actingAs($admin)->post(route('admin.services.store'), $data + ['image' => UploadedFile::fake()->image('b.jpg')]);

        $this->assertDatabaseHas('services', ['slug' => 'sama']);
        $this->assertDatabaseHas('services', ['slug' => 'sama-2']);
    }

    public function test_update_keeps_slug_and_replaces_image(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin)->post(route('admin.services.store'), [
            'title' => 'Awal', 'description' => 'x', 'status' => 'published', 'products' => 'a', 'image' => UploadedFile::fake()->image('a.jpg'),
        ]);
        $service = Service::firstWhere('slug', 'awal');
        $oldImage = $service->image;

        $this->actingAs($admin)->put(route('admin.services.update', $service), [
            'title' => 'Judul Diubah', 'description' => 'y', 'status' => 'published', 'products' => "b\nc", 'image' => UploadedFile::fake()->image('b.jpg'),
        ])->assertRedirect(route('admin.services.index'));

        $service->refresh();
        $this->assertSame('awal', $service->slug);
        $this->assertSame('Judul Diubah', $service->title);
        $this->assertNotSame($oldImage, $service->image);
        Storage::disk('public')->assertMissing(substr($oldImage, strlen('storage/')));
    }

    public function test_delete_removes_service_and_uploaded_image_but_not_bundled_images(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin)->post(route('admin.services.store'), [
            'title' => 'Hapus', 'description' => 'x', 'status' => 'published', 'products' => 'a', 'image' => UploadedFile::fake()->image('a.jpg'),
        ]);
        $uploaded = Service::firstWhere('slug', 'hapus');
        $bundled = Service::factory()->create();

        $this->actingAs($admin)->delete(route('admin.services.destroy', $uploaded))->assertRedirect(route('admin.services.index'));
        $this->actingAs($admin)->delete(route('admin.services.destroy', $bundled))->assertRedirect();

        $this->assertDatabaseCount('services', 0);
        Storage::disk('public')->assertMissing(substr($uploaded->image, strlen('storage/')));
    }

    public function test_validation_errors(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.services.store'), ['title' => ''])
            ->assertSessionHasErrors(['title', 'description', 'products', 'status', 'image']);
    }

    public function test_banner_and_cta_edits_show_on_public_services_page_and_redirect_back_to_services(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get(route('admin.home-sections.edit', 'service-banner'))->assertOk();

        $this->actingAs($admin)->put(route('admin.home-sections.update', 'service-banner'), [
            'title' => 'Banner Layanan Baru', 'description' => 'Teks banner.',
        ])->assertRedirect(route('admin.services.index'));

        $this->actingAs($admin)->put(route('admin.home-sections.update', 'service-cta'), [
            'title' => 'CTA Layanan Baru', 'description' => 'Teks cta.', 'label' => 'Hubungi',
        ])->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseCount('home_sections', 2);
        $this->assertSame('Banner Layanan Baru', HomeSection::firstWhere('section', 'service-banner')->content['title']);
        $this->get(route('services'))->assertSee('Banner Layanan Baru')->assertSee('CTA Layanan Baru');
    }

    public function test_reorder_changes_order_in_admin_table_and_on_public_pages(): void
    {
        [$a, $b, $c] = Service::factory()->count(3)->sequence(
            ['title' => 'Layanan A'], ['title' => 'Layanan B'], ['title' => 'Layanan C'],
        )->create()->all();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.services.reorder'), ['ids' => [$c->id, $a->id, $b->id]])
            ->assertNoContent();

        $this->assertSame([$c->id, $a->id, $b->id], Service::ordered()->pluck('id')->all());

        $this->get(route('services'))->assertSeeInOrder(['Layanan C', 'Layanan A', 'Layanan B']);
        $this->actingAs(User::first())->get(route('admin.services.index'))->assertSeeInOrder(['Layanan C', 'Layanan A', 'Layanan B']);
    }

    public function test_reorder_within_a_subset_only_swaps_their_slots(): void
    {
        [$a, $b, $c, $d] = Service::factory()->count(4)->create()->all();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.services.reorder'), ['ids' => [$d->id, $c->id]]);

        $this->assertSame([$a->id, $b->id, $d->id, $c->id], Service::ordered()->pluck('id')->all());
    }

    public function test_new_service_goes_to_the_end_and_reorder_is_validated(): void
    {
        $admin = User::factory()->create();
        Service::factory()->count(2)->create();

        $this->actingAs($admin)->post(route('admin.services.store'), [
            'title' => 'Terakhir', 'description' => 'x', 'products' => 'a', 'status' => 'published',
            'image' => UploadedFile::fake()->image('a.jpg'),
        ]);

        $this->assertSame('Terakhir', Service::ordered()->get()->last()->title);

        $this->actingAs($admin)->put(route('admin.services.reorder'), ['ids' => [1, 999]])->assertSessionHasErrors('ids.1');
        $this->actingAs($admin)->put(route('admin.services.reorder'), [])->assertSessionHasErrors('ids');
    }

    public function test_home_carousel_fallback_follows_service_order(): void
    {
        $services = Service::factory()->count(5)->create();
        $this->actingAs(User::factory()->create())
            ->put(route('admin.services.reorder'), ['ids' => [$services[4]->id, $services[0]->id]]);

        $this->get(route('home'))->assertSee($services[4]->title);
    }

    public function test_form_offers_draft_and_published_and_defaults_to_published(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.services.create'))
            ->assertOk()
            ->assertSee('Draft')
            ->assertSee('Terbit')
            ->assertSee('<option value="published" selected>', false);
    }

    public function test_status_is_shown_in_table_and_can_be_changed(): void
    {
        $admin = User::factory()->create();
        $service = Service::factory()->draft()->create();

        $this->actingAs($admin)->get(route('admin.services.index'))->assertSee('Draft');

        $this->actingAs($admin)->put(route('admin.services.update', $service), [
            'title' => $service->title, 'description' => 'x', 'products' => 'a', 'status' => 'published',
        ])->assertRedirect(route('admin.services.index'));

        $this->assertSame('published', $service->refresh()->status->value);
        $this->actingAs($admin)->get(route('admin.services.index'))->assertSee('Terbit');
    }

    public function test_invalid_status_is_rejected(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.services.store'), [
                'title' => 'X', 'description' => 'x', 'products' => 'a', 'status' => 'rahasia',
                'image' => UploadedFile::fake()->image('a.jpg'),
            ])
            ->assertSessionHasErrors('status');
    }

    public function test_draft_service_is_hidden_from_public_pages(): void
    {
        $published = Service::factory()->create(['title' => 'Layanan Tampil']);
        $draft = Service::factory()->draft()->create(['title' => 'Layanan Rahasia']);

        $this->get(route('services'))->assertSee('Layanan Tampil')->assertDontSee('Layanan Rahasia');
        $this->get(route('services.show', $published->slug))->assertOk();
        $this->get(route('services.show', $draft->slug))->assertNotFound();
    }

    public function test_search_filters_services_by_title_description_and_keeps_query_in_pagination(): void
    {
        // Admin sudah melihat semua aktivitas, jadi pratinjau lonceng tidak ikut memuat judul layanan
        $admin = User::factory()->create(['activities_seen_at' => now()->addMinute()]);
        Service::factory()->create(['title' => 'Mesin Pengolah Karet', 'description' => 'Untuk pabrik.']);
        Service::factory()->create(['title' => 'Komputer Kantor', 'description' => 'Perangkat bekerja.']);
        Service::factory()->create(['title' => 'Alat Lab', 'description' => 'Mendukung mesin presisi.']);

        $this->actingAs($admin)->get(route('admin.services.index', ['q' => 'mesin']))
            ->assertOk()
            ->assertSee('Mesin Pengolah Karet')
            ->assertSee('Alat Lab')
            ->assertDontSee('Komputer Kantor')
            ->assertSee('value="mesin"', false)
            ->assertSee('Hapus pencarian');

        $this->actingAs($admin)->get(route('admin.services.index', ['q' => 'tidakada']))
            ->assertSee('Tidak ada layanan yang cocok dengan “tidakada”.');

        $this->actingAs($admin)->get(route('admin.services.index'))
            ->assertSee('Komputer Kantor')
            ->assertDontSee('Hapus pencarian');
    }

    public function test_search_keyword_is_validated(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.services.index', ['q' => str_repeat('a', 101)]))
            ->assertSessionHasErrors('q');
    }

    public function test_search_bar_filters_rows_while_typing_without_reloading(): void
    {
        Service::factory()->create(['title' => 'Mesin Pengolah Karet']);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.services.index'))
            ->assertSee('x-data="liveSearch"', false)
            ->assertDontSee('>Cari</button>', false)
            ->assertSee('x-on:input="query = $event.target.value; filter()"', false)
            ->assertDontSee('debounce', false)
            ->assertSee('data-search="mesin pengolah karet', false)
            ->assertSee('data-search-empty', false);
    }
}
