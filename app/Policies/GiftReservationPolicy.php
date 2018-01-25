<?php

namespace App\Policies;

use App\User;
use App\Gift;
use Illuminate\Auth\Access\HandlesAuthorization;

class GiftReservationPolicy
{
    use HandlesAuthorization;

    public function updateReservation(User $user, Gift $gift)
    {
        // The owner of a gift can't reserve his own gift
        if ($gift->user_id === $user->id) return false;

        return $gift->reserved_by === null || $gift->reserved_by === $user->id;
    }
}