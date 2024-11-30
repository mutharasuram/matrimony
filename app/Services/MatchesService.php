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
        $users = User::where('gender', '!=', $userGender)->orderBy('created_at', 'desc')->take(20)->get();
        return $users;
    }
}
