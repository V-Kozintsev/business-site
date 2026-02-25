<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Role;

class AssignManagerRole
{
    public function handle(Registered $event): void
    {
        $user = $event->user;
        $user->assignRole('manager');  // ✅ Все новые = manager [web:36]
    }
}
