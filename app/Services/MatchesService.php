<?php

namespace App\Services;

use App\Models\User;
use DateTime;
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

    public function getJustJoined($id)
    {
        $userData = User::with('profile')->where('id', $id)->first();
        $gender = $userData->profile->gender ?? 'male';
        $oppositeGender = $gender == 'male' ? 'female' : 'male';
        $users = User::with('profile')
            ->whereHas('profile', function ($query) use ($oppositeGender) {
                $query->where('gender', $oppositeGender);
            })
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        return $users;
    }
    public function getMatches($id)
    {
        $userData = User::with('profile')->where('id', $id)->first();
        $userGender = $userData->profile->gender;
        $userDob = $userData->profile->dob;
        $userHeight = $userData->profile->height;
        $userSubcaste = $userData->profile->subcaste;
        $willingToMarryFromSubcaste = $userData->profile->willing_to_marry_from_subcaste;
        $oppositeGender = $userGender === 'male' ? 'female' : 'male';
        $users = User::with('profile')
            ->whereHas('profile', function ($query) use ($oppositeGender, $userDob, $userHeight, $userSubcaste, $willingToMarryFromSubcaste) {
                $query->where('gender', $oppositeGender)
                    ->where('height', '<=', $userHeight);
                if ($willingToMarryFromSubcaste === 'yes') {
                    $query->where('subcaste', $userSubcaste);
                }
                if ($oppositeGender === 'female') {
                    $query->where('dob', '<=', $userDob);
                } else {
                    $query->where('dob', '>=', $userDob);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return $users;
    }
    public function getNearBy($id)
    {
        $userData = User::with('profile')->where('id', $id)->first();
        $userGender = $userData->profile->gender;
        $userDob = $userData->profile->dob;
        $userHeight = $userData->profile->height;
        $userSubcaste = $userData->profile->subcaste;
        $state_of_birth = $userData->profile->state_of_birth;
        $country_of_birth =$userData->profile->country_of_birth;
        $city_of_birth = $userData->profile->city_of_birth;
        $country_living_in = $userData->profile->country_living_in;
        $residing_state = $userData->profile->city_of_birth;
        $residing_city = $userData->profile->residing_city;
        $willingToMarryFromSubcaste = $userData->profile->willing_to_marry_from_subcaste;
        $oppositeGender = $userGender === 'male' ? 'female' : 'male';
        $users = User::with('profile')
            ->whereHas('profile', function ($query) use (
                $oppositeGender,
                $userDob,
                $userHeight,
                $userSubcaste,
                $willingToMarryFromSubcaste,
                $state_of_birth,
                $country_of_birth,
                $city_of_birth,
                $country_living_in,
                $residing_state,
                $residing_city
            ) {
                $query->where('gender', $oppositeGender)
                    ->where('height', '<=', $userHeight)
                    ->where('country_living_in', $country_living_in);
                if ($willingToMarryFromSubcaste === 'yes') {
                    $query->where('subcaste', $userSubcaste);
                }
                if ($oppositeGender === 'female') {
                    $query->where('dob', '<=', $userDob);
                } else {
                    $query->where('dob', '>=', $userDob);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return $users;
    }
}
