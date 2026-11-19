<?php

namespace App\Providers;

use App\Enums\HomeSectionKey;
use App\Services\ActivityLogService;
use App\Services\ContactMessageService;
use App\Services\HomeContentService;
use App\Services\ServiceCatalogService;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Footer memakai alamat, telepon, dan email yang diatur admin di menu Tentang (bagian Kontak)
        View::composer('components.web.footer', fn (ViewContract $view) => $view->with(
            'contact',
            app(HomeContentService::class)->section(HomeSectionKey::AboutContact),
        )->with(
            'footerServices',
            app(ServiceCatalogService::class)->publishedLinks(),
        ));

        // Menu Layanan di navbar mengikuti layanan terbit, jadi bertambah otomatis saat admin menambah layanan
        View::composer('components.web.navbar', fn (ViewContract $view) => $view->with(
            'menuServices',
            app(ServiceCatalogService::class)->publishedLinks(24),
        ));

        // Lonceng di header admin: aktivitas yang belum dilihat admin yang sedang masuk dan pesan yang belum dibaca (jumlah dan pratinjau terbaru)
        View::composer('layouts.app', function (ViewContract $view) {
            $user = auth()->user();
            $service = app(ActivityLogService::class);
            $messages = app(ContactMessageService::class);

            $view->with([
                'newActivityCount' => $user ? $service->newCount($user) : 0,
                'newActivities' => $user ? $service->newest($user) : collect(),
                'unreadMessageCount' => $user ? $messages->unreadCount() : 0,
                'unreadMessages' => $user ? $messages->newestUnread() : collect(),
            ]);
        });

        // Logo dan ikon tab diatur admin di menu Logo; dibaca sekali per permintaan untuk semua tampilan yang memakainya
        $logos = null;
        View::composer(
            ['layouts.app', 'layouts.guest', 'layouts.public', 'components.web.navbar', 'components.web.footer'],
            function (ViewContract $view) use (&$logos) {
                $logos ??= app(HomeContentService::class)->section(HomeSectionKey::Branding);
                $view->with('logos', $logos);
            },
        );
    }
}
