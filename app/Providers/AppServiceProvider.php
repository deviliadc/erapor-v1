<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        // Set locale Carbon ke Indonesia
        Carbon::setLocale('id');

        // Jika pakai PHP 8 ke atas dan ingin format waktu lokal juga
        setlocale(LC_TIME, 'id_ID.utf8');

        // Mengabaikan Alpine.js untuk Livewire
        // Livewire::ignoreAlpine();
    }
}
