<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // BUG FIXED: Menggunakan tanda '*' agar variabel dikirim ke SEMUA view tanpa terkecuali
        View::composer('*', function ($view) {
            $pendingCount = Order::where('status', 'pending')->count();
            $view->with('pendingOrdersCount', $pendingCount);
        });
    }
}