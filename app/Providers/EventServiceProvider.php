<?php

namespace App\Providers;


use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider; 
use App\Listeners\AssignManagerRole;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            AssignManagerRole::class,  // ✅ Новые юзеры = manager
        ],
    ];

    protected bool $discoverEvents = false;
}
