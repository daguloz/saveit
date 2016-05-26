<?php

namespace App\Policies;

use App\User;
use App\Link;
use Illuminate\Auth\Access\HandlesAuthorization;

class LinkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can delete the given link.
     *
     * @param  User  $user
     * @param  Task  $link
     * @return bool
     */
    public function destroy(User $user, Link $link)
    {
        return $user->id === $link->user_id;
    }
}
