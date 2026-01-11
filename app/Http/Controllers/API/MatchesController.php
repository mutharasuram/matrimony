<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\MatchesService;

class MatchesController extends BaseController
{
    protected $matchesService;

    /**
     * MatchesController constructor.
     *
     * @param MatchesService $matchesService
     */
    public function __construct(MatchesService $matchesService)
    {
        $this->matchesService = $matchesService;
    }

    public function index(Request $request)
    {
        try {
            // Validate required parameters
            $validatedData = $request->validate([
                'type' => 'required|string|in:just_joined,matches,nearby,shortlisted,shortlisted_by,interested,interested_by',
                'id' => 'required|integer|min:1',
                'search' => 'nullable|string|max:255'
            ]);

            $type = $validatedData['type'];
            $userId = $validatedData['id'];

            // Get pagination parameters
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);

            // Validate pagination parameters
            $perPage = max(1, min(100, (int)$perPage)); // Limit between 1-100
            $page = max(1, (int)$page);

            // Get search parameter
            $searchParams = [
                'search' => $request->get('search')
            ];

            switch ($type) {
                case 'just_joined':
                    $matches = $this->matchesService->getJustJoined($userId, $perPage, $page, $searchParams);
                    break;
                case 'matches':
                    $matches = $this->matchesService->getMatches($userId, $perPage, $page, $searchParams);
                    break;
                case 'nearby':
                    $matches = $this->matchesService->getNearBy($userId, $perPage, $page, $searchParams);
                    break;
                case 'shortlisted':
                    $matches = $this->matchesService->getShortlisted($userId, $perPage, $page, $searchParams);
                    break;
                case 'shortlisted_by':
                    $matches = $this->matchesService->getShortlistedBy($userId, $perPage, $page, $searchParams);
                    break;
                case 'interested':
                    $matches = $this->matchesService->getInterested($userId, $perPage, $page, $searchParams);
                    break;
                case 'interested_by':
                    $matches = $this->matchesService->getInterestedBy($userId, $perPage, $page, $searchParams);
                    break;
                default:
                    return $this->sendError('Invalid match type.', [], 400);
            }

            return $this->sendResponse($matches, ucfirst(str_replace('_', ' ', $type)) . ' retrieved successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving matches.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get home profiles - random 10 profiles of opposite gender
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function home(Request $request)
    {
        try {
            // Validate required parameters
            $validatedData = $request->validate([
                'id' => 'required|integer|min:1',
                'search' => 'nullable|string|max:255'
            ]);

            $userId = $validatedData['id'];

            // Get search parameter
            $searchParams = [
                'search' => $request->get('search')
            ];

            $profiles = $this->matchesService->getHomeProfiles($userId, $searchParams);

            // Format users with profile_completion
            if (isset($profiles['data']) && is_array($profiles['data'])) {
                $profiles['data'] = array_map(function ($user) {
                    return $this->formatUserWithProfileCompletion($user);
                }, $profiles['data']);
            }
            $profiles['user'] = $this->formatUserWithProfileCompletion($profiles['user']);

            return $this->sendResponse($profiles, 'Home profiles retrieved successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving home profiles.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Format user with profile completion data
     *
     * @param mixed $user
     * @return array
     */
    private function formatUserWithProfileCompletion($user)
    {
        // Define all profile fields for completion tracking
        $allProfileFields = [
            // Basic Information
            'profile_created_by',
            'gender',
            'name',
            'dob',
            'mother_tongue',
            'subcaste',
            'sub_caste_details',
            'willing_to_marry_from_subcaste',
            'marital_status',
            'height',
            'physical_status',
            // Location
            'country_living_in',
            'residing_state',
            'residing_city',
            'citizenship',
            'country_of_birth',
            'state_of_birth',
            'city_of_birth',
            // Education & Career
            'education',
            'education_category',
            'employed_in',
            'occupation',
            'annual_income',
            'income',
            // Family
            'family_status',
            'family_type',
            'father_occupation',
            'mother_occupation',
            'no_of_brothers',
            'no_of_sisters',
            // About
            'about_me',
            'about_my_family',
            'fewlines_about_my_partner',
            // Astrology
            'dosham',
            'dosham_value',
            'star_nakshatram',
            'rasi',
            'gothram',
            'time_of_birth',
            'horoscope_chart_style',
            // Habits
            'eating_habit',
            'drinking_habit',
            'smoking_habit',
            'habit',
            'hobbies_and_interests',
            'music',
            'sports',
            'food',
            // Partner Preferences - Age & Physical
            'preferred_age_min',
            'preferred_age_max',
            'preferred_height_min',
            'preferred_height_max',
            'preferred_marital_status',
            'preferred_physical_status',
            // Partner Preferences - Background
            'preferred_mother_tongue',
            'preferred_subcaste',
            'preferred_subcaste_details',
            'preferred_chevvai_dosham',
            'preferred_citizenship',
            // Partner Preferences - Education & Career
            'preferred_education',
            'preferred_employed_in',
            'preferred_occupation',
            'preferred_annual_income_min',
            'preferred_annual_income_max',
            'preferred_country',
            // Partner Preferences - Habits
            'preferred_eating_habit',
            'preferred_drinking_habit',
            'preferred_smoking_habit',
            'preferred_hobbies_and_interests',
            'preferred_music',
            'preferred_sports',
            'preferred_food',
            // Other
            'isEligible'
        ];

        // Get profile - handle both Eloquent models and arrays
        $profile = null;
        if (is_object($user) && isset($user->profile)) {
            $profile = $user->profile;
        } elseif (is_array($user) && isset($user['profile'])) {
            $profile = $user['profile'];
        }

        // Initialize profile_completion
        $profileCompletion = [
            'filled_fields' => 0,
            'total_fields' => count($allProfileFields),
            'percentage' => 0,
            'missing_fields' => []
        ];

        // Calculate profile completion if profile exists
        if ($profile) {
            // Convert profile to array if it's a model
            $profileArray = is_object($profile) && method_exists($profile, 'toArray')
                ? $profile->toArray()
                : (array)$profile;

            $filledFields = 0;
            $missingFields = [];

            foreach ($allProfileFields as $field) {
                $value = $profileArray[$field] ?? null;
                if ($value !== null && $value !== '' && $value !== '0') {
                    $filledFields++;
                } else {
                    $missingFields[] = $field;
                }
            }

            $percentage = count($allProfileFields) > 0
                ? round(($filledFields / count($allProfileFields)) * 100, 2)
                : 0;

            $profileCompletion = [
                'filled_fields' => $filledFields,
                'total_fields' => count($allProfileFields),
                'percentage' => $percentage,
                'missing_fields' => $missingFields
            ];
        } else {
            // If no profile exists, all fields are missing
            $profileCompletion['missing_fields'] = $allProfileFields;
        }

        // Convert user to array if it's a model
        $userArray = is_object($user) && method_exists($user, 'toArray') ? $user->toArray() : (array)$user;

        // Add profile_completion to user array
        $userArray['profile_completion'] = $profileCompletion;

        // Format images if they exist
        $images = [];
        if (is_object($user) && isset($user->profile) && $user->profile && isset($user->profile->images)) {
            $images = $user->profile->images;
        } elseif (isset($userArray['profile']['images'])) {
            $images = $userArray['profile']['images'];
        } elseif (isset($userArray['images'])) {
            $images = $userArray['images'];
        }

        if (!empty($images)) {
            // Convert collection to array if needed
            $imagesArray = is_object($images) && method_exists($images, 'toArray')
                ? $images->toArray()
                : (is_array($images) ? $images : []);

            $userArray['images'] = array_map(function ($image) {
                $imgArray = is_object($image) && method_exists($image, 'toArray') ? $image->toArray() : (array)$image;
                if (!isset($imgArray['full_url']) && isset($imgArray['img_path'])) {
                    $imgArray['full_url'] = url('storage/app/public/' . $imgArray['img_path']);
                }
                return [
                    'id' => $imgArray['id'] ?? null,
                    'img_path' => $imgArray['img_path'] ?? null,
                    'full_url' => $imgArray['full_url'] ?? null,
                    'created_at' => $imgArray['created_at'] ?? null,
                    'updated_at' => $imgArray['updated_at'] ?? null,
                ];
            }, $imagesArray);
        } else {
            $userArray['images'] = [];
        }

        return $userArray;
    }
}
