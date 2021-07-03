<?php

namespace App\Policies;

use App\User;
use App\Admin;


use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can update the report state.
     *
     * @param  \App\User  $user
     * @param  \App\Admin  $admin
     * @return mixed
     */
    public function update(User $user, Admin $admin)
    {
        return Auth::guard('admin')->Id() === $admin->id_admin;
    }
    
}
