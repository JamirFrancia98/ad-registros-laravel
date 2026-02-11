<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
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
        View::composer('layouts.app', function ($view) {
            $notifItems = Notification::orderByDesc('id')->limit(5)->get();
            $notifUnreadCount = Notification::whereNull('read_at')->count();

            $view->with(compact('notifItems', 'notifUnreadCount'));
        });
    }
}
