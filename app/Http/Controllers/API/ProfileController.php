<?php

namespace App\Http\Controllers\API;

use App\Models\Profile;
use App\Models\ProfileImg;
use App\Models\User;
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
                'family_status' => 'sometimes|in:middle_class,upper_middle class,rich_affluent',
                'family_type' => 'sometimes|in:joint_family,nuclear_family',
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
            ]);

            $userId = $request->user_id;

            // Get user with profile and images
            $user = User::with(['profile.images'])
                ->where('id', $userId)
                ->first();

            if (!$user) {
                return $this->sendError('User not found.', [], 404);
            }

            // Format the response
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'm_id' => $user->m_id,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'profile' => null,
                'images' => []
            ];

            // Add profile data if exists
            if ($user->profile) {
                $userData['profile'] = [
                    'id' => $user->profile->id,
                    'profile_created_by' => $user->profile->profile_created_by,
                    'gender' => $user->profile->gender,
                    'name' => $user->profile->name,
                    'dob' => $user->profile->dob,
                    'mother_tongue' => $user->profile->mother_tongue,
                    'subcaste' => $user->profile->subcaste,
                    'sub_caste_details' => $user->profile->sub_caste_details,
                    'willing_to_marry_from_subcaste' => $user->profile->willing_to_marry_from_subcaste,
                    'marital_status' => $user->profile->marital_status,
                    'country_living_in' => $user->profile->country_living_in,
                    'residing_state' => $user->profile->residing_state,
                    'residing_city' => $user->profile->residing_city,
                    'citizenship' => $user->profile->citizenship,
                    'height' => $user->profile->height,
                    'education' => $user->profile->education,
                    'employed_in' => $user->profile->employed_in,
                    'occupation' => $user->profile->occupation,
                    'annual_income' => $user->profile->annual_income,
                    'physical_status' => $user->profile->physical_status,
                    'family_status' => $user->profile->family_status,
                    'family_type' => $user->profile->family_type,
                    'about_me' => $user->profile->about_me,
                    'dosham' => $user->profile->dosham,
                    'star_nakshatram' => $user->profile->star_nakshatram,
                    'rasi' => $user->profile->rasi,
                    'gothram' => $user->profile->gothram,
                    'time_of_birth' => $user->profile->time_of_birth,
                    'country_of_birth' => $user->profile->country_of_birth,
                    'state_of_birth' => $user->profile->state_of_birth,
                    'city_of_birth' => $user->profile->city_of_birth,
                    'horoscope_chart_style' => $user->profile->horoscope_chart_style,
                    'created_at' => $user->profile->created_at,
                    'updated_at' => $user->profile->updated_at,
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
            }

            return $this->sendResponse($userData, 'User details retrieved successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving user details.', ['error' => $e->getMessage()], 500);
        }
    }
}
