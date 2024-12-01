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
                    $storedImages[] = asset('storage/' . $path);
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
    public function Shortlist(Request $request)
{
    try {
        $validatedData = $request->validate([
            'id' => 'required', 
            'shorted_id' => 'required', 
        ]);
        $id = $request->id;
        $shorted_id = $request->shorted_id;
        $short_data = Shortlist::where(['user_id' => $id, 'shorted_id' => $shorted_id])->first();
        if ($short_data) {
            $data = Shortlist::where([
                'user_id' => $id,
                'shorted_id' => $shorted_id
            ])->delete();
           $item='true';
            return $this->sendResponse($item, 'Shortlisted Profile Removed successfully!');
        } else {
            $data = Shortlist::insert([
                'user_id' => $id,
                'shorted_id' => $shorted_id
            ]);
            $item='true';
            return $this->sendResponse($item, 'Shortlisted Profile added successfully!');
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        return $this->sendError('Validation Error', $e->errors(), 422);
    } catch (\Exception $e) {
        return $this->sendError('Error processing request.', ['error' => $e->getMessage()], 500);
    }
}
}
