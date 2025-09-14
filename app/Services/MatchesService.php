<?php

namespace App\Services;

use App\Models\Interest;
use App\Models\Shortlist;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class MatchesService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getJustJoined($id, $perPage = 15, $page = 1)
    {
        $userData = User::with('profile')->where('id', $id)->first();
        
        if (!$userData || !$userData->profile) {
            return [
                'data' => [],
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => 0,
                    'last_page' => 0,
                    'from' => null,
                    'to' => null
                ]
            ];
        }

        $gender = $userData->profile->gender ?? 'male';
        $oppositeGender = $gender == 'male' ? 'female' : 'male';
        
        $users = User::with('profile', 'profile.images')
            ->where('id', '!=', $id) // Exclude current user
            ->whereHas('profile', function ($query) use ($oppositeGender) {
                $query->where('gender', $oppositeGender);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem()
            ]
        ];
    }
    public function getMatches($id, $perPage = 15, $page = 1)
    {
        $userData = User::with('profile')->where('id', $id)->first();
        if (!$userData || !$userData->profile) {
            return [
                'data' => [],
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => 0,
                    'last_page' => 0,
                    'from' => null,
                    'to' => null
                ]
            ];
        }

        $userGender = $userData->profile->gender;
        $userDob = $userData->profile->dob;
        $userHeight = $userData->profile->height;
        $userSubcaste = $userData->profile->subcaste;
        $willingToMarryFromSubcaste = $userData->profile->willing_to_marry_from_subcaste;
        $oppositeGender = $userGender === 'male' ? 'female' : 'male';
        
        $users = User::with('profile', 'profile.images')
            ->where('id', '!=', $id) // Exclude current user
            ->whereHas('profile', function ($query) use ($oppositeGender, $userDob, $userHeight, $userSubcaste, $willingToMarryFromSubcaste) {
                $query->where('gender', $oppositeGender);
                
                // Height matching logic - ensure both users have height data
                // if ($userHeight) {
                //     $query->where('height', '<=', $userHeight);
                // }
                
                // Subcaste matching logic
                // if ($willingToMarryFromSubcaste === 'yes' && $userSubcaste) {
                //     $query->where('subcaste', $userSubcaste);
                // }
                
                // Age matching logic - ensure both users have DOB data
                // if ($userDob) {
                //     if ($oppositeGender === 'female') {
                //         $query->where('dob', '<=', $userDob); // Female should be younger or same age
                //     } else {
                //         $query->where('dob', '>=', $userDob); // Male should be older or same age
                //     }
                // }
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
            
        return [
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem()
            ]
        ];
    }
    public function getNearBy($id, $perPage = 15, $page = 1)
    {
        $userData = User::with('profile')->where('id', $id)->first();
        
        if (!$userData || !$userData->profile) {
            return [
                'data' => [],
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => 0,
                    'last_page' => 0,
                    'from' => null,
                    'to' => null
                ]
            ];
        }

        $userGender = $userData->profile->gender;
        $userDob = $userData->profile->dob;
        $userHeight = $userData->profile->height;
        $userSubcaste = $userData->profile->subcaste;
        $state_of_birth = $userData->profile->state_of_birth;
        $country_of_birth = $userData->profile->country_of_birth;
        $city_of_birth = $userData->profile->city_of_birth;
        $country_living_in = $userData->profile->country_living_in;
        $residing_state = $userData->profile->residing_state;
        $residing_city = $userData->profile->residing_city;
        $willingToMarryFromSubcaste = $userData->profile->willing_to_marry_from_subcaste;
        $oppositeGender = $userGender === 'male' ? 'female' : 'male';

        $users = User::with('profile', 'profile.images')
            ->where('id', '!=', $id) // Exclude current user
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
                $query->where('gender', $oppositeGender);
                
                // Height matching logic - ensure both users have height data
                if ($userHeight) {
                    $query->where('height', '<=', $userHeight);
                }
                
                // Location matching logic - check if any location matches
                $query->where(function ($locationQuery) use (
                    $state_of_birth,
                    $city_of_birth,
                    $residing_state,
                    $residing_city
                ) {
                    if ($state_of_birth) {
                        $locationQuery->where('state_of_birth', $state_of_birth);
                    }
                    if ($city_of_birth) {
                        $locationQuery->orWhere('city_of_birth', $city_of_birth);
                    }
                    if ($residing_state) {
                        $locationQuery->orWhere('residing_state', $residing_state);
                    }
                    if ($residing_city) {
                        $locationQuery->orWhere('residing_city', $residing_city);
                    }
                });

                // Subcaste matching logic
                // if ($willingToMarryFromSubcaste === 'yes' && $userSubcaste) {
                //     $query->where('subcaste', $userSubcaste);
                // }
                
                // // Age matching logic - ensure both users have DOB data
                // if ($userDob) {
                //     if ($oppositeGender === 'female') {
                //         $query->where('dob', '<=', $userDob); // Female should be younger or same age
                //     } else {
                //         $query->where('dob', '>=', $userDob); // Male should be older or same age
                //     }
                // }
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem()
            ]
        ];
    }
    public function getShortlisted($id, $perPage = 15, $page = 1)
    {
        $shortlisted = Shortlist::where('user_id', $id)
            ->with(['shortlistedUser.profile', 'shortlistedUser.profile.images'])
            ->paginate($perPage, ['*'], 'page', $page);
            
        $data = $shortlisted->map(function ($item) {
            return $item->shortlistedUser;
        });

        return [
            'data' => $data->values()->all(),
            'pagination' => [
                'current_page' => $shortlisted->currentPage(),
                'per_page' => $shortlisted->perPage(),
                'total' => $shortlisted->total(),
                'last_page' => $shortlisted->lastPage(),
                'from' => $shortlisted->firstItem(),
                'to' => $shortlisted->lastItem()
            ]
        ];
    }
    
    public function getShortlistedBy($id, $perPage = 15, $page = 1)
    {
        $shortlisted = Shortlist::where('shorted_id', $id)
            ->with(['user.profile', 'user.profile.images'])
            ->paginate($perPage, ['*'], 'page', $page);
            
        $data = $shortlisted->map(function ($item) {
            return $item->user;
        });

        return [
            'data' => $data->values()->all(),
            'pagination' => [
                'current_page' => $shortlisted->currentPage(),
                'per_page' => $shortlisted->perPage(),
                'total' => $shortlisted->total(),
                'last_page' => $shortlisted->lastPage(),
                'from' => $shortlisted->firstItem(),
                'to' => $shortlisted->lastItem()
            ]
        ];
    }
    
    public function getInterested($id, $perPage = 15, $page = 1)
    {
        $interested = Interest::where('sender_id', $id)
            ->with(['receiver.profile', 'receiver.profile.images'])
            ->paginate($perPage, ['*'], 'page', $page);
            
        $data = $interested->map(function ($item) {
            return $item->receiver;
        });

        return [
            'data' => $data->values()->all(),
            'pagination' => [
                'current_page' => $interested->currentPage(),
                'per_page' => $interested->perPage(),
                'total' => $interested->total(),
                'last_page' => $interested->lastPage(),
                'from' => $interested->firstItem(),
                'to' => $interested->lastItem()
            ]
        ];
    }
    
    public function getInterestedBy($id, $perPage = 15, $page = 1)
    {
        $interested = Interest::where('receiver_id', $id)
            ->with(['sender.profile', 'sender.profile.images'])
            ->paginate($perPage, ['*'], 'page', $page);
            
        $data = $interested->map(function ($item) {
            return $item->sender;
        });

        return [
            'data' => $data->values()->all(),
            'pagination' => [
                'current_page' => $interested->currentPage(),
                'per_page' => $interested->perPage(),
                'total' => $interested->total(),
                'last_page' => $interested->lastPage(),
                'from' => $interested->firstItem(),
                'to' => $interested->lastItem()
            ]
        ];
    }
}
