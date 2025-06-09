<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->brandName('MediPet')
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#A1E3F9'),
                'secondary' => Color::hex('#F9A1A1'),
                'success' => Color::hex('#A1F9A1'),
                'warning' => Color::hex('#F9DFA1'),
                'danger' => Color::hex('#F9A1A1'),
                'info' => Color::hex('#A1C6F9'),
                'light' => Color::hex('#F1F1F1'),
                'dark' => Color::hex('#333333'),
                'muted' => Color::hex('#B0B0B0'),
                'accent' => Color::hex('#FF5722'),
                'link' => Color::hex('#1E88E5'),
                'text' => Color::hex('#212121'),
                'background' => Color::hex('#FFFFFF'),
                'border' => Color::hex('#E0E0E0'),
                'hover' => Color::hex('#F5F5F5'),
                'active' => Color::hex('#E0E0E0'),
                'disabled' => Color::hex('#BDBDBD'),
                'focus' => Color::hex('#E3F2FD'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                \App\Filament\Widgets\CenterImageWidget::class, // Aquí agregamos tu widget
            ])
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
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarFullyCollapsibleOnDesktop();
    }
}