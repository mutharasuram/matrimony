<?php

namespace App\Http\Controllers\API;

use App\Models\Profile;
use App\Models\ProfileImg;
use App\Models\User;
use App\Models\Interest;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Shortlist;

class ProfileController extends BaseController
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'profile_created_by' => 'required|in:self,parent,sibling,relative,friend',
            'gender' => 'required|in:male,female',
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'mother_tongue' => 'required|string|max:255',
            'subcaste' => 'nullable|string|max:255',
            'sub_caste_details' => 'nullable|string|max:255',
            'willing_to_marry_from_subcaste' => 'required|in:yes,no',
            'marital_status' => 'required|in:Unmarried,Widower,Divorced,Separated',
            'country_living_in' => 'required|string|max:255',
            'residing_state' => 'required|string|max:255',
            'residing_city' => 'required|string|max:255',
            'citizenship' => 'required|string|max:255',
            'height' => 'required|string|max:255',
            'education' => 'required|string|max:255',
            'employed_in' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'annual_income' => 'nullable|string|max:255',
            'physical_status' => 'required|in:normal,physically_challenged',
            'family_status' => 'required|in:middle_class,upper_middle class,rich_affluent',
            'family_type' => 'required|in:joint_family,nuclear_family',
            'about_me' => 'nullable|string',
            'dosham' => 'required|in:yes,no,donot_know',
            'star_nakshatram' => 'nullable|string|max:255',
            'rasi' => 'nullable|string|max:255',
            'gothram' => 'nullable|string|max:255',
            'time_of_birth' => 'nullable|date_format:H:i',
            'country_of_birth' => 'nullable|string|max:255',
            'state_of_birth' => 'nullable|string|max:255',
            'city_of_birth' => 'nullable|string|max:255',
            'horoscope_chart_style' => 'nullable|string|max:255',
        ]);

        $profile = Profile::create($validatedData);

        // Return a response
        return response()->json([
            'message' => 'Profile created successfully',
            'profile' => $profile
        ], 201);
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'profile_created_by' => 'sometimes|in:self,parent,sibling,relative,friend',
                'gender' => 'sometimes|in:male,female',
                'name' => 'sometimes|string|max:255',
                'dob' => 'sometimes|date',
                'mother_tongue' => 'sometimes|string|max:255',
                'subcaste' => 'nullable|string|max:255',
                'sub_caste_details' => 'nullable|string|max:255',
                'willing_to_marry_from_subcaste' => 'sometimes|in:yes,no',
                'marital_status' => 'sometimes|in:Unmarried,Widower,Divorced,Separated',
                'country_living_in' => 'sometimes|string|max:255',
                'residing_state' => 'sometimes|string|max:255',
                'residing_city' => 'sometimes|string|max:255',
                'citizenship' => 'sometimes|string|max:255',
                'height' => 'sometimes|string|max:255',
                'education' => 'sometimes|string|max:255',
                'employed_in' => 'nullable|string|max:255',
                'occupation' => 'nullable|string|max:255',
                'annual_income' => 'nullable|string|max:255',
                'physical_status' => 'sometimes|in:normal,physically_challenged',
                'family_status' => 'sometimes|in:poor,lower_middle,middle_class,upper_middle class,rich_affluent',
                'family_type' => 'sometimes|in:joint_family,nuclear_family,small_family',
                'about_me' => 'nullable|string',
                'dosham' => 'sometimes|in:yes,no,donot_know',
                'star_nakshatram' => 'nullable|string|max:255',
                'rasi' => 'nullable|string|max:255',
                'gothram' => 'nullable|string|max:255',
                'time_of_birth' => 'nullable|date_format:H:i',
                'country_of_birth' => 'nullable|string|max:255',
                'state_of_birth' => 'nullable|string|max:255',
                'city_of_birth' => 'nullable|string|max:255',
                'horoscope_chart_style' => 'nullable|string|max:255',
                // New preference fields
                'preferred_eating_habit' => 'nullable|string|max:255',
                'preferred_drinking_habit' => 'nullable|string|max:255',
                'preferred_smoking_habit' => 'nullable|string|max:255',
                'preferred_hobbies_and_interests' => 'nullable|string',
                'preferred_music' => 'nullable|string',
                'preferred_sports' => 'nullable|string',
                'preferred_food' => 'nullable|string',
                // New fields from migration
                'about_my_family' => 'nullable|string|max:255',
                'fewlines_about_my_partner' => 'nullable|string|max:255',
            ]);

            $userId = $validatedData['user_id'];
            
            // Find the user and their profile
            $user = User::with('profile')->where('id', $userId)->first();
            
            if (!$user) {
                return $this->sendError('User not found.', [], 404);
            }
            
            if (!$user->profile) {
                return $this->sendError('User profile not found.', [], 404);
            }

            // Remove user_id from the data as it's not needed for update
            unset($validatedData['user_id']);
            
            // Update the profile
            $user->profile->update($validatedData);
            
            // Refresh the profile to get updated data
            $user->profile->refresh();

            return $this->sendResponse([
                'profile' => $user->profile
            ], 'Profile updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Error updating profile.', ['error' => $e->getMessage()], 500);
        }
    }

    public function profile_img_store(Request $request)
    {
        try {
            // Validate the input
            $validatedData = $request->validate([
                'id' => 'required',
                'profile_img' => 'required|array',
                'profile_img.*' => 'image|mimes:jpeg,png,jpg',
            ]);
            $id = $request->id;
            $userData = User::with('profile')->where('id', $id)->first();
            if (!$userData || !$userData->profile) {
                return $this->sendError('User profile not found.', array(), 404);

            }
            $uploadedFiles = $request->file('profile_img');
            if (!is_array($uploadedFiles)) {
                $uploadedFiles = [$uploadedFiles];
            }
            $storedImages = [];
            foreach ($uploadedFiles as $image) {
                if ($image->isValid()) {
                    $path = $image->store('profile_images', 'public');
                    ProfileImg::insert([
                        'profile_id' => $userData->profile->id,
                        'img_path' => $path,
                    ]);
                    $storedImages[] = url('storage/app/public/' . $path);
                } else {
                    return $this->sendError('Invalid file upload.', array(), 404);
                }
            }

            return $this->sendResponse($storedImages, 'Profile Images uploaded successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Error uploading profile images.', ['error' => $e->getMessage()], 404);
        }
    }
    public function shortlist(Request $request)
    {
        try {
            // Get parameters from request - handle both JSON and form data
            $id = $request->get('id');
            $shorted_id = $request->get('shorted_id');
            
            // If not found, try JSON data
            if (!$id || !$shorted_id) {
                $jsonData = $request->json() ? $request->json()->all() : [];
                $id = $id ?: ($jsonData['id'] ?? null);
                $shorted_id = $shorted_id ?: ($jsonData['shorted_id'] ?? null);
            }
            
            // Validate that we have the required parameters
            if (!$id || !$shorted_id) {
                return $this->sendError('Missing required parameters.', [
                    'id' => $id,
                    'shorted_id' => $shorted_id
                ], 400);
            }
            
            // Check if both users exist
            if (!User::where('id', $id)->exists()) {
                return $this->sendError('User not found.', ['id' => $id], 404);
            }
            
            if (!User::where('id', $shorted_id)->exists()) {
                return $this->sendError('User to shortlist not found.', ['shorted_id' => $shorted_id], 404);
            }
            
            if ($id == $shorted_id) {
                return $this->sendError('Cannot shortlist yourself.', [], 400);
            }
            
            // Check if already shortlisted
            $short_data = Shortlist::where(['user_id' => $id, 'shorted_id' => $shorted_id])->first();
            
            if ($short_data) {
                // Remove from shortlist
                $short_data->delete();
                return $this->sendResponse([
                    'action' => 'removed',
                    'shortlisted' => false
                ], 'Profile removed from shortlist successfully!');
            } else {
                // Add to shortlist
                Shortlist::create([
                    'user_id' => $id,
                    'shorted_id' => $shorted_id
                ]);
                return $this->sendResponse([
                    'action' => 'added',
                    'shortlisted' => true
                ], 'Profile added to shortlist successfully!');
            }
        } catch (\Exception $e) {
            return $this->sendError('Error processing request.', ['error' => $e->getMessage()], 500);
        }
    }
    public function delete_account(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:users,id', 
            ]);
            
            $userId = $validatedData['user_id'];
            
            // Find the user
            $user = User::where('id', $userId)->first();
            
            if (!$user) {
                return $this->sendError('User not found.', [], 404);
            }
            
            // Delete the user (cascade will handle related records)
            $user->delete();
            
            return $this->sendResponse([
                'deleted' => true,
                'user_id' => $userId
            ], 'User account deleted successfully!');
       
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Error processing request.', ['error' => $e->getMessage()], 500);
        }  
    }

    public function getUserDetails(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'current_user_id' => 'nullable|exists:users,id',
            ]);

            $userId = $request->user_id;
            $currentUserId = $request->current_user_id;

            // Get user with profile and images
            $user = User::with(['profile.images'])
                ->where('id', $userId)
                ->first();

            if (!$user) {
                return $this->sendError('User not found.', [], 404);
            }

            // Define all profile fields for completion tracking
            $allProfileFields = [
                // Basic Information
                'profile_created_by', 'gender', 'name', 'dob', 'mother_tongue',
                'subcaste', 'sub_caste_details', 'willing_to_marry_from_subcaste',
                'marital_status', 'height', 'physical_status',
                // Location
                'country_living_in', 'residing_state', 'residing_city', 'citizenship',
                'country_of_birth', 'state_of_birth', 'city_of_birth',
                // Education & Career
                'education', 'education_category', 'employed_in', 'occupation',
                'annual_income', 'income',
                // Family
                'family_status', 'family_type', 'father_occupation', 'mother_occupation',
                'no_of_brothers', 'no_of_sisters',
                // About
                'about_me', 'about_my_family', 'fewlines_about_my_partner',
                // Astrology
                'dosham', 'star_nakshatram', 'rasi', 'gothram', 'time_of_birth',
                'horoscope_chart_style',
                // Habits
                'eating_habit', 'drinking_habit', 'smoking_habit', 'habit',
                'hobbies_and_interests', 'music', 'sports', 'food',
                // Partner Preferences - Age & Physical
                'preferred_age_min', 'preferred_age_max', 'preferred_height_min',
                'preferred_height_max', 'preferred_marital_status', 'preferred_physical_status',
                // Partner Preferences - Background
                'preferred_mother_tongue', 'preferred_subcaste', 'preferred_chevvai_dosham',
                'preferred_citizenship',
                // Partner Preferences - Education & Career
                'preferred_education', 'preferred_employed_in', 'preferred_occupation',
                'preferred_annual_income_min', 'preferred_annual_income_max',
                'preferred_country',
                // Partner Preferences - Habits
                'preferred_eating_habit', 'preferred_drinking_habit', 'preferred_smoking_habit',
                'preferred_hobbies_and_interests', 'preferred_music', 'preferred_sports',
                'preferred_food',
                // Other
                'isEligible'
            ];

            // Format the response with ALL user fields
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'm_id' => $user->m_id,
                'is_admin' => $user->is_admin ?? false,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'profile' => null,
                'images' => [],
                'profile_completion' => [
                    'filled_fields' => 0,
                    'total_fields' => count($allProfileFields),
                    'percentage' => 0,
                    'missing_fields' => []
                ]
            ];

            // Add profile data if exists
            if ($user->profile) {
                $profile = $user->profile;
                
                // Calculate profile completion
                $filledFields = 0;
                $missingFields = [];
                
                foreach ($allProfileFields as $field) {
                    $value = $profile->$field;
                    if ($value !== null && $value !== '' && $value !== '0') {
                        $filledFields++;
                    } else {
                        $missingFields[] = $field;
                    }
                }
                
                $percentage = count($allProfileFields) > 0 
                    ? round(($filledFields / count($allProfileFields)) * 100, 2) 
                    : 0;

                $userData['profile'] = [
                    // Basic Information
                    'id' => $profile->id,
                    'user_id' => $profile->user_id,
                    'profile_created_by' => $profile->profile_created_by,
                    'gender' => $profile->gender,
                    'name' => $profile->name,
                    'dob' => $profile->dob,
                    'mother_tongue' => $profile->mother_tongue,
                    'subcaste' => $profile->subcaste,
                    'sub_caste_details' => $profile->sub_caste_details,
                    'willing_to_marry_from_subcaste' => $profile->willing_to_marry_from_subcaste,
                    'marital_status' => $profile->marital_status,
                    'height' => $profile->height,
                    'physical_status' => $profile->physical_status,
                    // Location
                    'country_living_in' => $profile->country_living_in,
                    'residing_state' => $profile->residing_state,
                    'residing_city' => $profile->residing_city,
                    'citizenship' => $profile->citizenship,
                    'country_of_birth' => $profile->country_of_birth,
                    'state_of_birth' => $profile->state_of_birth,
                    'city_of_birth' => $profile->city_of_birth,
                    // Education & Career
                    'education' => $profile->education,
                    'education_category' => $profile->education_category,
                    'employed_in' => $profile->employed_in,
                    'occupation' => $profile->occupation,
                    'annual_income' => $profile->annual_income,
                    'income' => $profile->income,
                    // Family
                    'family_status' => $profile->family_status,
                    'family_type' => $profile->family_type,
                    'father_occupation' => $profile->father_occupation,
                    'mother_occupation' => $profile->mother_occupation,
                    'no_of_brothers' => $profile->no_of_brothers,
                    'no_of_sisters' => $profile->no_of_sisters,
                    // About
                    'about_me' => $profile->about_me,
                    'about_my_family' => $profile->about_my_family,
                    'fewlines_about_my_partner' => $profile->fewlines_about_my_partner,
                    // Astrology
                    'dosham' => $profile->dosham,
                    'star_nakshatram' => $profile->star_nakshatram,
                    'rasi' => $profile->rasi,
                    'gothram' => $profile->gothram,
                    'time_of_birth' => $profile->time_of_birth,
                    'horoscope_chart_style' => $profile->horoscope_chart_style,
                    // Habits
                    'eating_habit' => $profile->eating_habit,
                    'drinking_habit' => $profile->drinking_habit,
                    'smoking_habit' => $profile->smoking_habit,
                    'habit' => $profile->habit,
                    'hobbies_and_interests' => $profile->hobbies_and_interests,
                    'music' => $profile->music,
                    'sports' => $profile->sports,
                    'food' => $profile->food,
                    // Partner Preferences - Age & Physical
                    'preferred_age_min' => $profile->preferred_age_min,
                    'preferred_age_max' => $profile->preferred_age_max,
                    'preferred_height_min' => $profile->preferred_height_min,
                    'preferred_height_max' => $profile->preferred_height_max,
                    'preferred_marital_status' => $profile->preferred_marital_status,
                    'preferred_physical_status' => $profile->preferred_physical_status,
                    // Partner Preferences - Background
                    'preferred_mother_tongue' => $profile->preferred_mother_tongue,
                    'preferred_subcaste' => $profile->preferred_subcaste,
                    'preferred_chevvai_dosham' => $profile->preferred_chevvai_dosham,
                    'preferred_citizenship' => $profile->preferred_citizenship,
                    // Partner Preferences - Education & Career
                    'preferred_education' => $profile->preferred_education,
                    'preferred_employed_in' => $profile->preferred_employed_in,
                    'preferred_occupation' => $profile->preferred_occupation,
                    'preferred_annual_income_min' => $profile->preferred_annual_income_min,
                    'preferred_annual_income_max' => $profile->preferred_annual_income_max,
                    'preferred_country' => $profile->preferred_country,
                    // Partner Preferences - Habits
                    'preferred_eating_habit' => $profile->preferred_eating_habit,
                    'preferred_drinking_habit' => $profile->preferred_drinking_habit,
                    'preferred_smoking_habit' => $profile->preferred_smoking_habit,
                    'preferred_hobbies_and_interests' => $profile->preferred_hobbies_and_interests,
                    'preferred_music' => $profile->preferred_music,
                    'preferred_sports' => $profile->preferred_sports,
                    'preferred_food' => $profile->preferred_food,
                    // Other
                    'isEligible' => $profile->isEligible,
                    // Timestamps
                    'created_at' => $profile->created_at,
                    'updated_at' => $profile->updated_at,
                ];

                // Update profile completion data
                $userData['profile_completion'] = [
                    'filled_fields' => $filledFields,
                    'total_fields' => count($allProfileFields),
                    'percentage' => $percentage,
                    'missing_fields' => $missingFields
                ];

                // Add images if they exist
                if ($user->profile->images && $user->profile->images->count() > 0) {
                    $userData['images'] = $user->profile->images->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'img_path' => $image->img_path,
                            'full_url' => url('storage/app/public/' . $image->img_path),
                            'created_at' => $image->created_at,
                            'updated_at' => $image->updated_at,
                        ];
                    })->toArray();
                }
            } else {
                // If no profile exists, all fields are missing
                $userData['profile_completion'] = [
                    'filled_fields' => 0,
                    'total_fields' => count($allProfileFields),
                    'percentage' => 0,
                    'missing_fields' => $allProfileFields
                ];
            }

            // Add interest information if current_user_id is provided
            if ($currentUserId) {
                $interest = Interest::where('sender_id', $currentUserId)
                    ->where('receiver_id', $userId)
                    ->first();
                
                $userData['has_sent_interest'] = $interest !== null;
                
                // Also add interest status if interest exists
                if ($interest) {
                    $userData['interest_status'] = $interest->status; // pending, accepted, declined, replied
                } else {
                    $userData['interest_status'] = null;
                }
            } else {
                $userData['has_sent_interest'] = false;
                $userData['interest_status'] = null;
            }

            return $this->sendResponse($userData, 'User details retrieved successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving user details.', ['error' => $e->getMessage()], 500);
        }
    }

    public function deleteProfileImage(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'image_id' => 'required|integer|exists:profile_img,id',
            ]);

            $imageId = $validatedData['image_id'];

            // Find the profile image
            $profileImage = ProfileImg::find($imageId);

            if (!$profileImage) {
                return $this->sendError('Profile image not found.', [], 404);
            }

            // Delete the physical file from storage
            $filePath = storage_path('app/public/' . $profileImage->img_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete the database record
            $profileImage->delete();

            return $this->sendResponse([
                'deleted' => true,
                'image_id' => $imageId,
                'deleted_path' => $profileImage->img_path
            ], 'Profile image deleted successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Error deleting profile image.', ['error' => $e->getMessage()], 500);
        }
    }
}
