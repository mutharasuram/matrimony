<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Otp;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Services\MatchesService;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
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

    /**
     * Register api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $contentType = $request->header('Content-Type');
            if (str_contains($contentType, 'application/json')) {
                $input = $request->json()->all(); // For raw JSON
            } else {
                $input = $request->all(); // For form-data
            }

            $validator = Validator::make($input, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'mobile' => 'required|string|max:15|unique:users,mobile',
                // Profile-related validation rules
                'profile_created_by' => 'required|in:self,parent,sibling,relative,friend',
                'gender' => 'required|in:male,female',
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
                'family_status' => 'required|in:poor,lower_middle,middle_class,upper_middle_class,rich_affluent',
                'family_type' => 'required|in:joint_family,nuclear_family,small_family',
                'about_me' => 'nullable|string',
                'dosham' => 'required|in:yes,no,donot_know',
                'dosham_value' => 'nullable|string|max:255',
                'star_nakshatram' => 'nullable|string|max:255',
                'rasi' => 'nullable|string|max:255',
                'gothram' => 'nullable|string|max:255',
                'time_of_birth' => 'nullable|date_format:H:i',
                'country_of_birth' => 'nullable|string|max:255',
                'state_of_birth' => 'nullable|string|max:255',
                'city_of_birth' => 'nullable|string|max:255',
                'horoscope_chart_style' => 'nullable|string|max:255',
                'education_category' => 'nullable|string|max:255',
                'habit' => 'nullable|string|max:255',
                'isEligible' => 'nullable|boolean',
                'income' => 'nullable|string|max:255',
                // Family Details validation rules
                'father_occupation' => 'nullable|string|max:255',
                'mother_occupation' => 'nullable|string|max:255',
                'no_of_brothers' => 'nullable|integer|min:0',
                'no_of_sisters' => 'nullable|integer|min:0',
                // Partner Preference validation rules
                'preferred_age_min' => 'nullable|string|max:255',
                'preferred_age_max' => 'nullable|string|max:255',
                'preferred_height_min' => 'nullable|string|max:255',
                'preferred_height_max' => 'nullable|string|max:255',
                'preferred_marital_status' => 'nullable|string|max:255',
                'preferred_physical_status' => 'nullable|string|max:255',
                'preferred_mother_tongue' => 'nullable|string|max:255',
                'preferred_subcaste' => 'nullable|string|max:255',
                'preferred_subcaste_details' => 'nullable|string|max:255',
                'preferred_chevvai_dosham' => 'nullable|string|max:255',
                'preferred_education' => 'nullable|string|max:255',
                'preferred_employed_in' => 'nullable|string|max:255',
                'preferred_occupation' => 'nullable|string|max:255',
                'preferred_annual_income_min' => 'nullable|string|max:255',
                'preferred_annual_income_max' => 'nullable|string|max:255',
                'preferred_country' => 'nullable|string|max:255',
                'preferred_citizenship' => 'nullable|string|max:255',
                'eating_habit' => 'nullable|string|max:255',
                'drinking_habit' => 'nullable|string|max:255',
                'smoking_habit' => 'nullable|string|max:255',
                'hobbies_and_interests' => 'nullable|string',
                'music' => 'nullable|string',
                'sports' => 'nullable|string',
                'food' => 'nullable|string',
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
                'fewlines_about_my_partner' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $mId = User::generateUniqueMId();
            $input['password'] = bcrypt($input['password']);
            $input['m_id'] = $mId;
            $user = User::create($input);
            $profileData = $request->only([
                'profile_created_by',
                'gender',
                'name',
                'dob',
                'mother_tongue',
                'subcaste',
                'sub_caste_details',
                'willing_to_marry_from_subcaste',
                'marital_status',
                'country_living_in',
                'residing_state',
                'residing_city',
                'citizenship',
                'height',
                'education',
                'employed_in',
                'occupation',
                'annual_income',
                'physical_status',
                'family_status',
                'family_type',
                'about_me',
                'dosham',
                'dosham_value',
                'star_nakshatram',
                'rasi',
                'gothram',
                'time_of_birth',
                'country_of_birth',
                'state_of_birth',
                'city_of_birth',
                'horoscope_chart_style',
                'education_category',
                'habit',
                'isEligible',
                'income',
                // Family Details fields
                'father_occupation',
                'mother_occupation',
                'no_of_brothers',
                'no_of_sisters',
                // Partner Preference fields
                'preferred_age_min',
                'preferred_age_max',
                'preferred_height_min',
                'preferred_height_max',
                'preferred_marital_status',
                'preferred_physical_status',
                'preferred_mother_tongue',
                'preferred_subcaste',
                'preferred_subcaste_details',
                'preferred_chevvai_dosham',
                'preferred_education',
                'preferred_employed_in',
                'preferred_occupation',
                'preferred_annual_income_min',
                'preferred_annual_income_max',
                'preferred_country',
                'preferred_citizenship',
                'eating_habit',
                'drinking_habit',
                'smoking_habit',
                'hobbies_and_interests',
                'music',
                'sports',
                'food',
                // New preference fields
                'preferred_eating_habit',
                'preferred_drinking_habit',
                'preferred_smoking_habit',
                'preferred_hobbies_and_interests',
                'preferred_music',
                'preferred_sports',
                'preferred_food',
                // New fields from migration
                'about_my_family' => 'nullable|string|max:255',
                'fewlines_about_my_partner' => 'nullable|string|max:255'
            ]);
            $profileData['user_id'] = $user->id;
            Profile::create($profileData);
            /** @var User $user */
            $success['token'] =  $user->createToken('auth_token')->plainTextToken;
            $success['user'] =  User::with('profile', 'profile.images')->where('id', $user->id)->first();
            // $success['list'] = $this->matchesService->getJustJoined($user->id);
            DB::commit();
            return $this->sendResponse($success, 'User register successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Error', $e->getMessage());
        }
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            if (Auth::attempt(['email' => $request->value, 'password' => $request->password])) {
                $user = Auth::user();
                /** @var User $user */
                $success['token'] =  $user->createToken('auth_token')->plainTextToken;
                $success['user'] =  User::with('profile', 'profile.images')->where('id', $user->id)->first();
                // $success['list'] = $this->matchesService->getJustJoined($user->id);
                return $this->sendResponse($success, 'User login successfully.');
            } else if (Auth::attempt(['m_id' => $request->value, 'password' => $request->password])) {
                $user = Auth::user();
                /** @var User $user */
                $success['token'] =  $user->createToken('auth_token')->plainTextToken;
                $success['user'] =  User::with('profile', 'profile.images')->where('id', $user->id)->first();
                // $success['list'] = $this->matchesService->getJustJoined($user->id);
                return $this->sendResponse($success, 'User login successfully.');
            } else if (Auth::attempt(['mobile' => $request->value, 'password' => $request->password])) {
                $user = Auth::user();
                /** @var User $user */
                $success['token'] =  $user->createToken('auth_token')->plainTextToken;
                $success['user'] =  User::with('profile', 'profile.images')->where('id', $user->id)->first();
                // $success['list'] = $this->matchesService->getJustJoined($user->id);
                return $this->sendResponse($success, 'User login successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Error', $e->getMessage());
        }
    }

    public function sendSms(Request $request)
    {
        // try {
        $apiKey = env('FAST2SMS_API_KEY');
        $url = "https://www.fast2sms.com/dev/bulkV2";

        $otp = rand(100000, 999999);
        $fields = [
            "route" => "dlt",
            "sender_id" => "LKGBUS",
            "message" => "168388",
            "variables_values" => $otp,
            "flash" => 0,
            "numbers" => $request->mobile
        ];
        $response = Http::withHeaders([
            "authorization" => $apiKey,
            "Content-Type" => "application/json"
        ])->post($url, $fields);


        $responseBody = json_decode($response->body(), true);

        if (!$responseBody) {
            return response()->json([
                "success" => false,
                "message" => "Invalid API Response",
                "data" => [
                    "error" => "Fast2SMS did not return valid JSON",
                    "raw_response" => $response->body()
                ]
            ], 400);
        }

        // Check if SMS was sent successfully
        if (isset($responseBody['return']) && $responseBody['return']) {
            // Store OTP in database
            Otp::where('mobile', $request->mobile)->delete();
            Otp::create(['mobile' => $request->mobile, 'otp' => $otp]);

            return response()->json([
                "success" => true,
                "message" => "OTP sent successfully",
                "data" => $responseBody
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Failed to send OTP",
                "data" => [
                    "error" => $responseBody['message'] ?? "Unknown Error",
                    "full_response" => $responseBody
                ]
            ], 400);
        }
        // } catch (\Exception $e) {
        //     return response()->json([
        //         "success" => false,
        //         "message" => "Error",
        //         "data" => ["error" => $e->getMessage()]
        //     ]);
        // }
    }

    public function verifyOtp(Request $request)
    {
        try {

            $request->validate([
                'mobile' => 'required|string',
                'otp' => 'required|digits:6',
            ]);

            $otpRecord = Otp::where('mobile', $request->mobile)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($otpRecord && $otpRecord->otp == $request->otp) {
                $createdAt = $otpRecord->created_at;
                $now = now();

                if ($now->diffInMinutes($createdAt) <= 5) {
                    $otpRecord->delete();

                    return $this->sendResponse(true, 'OTP verified successfully.');
                } else {
                    return $this->sendError('OTP expired.', ['error' => 'The OTP has expired.'], 400);
                }
            } else {
                return $this->sendError('Invalid OTP or mobile number.', ['error' => 'The OTP provided is incorrect.'], 400);
            }
        } catch (\Exception $e) {
            return $this->sendError('Error', ['error' => $e->getMessage()]);
        }
    }



    public function checkIsExist(Request $request)
    {
        try {
            if ($request->type === 'mobile') {
                $value = User::isMobileExist($request->value);
                if ($value) {
                    return $this->sendResponse(true, 'Mobile number exists.');
                } else {
                    return $this->sendResponse(false, 'Mobile number does not exists.');
                }
            } else if ($request->type === 'email') {
                $value = User::isEmailExist($request->value);
                if ($value) {
                    return $this->sendResponse(true, 'Email exists.');
                } else {
                    return $this->sendResponse(false, 'Email does not exists.');
                }
            } else {
                return $this->sendError('Invalid type', ['error' => 'Invalid type']);
            }
        } catch (\Exception $e) {
            return $this->sendError('Error', ['error' => $e->getMessage()]);
        }
    }

    public function updatePassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|string',
                'password' => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $user = User::where('mobile', $request->mobile)->first();
            if (!$user) {
                return $this->sendError('User not found.', ['error' => 'User not found']);
            }
            $user->password = bcrypt($request->password);
            $user->save();
            DB::commit();
            return $this->sendResponse(true, 'Password updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Error', ['error' => $e->getMessage()]);
        }
    }

    public function getUserDetails($id)
    {
        try {
            $user = User::with(['profile', 'profile.images'])->find($id);

            if (!$user) {
                return $this->sendError('User not found.', ['error' => 'User does not exist.']);
            }

            return $this->sendResponse($user, 'User details retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Error', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update profile api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $contentType = $request->header('Content-Type');
            if (str_contains($contentType, 'application/json')) {
                $input = $request->json()->all(); // For raw JSON
            } else {
                $input = $request->all(); // For form-data
            }

            $validator = Validator::make($input, [
                'user_id' => 'required|exists:users,id',
                // Profile-related validation rules
                'profile_created_by' => 'nullable|in:self,parent,sibling,relative,friend',
                'gender' => 'nullable|in:male,female',
                'name' => 'nullable|string|max:255',
                'dob' => 'nullable|date',
                'mother_tongue' => 'nullable|string|max:255',
                'subcaste' => 'nullable|string|max:255',
                'sub_caste_details' => 'nullable|string|max:255',
                'willing_to_marry_from_subcaste' => 'nullable|in:yes,no',
                'marital_status' => 'nullable|in:Unmarried,Widower,Divorced,Separated',
                'country_living_in' => 'nullable|string|max:255',
                'residing_state' => 'nullable|string|max:255',
                'residing_city' => 'nullable|string|max:255',
                'citizenship' => 'nullable|string|max:255',
                'height' => 'nullable|string|max:255',
                'education' => 'nullable|string|max:255',
                'employed_in' => 'nullable|string|max:255',
                'occupation' => 'nullable|string|max:255',
                'annual_income' => 'nullable|string|max:255',
                'physical_status' => 'nullable|in:normal,physically_challenged',
                'family_status' => 'nullable|in:poor,lower_middle,middle_class,upper_middle_class,rich_affluent',
                'family_type' => 'nullable|in:joint_family,nuclear_family,small_family',
                'about_me' => 'nullable|string',
                'dosham' => 'nullable|in:yes,no,donot_know',
                'dosham_value' => 'nullable|string|max:255',
                'star_nakshatram' => 'nullable|string|max:255',
                'rasi' => 'nullable|string|max:255',
                'gothram' => 'nullable|string|max:255',
                'time_of_birth' => 'nullable|date_format:H:i',
                'country_of_birth' => 'nullable|string|max:255',
                'state_of_birth' => 'nullable|string|max:255',
                'city_of_birth' => 'nullable|string|max:255',
                'horoscope_chart_style' => 'nullable|string|max:255',
                'education_category' => 'nullable|string|max:255',
                'habit' => 'nullable|string|max:255',
                'isEligible' => 'nullable|boolean',
                'income' => 'nullable|string|max:255',
                // Family Details validation rules
                'father_occupation' => 'nullable|string|max:255',
                'mother_occupation' => 'nullable|string|max:255',
                'no_of_brothers' => 'nullable|integer|min:0',
                'no_of_sisters' => 'nullable|integer|min:0',
                // Partner Preference validation rules
                'preferred_age_min' => 'nullable|string|max:255',
                'preferred_age_max' => 'nullable|string|max:255',
                'preferred_height_min' => 'nullable|string|max:255',
                'preferred_height_max' => 'nullable|string|max:255',
                'preferred_marital_status' => 'nullable|string|max:255',
                'preferred_physical_status' => 'nullable|string|max:255',
                'preferred_mother_tongue' => 'nullable|string|max:255',
                'preferred_subcaste' => 'nullable|string|max:255',
                'preferred_subcaste_details' => 'nullable|string|max:255',
                'preferred_chevvai_dosham' => 'nullable|string|max:255',
                'preferred_education' => 'nullable|string|max:255',
                'preferred_employed_in' => 'nullable|string|max:255',
                'preferred_occupation' => 'nullable|string|max:255',
                'preferred_annual_income_min' => 'nullable|string|max:255',
                'preferred_annual_income_max' => 'nullable|string|max:255',
                'preferred_country' => 'nullable|string|max:255',
                'preferred_citizenship' => 'nullable|string|max:255',
                'eating_habit' => 'nullable|string|max:255',
                'drinking_habit' => 'nullable|string|max:255',
                'smoking_habit' => 'nullable|string|max:255',
                'hobbies_and_interests' => 'nullable|string',
                'music' => 'nullable|string',
                'sports' => 'nullable|string',
                'food' => 'nullable|string',
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

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $user = User::find($input['user_id']);
            if (!$user) {
                return $this->sendError('User not found.', ['error' => 'User does not exist.']);
            }

            $profile = Profile::where('user_id', $user->id)->first();
            if (!$profile) {
                return $this->sendError('Profile not found.', ['error' => 'Profile does not exist.']);
            }

            $profileData = $request->only([
                'profile_created_by',
                'gender',
                'name',
                'dob',
                'mother_tongue',
                'subcaste',
                'sub_caste_details',
                'willing_to_marry_from_subcaste',
                'marital_status',
                'country_living_in',
                'residing_state',
                'residing_city',
                'citizenship',
                'height',
                'education',
                'employed_in',
                'occupation',
                'annual_income',
                'physical_status',
                'family_status',
                'family_type',
                'about_me',
                'dosham',
                'dosham_value',
                'star_nakshatram',
                'rasi',
                'gothram',
                'time_of_birth',
                'country_of_birth',
                'state_of_birth',
                'city_of_birth',
                'horoscope_chart_style',
                'education_category',
                'habit',
                'isEligible',
                'income',
                // Family Details fields
                'father_occupation',
                'mother_occupation',
                'no_of_brothers',
                'no_of_sisters',
                // Partner Preference fields
                'preferred_age_min',
                'preferred_age_max',
                'preferred_height_min',
                'preferred_height_max',
                'preferred_marital_status',
                'preferred_physical_status',
                'preferred_mother_tongue',
                'preferred_subcaste',
                'preferred_subcaste_details',
                'preferred_chevvai_dosham',
                'preferred_education',
                'preferred_employed_in',
                'preferred_occupation',
                'preferred_annual_income_min',
                'preferred_annual_income_max',
                'preferred_country',
                'preferred_citizenship',
                'eating_habit',
                'drinking_habit',
                'smoking_habit',
                // New preference fields
                'preferred_eating_habit',
                'preferred_drinking_habit',
                'preferred_smoking_habit',
                'preferred_hobbies_and_interests',
                'preferred_music',
                'preferred_sports',
                'preferred_food',
            ]);

            // Remove null values to avoid overwriting existing data with null
            $profileData = array_filter($profileData, function ($value) {
                return $value !== null;
            });

            $profile->update($profileData);

            $success['user'] = User::with('profile', 'profile.images')->where('id', $user->id)->first();

            DB::commit();
            return $this->sendResponse($success, 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Error', $e->getMessage());
        }
    }
}

// register mock data

// {
//     "name": "Mutharasu",
//     "email": "mutharasuram0@gmail.com",
//     "password": "Test@123",
//     "mobile": "7708468980",
//     "profile_created_by": "self",
//     "gender": "male",
//     "dob": "1990-01-01",
//     "mother_tongue": "English",
//     "subcaste": "Example Subcaste",
//     "sub_caste_details": "Details about the subcaste",
//     "willing_to_marry_from_subcaste": "yes",
//     "marital_status": "Unmarried",
//     "country_living_in": "United States",
//     "residing_state": "California",
//     "residing_city": "Los Angeles",
//     "citizenship": "American",
//     "height": "5.9",
//     "education": "Masters in Computer Science",
//     "employed_in": "Private Sector",
//     "occupation": "Software Engineer",
//     "annual_income": "100000",
//     "physical_status": "normal",
//     "family_status": "middle_class",
//     "family_type": "nuclear_family",
//     "about_me": "I am a software engineer looking for a compatible partner.",
//     "dosham": "no",
//     "star_nakshatram": "Ashwini",
//     "rasi": "Aries",
//     "gothram": "Example Gothram",
//     "time_of_birth": "10:30",
//     "country_of_birth": "United States",
//     "state_of_birth": "California",
//     "city_of_birth": "Los Angeles",
//     "horoscope_chart_style": "South Indian"
//   }