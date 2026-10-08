<?php

namespace App\Providers;

use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Carbon::setLocale('id');

        // Audit SQL (hanya untuk request web, agar seeding tidak membengkakkan log)
        if (! app()->runningInConsole()) {
            DB::listen(function ($query) {
                Log::info(sprintf("[SQL AUDIT] (%.2f ms) %s", $query->time, $query->sql));
            });
        }

        // Data header: pengguna aktif dan notifikasi
        View::composer('components.layout', function ($view) {
            $user = Auth::user();
            $view->with([
                'pengguna' => $user,
                'notifs' => $user ? Notifikasi::where('user_id', $user->id)->latest()->latest('id')->take(6)->get() : collect(),
                'jumlahNotif' => $user ? Notifikasi::where('user_id', $user->id)->where('dibaca', false)->count() : 0,
            ]);
        });
    }
}