<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MatchesService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getJustJoined()
    {
        $userGender = Auth::user()->gender;
        $oppositeGender = $userGender == 'male' ? 'female' : 'male';
        $users = User::with('profile')
            ->whereHas('profile', function ($query) use ($oppositeGender) {
                $query->where('gender', $oppositeGender);
            })
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return $users;
    }
}
