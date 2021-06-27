<?php

namespace App\Policies;

use App\Models\Gift;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GiftPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Gift $gift)
    {
        return $user->id === $gift->user_id;
    }

    public function updateReservation(User $user, Gift $gift): bool
    {
        // The owner of a gift can't reserve his own gift
        if ($gift->user_id === $user->id) return false;

        return $gift->reserved_by === null || $gift->reserved_by === $user->id;
    }

    public function destroy(User $user, Gift $gift)
    {
        return $user->id === $gift->user_id;
    }
}
