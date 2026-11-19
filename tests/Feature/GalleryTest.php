<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_public_gallery_page_lists_galleries(): void
    {
        Gallery::factory()->create(['title' => 'Gudang Utama']);

        $this->get(route('galleries.index'))->assertOk()->assertSee('Gudang Utama');
    }

    public function test_admin_can_create_gallery_with_image_and_activity_is_logged(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.galleries.store'), [
                'title' => 'Foto Baru',
                'image' => UploadedFile::fake()->image('foto.jpg'),
            ])
            ->assertRedirect(route('admin.galleries.index'))
            ->assertSessionHas('success');

        $gallery = Gallery::firstOrFail();
        Storage::disk('public')->assertExists($gallery->image);
        $this->assertDatabaseHas('activity_logs', ['action' => 'created', 'subject_id' => $gallery->id]);
    }

    public function test_store_requires_valid_input(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.galleries.store'), ['title' => ''])
            ->assertSessionHasErrors(['title', 'image']);
    }

    public function test_update_replaces_old_image_only_when_new_one_is_uploaded(): void
    {
        $user = User::factory()->create();
        Storage::disk('public')->put('galleries/lama.jpg', 'x');
        $gallery = Gallery::factory()->create(['image' => 'galleries/lama.jpg']);

        $this->actingAs($user)
            ->put(route('admin.galleries.update', $gallery), ['title' => 'Judul Baru'])
            ->assertRedirect(route('admin.galleries.index'));
        Storage::disk('public')->assertExists('galleries/lama.jpg');

        $this->actingAs($user)
            ->put(route('admin.galleries.update', $gallery), [
                'title' => 'Judul Baru',
                'image' => UploadedFile::fake()->image('baru.jpg'),
            ]);
        Storage::disk('public')->assertMissing('galleries/lama.jpg');
        $this->assertNotSame('galleries/lama.jpg', $gallery->fresh()->image);
        $this->assertSame(2, ActivityLog::where('action', 'updated')->count());
    }

    public function test_admin_can_delete_gallery_and_its_file(): void
    {
        Storage::disk('public')->put('galleries/hapus.jpg', 'x');
        $gallery = Gallery::factory()->create(['image' => 'galleries/hapus.jpg']);

        $this->actingAs(User::factory()->create())
            ->delete(route('admin.galleries.destroy', $gallery))
            ->assertRedirect(route('admin.galleries.index'));

        $this->assertModelMissing($gallery);
        Storage::disk('public')->assertMissing('galleries/hapus.jpg');
    }

    public function test_admin_pages_render(): void
    {
        $user = User::factory()->create();
        $gallery = Gallery::factory()->create();

        foreach ([route('admin.dashboard'), route('admin.galleries.index'), route('admin.galleries.create'), route('admin.galleries.edit', $gallery)] as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }

    public function test_reorder_changes_order_in_admin_grid_and_on_public_page(): void
    {
        [$a, $b, $c] = Gallery::factory()->count(3)->sequence(
            ['title' => 'Foto A'], ['title' => 'Foto B'], ['title' => 'Foto C'],
        )->create()->all();

        $this->actingAs(User::factory()->create())
            ->put(route('admin.galleries.reorder'), ['ids' => [$c->id, $a->id, $b->id]])
            ->assertNoContent();

        $this->assertSame([$c->id, $a->id, $b->id], Gallery::ordered()->pluck('id')->all());

        $this->get(route('galleries.index'))->assertSeeInOrder(['Foto C', 'Foto A', 'Foto B']);
        $this->actingAs(User::first())->get(route('admin.galleries.index'))->assertSeeInOrder(['Foto C', 'Foto A', 'Foto B']);
    }

    public function test_reorder_within_a_subset_only_swaps_their_slots_and_is_validated(): void
    {
        $admin = User::factory()->create();
        [$a, $b, $c, $d] = Gallery::factory()->count(4)->create()->all();

        $this->actingAs($admin)->put(route('admin.galleries.reorder'), ['ids' => [$d->id, $c->id]]);
        $this->assertSame([$a->id, $b->id, $d->id, $c->id], Gallery::ordered()->pluck('id')->all());

        $this->actingAs($admin)->put(route('admin.galleries.reorder'), ['ids' => [$a->id, 999]])->assertSessionHasErrors('ids.1');
        $this->actingAs($admin)->put(route('admin.galleries.reorder'), [])->assertSessionHasErrors('ids');
    }

    public function test_new_image_goes_to_the_end(): void
    {
        Gallery::factory()->count(2)->create();

        $this->actingAs(User::factory()->create())->post(route('admin.galleries.store'), [
            'title' => 'Paling Akhir',
            'image' => UploadedFile::fake()->image('baru.jpg'),
        ]);

        $this->assertSame('Paling Akhir', Gallery::ordered()->get()->last()->title);
    }

    public function test_admin_grid_has_add_button_and_single_edit_button_per_image(): void
    {
        $gallery = Gallery::factory()->create(['title' => 'Gudang']);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.galleries.index'))
            ->assertOk()
            ->assertSee('Tambah Gambar')
            ->assertDontSee('Ubah Nama')
            ->assertSee(route('admin.galleries.edit', $gallery), false);
    }

    public function test_admin_renames_image_without_touching_description_or_image(): void
    {
        $gallery = Gallery::factory()->create(['title' => 'Lama', 'description' => 'Tetap', 'image' => 'galleries/x.jpg']);

        $this->actingAs(User::factory()->create())
            ->put(route('admin.galleries.update', $gallery), ['title' => 'Nama Baru'])
            ->assertRedirect(route('admin.galleries.index'));

        $gallery->refresh();
        $this->assertSame('Nama Baru', $gallery->title);
        $this->assertSame('Tetap', $gallery->description);
        $this->assertSame('galleries/x.jpg', $gallery->image);
    }

    public function test_public_gallery_section_has_line_and_root_decoration(): void
    {
        Gallery::factory()->create();

        $html = $this->get(route('galleries.index'))->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, 'vector-effect="non-scaling-stroke" d="M500 0 C380 180'));
    }

    public function test_all_images_show_on_one_page_without_pagination(): void
    {
        $galleries = Gallery::factory()->count(20)->create();

        $this->actingAs(User::factory()->create(['activities_seen_at' => now()->addMinute()]))
            ->get(route('admin.galleries.index'))
            ->assertSee($galleries->last()->title)
            ->assertDontSee('page=2');

        $this->get(route('galleries.index'))
            ->assertSee($galleries->last()->title)
            ->assertDontSee('page=2');
    }
}
