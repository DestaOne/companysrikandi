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
}
