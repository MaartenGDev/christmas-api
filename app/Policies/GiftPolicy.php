<?php

namespace App\Policies;

use App\User;
use App\Gift;
use Illuminate\Auth\Access\HandlesAuthorization;

class GiftPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the gift.
     *
     * @param  \App\User  $user
     * @param  \App\Gift  $gift
     * @return mixed
     */
    public function update(User $user, Gift $gift)
    {
        return $user->id === $gift->user_id;
    }

    /**
     * Determine whether the user can delete the gift.
     *
     * @param  \App\User  $user
     * @param  \App\Gift  $gift
     * @return mixed
     */
    public function delete(User $user, Gift $gift)
    {
        return $user->id === $gift->user_id;
    }
}
