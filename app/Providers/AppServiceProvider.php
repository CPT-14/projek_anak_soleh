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
        // Menyebar jumlah order yang butuh perhatian admin (pending & diproses) ke SEMUA layout
        View::composer('*', function ($view) {
            
            // Kita coba pakai try-catch untuk berjaga-jaga jika ada migrasi yang belum dijalankan (mencegah error View)
            try {
                $pendingCount = Order::whereIn('status', ['pending', 'diproses'])->count();
                $view->with('pendingOrdersCount', $pendingCount);
            } catch (\Exception $e) {
                // Jika tabel orders belum ada, defaultkan ke 0 agar aplikasi tidak crash
                $view->with('pendingOrdersCount', 0);
            }
            
        });
    }
}