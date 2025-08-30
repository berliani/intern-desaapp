<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SubdomainMiddleware;
use App\Models\Company;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Spatie\Permission\Middleware\RoleMiddleware;
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->profile()
            // ->login()
            ->defaultThemeMode(ThemeMode::Light)
            ->colors([
                'primary' => Color::Emerald,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
                'info' => Color::Blue,
                'gray' => Color::Gray,
                'secondary' => Color::Indigo,
            ])
            ->navigationItems([
                NavigationItem::make('Halaman Depan')
                    ->url('/', shouldOpenInNewTab: false)
                    ->icon('heroicon-o-home')
                    ->group('Dashboard')
                    ->sort(2),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->sidebarCollapsibleOnDesktop()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                RoleMiddleware::class . ':super_admin|admin',

                // Middleware subdomain diletakkan di sini, setelah otentikasi
                SubdomainMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                RoleMiddleware::class . ':super_admin|admin',
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->userMenuItems([
                'logout' => MenuItem::make()
                    ->label('Keluar')
                    ->postAction('/admin-logout-redirect'),
            ])
            ->brandName('Desa Digital')
            ->navigationGroups([
                'Dashboard',
                'Desa',
                'Kependudukan',
                'Bantuan Sosial',
                'Layanan Warga',
                'Administrasi Sistem',
            ])

            ->tenant(Company::class, slugAttribute: 'subdomain', ownershipRelationship: 'users')
            ->tenantDomain('{tenant:subdomain}.desa.local')
            ->tenantDomain('{tenant:subdomain}.desa.local')

            ->renderHook(
                'panels::head.end',
                fn() => '
                <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
                <script>
                    window.chart = function(config) {
                        return {
                            chart: null,
                            init() {
                                setTimeout(() => {
                                    const canvas = this.$refs.canvas;
                                    if (canvas) {
                                        try {
                                            this.chart = new Chart(canvas.getContext("2d"), {
                                                type: config.type,
                                                data: config.cachedData,
                                                options: config.options
                                            });
                                        } catch(e) {
                                            console.error("Error initializing chart:", e);
                                        }
                                    }
                                }, 100);
                            }
                        };
                    };
                </script>'
                fn() => '
                  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
                  <script>
                       window.chart = function(config) {
                           return {
                               chart: null,
                               init() {
                                   setTimeout(() => {
                                       const canvas = this.$refs.canvas;
                                       if (canvas) {
                                           try {
                                               this.chart = new Chart(canvas.getContext("2d"), {
                                                   type: config.type,
                                                   data: config.cachedData,
                                                   options: config.options
                                               });
                                           } catch(e) {
                                               console.error("Error initializing chart:", e);
                                           }
                                       }
                                   }, 100);
                               }
                           };
                       };
                  </script>'
            );
    }
}
