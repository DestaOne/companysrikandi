<?php

namespace Tests\Feature;

use App\Models\HomeSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_logo_page(): void
    {
        $this->get(route('admin.branding.edit'))->assertRedirect(route('login'));
    }

    public function test_default_logos_are_used_until_changed(): void
    {
        $this->get(route('home'))
            ->assertSee('rel="icon" href="'.asset('images/logo.png').'"', false)
            ->assertSee(asset('images/logo-light.png'), false);
    }

    public function test_admin_can_change_logos_and_they_show_on_website(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create())
            ->put(route('admin.branding.update'), [
                'favicon' => UploadedFile::fake()->image('ikon.png'),
                'footer_logo' => UploadedFile::fake()->image('footer.png'),
            ])
            ->assertRedirect(route('admin.branding.edit'))
            ->assertSessionHas('success');

        $response = $this->get(route('home'));
        $response->assertSee('storage/home/', false);
        $response->assertSee(asset('images/logo-light.png'), false); // yang tidak diganti tetap bawaan
    }

    public function test_logo_must_be_an_image(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.branding.update'), ['favicon' => UploadedFile::fake()->create('x.pdf', 10)])
            ->assertSessionHasErrors('favicon');
    }

    public function test_admin_can_reset_a_logo_to_default_and_uploaded_file_is_deleted(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $this->actingAs($admin)->put(route('admin.branding.update'), ['navbar_logo_light' => UploadedFile::fake()->image('a.png')]);
        $stored = HomeSection::where('section', 'branding')->firstOrFail()->content['navbar_logo_light'];
        Storage::disk('public')->assertExists(str_replace('storage/', '', $stored));

        $this->actingAs($admin)
            ->delete(route('admin.branding.reset', 'navbar_logo_light'))
            ->assertRedirect(route('admin.branding.edit'))
            ->assertSessionHas('success');

        $this->assertSame('images/logo-light.png', HomeSection::where('section', 'branding')->firstOrFail()->content['navbar_logo_light']);
        Storage::disk('public')->assertMissing(str_replace('storage/', '', $stored));
    }

    public function test_reset_button_only_shows_for_changed_logo(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();

        $this->actingAs($admin)->get(route('admin.branding.edit'))->assertDontSee('Kembalikan ke bawaan');

        $this->put(route('admin.branding.update'), ['favicon' => UploadedFile::fake()->image('a.png')]);

        $this->get(route('admin.branding.edit'))->assertSee('form="reset-favicon"', false)->assertDontSee('form="reset-footer_logo"', false);
    }

    public function test_reset_rejects_unknown_logo_name(): void
    {
        $this->actingAs(User::factory()->create())
            ->delete(route('admin.branding.reset', 'bukan_logo'))
            ->assertNotFound();
    }
}
