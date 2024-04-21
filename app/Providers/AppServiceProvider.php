<?php

declare(strict_types=1);

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\Vite;
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
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en', 'cs'])
                ->flags([
                    'en' => Vite::asset('resources/img/flag-usa.svg'),
                    'cs' => Vite::asset('resources/img/flag-czech.svg'),
                ])
                ->circular();
        });

        DatePicker::configureUsing(function (DatePicker $datePicker): void {
            $datePicker->displayFormat('d.m.Y')
                ->native(false);
        });
    }
}
