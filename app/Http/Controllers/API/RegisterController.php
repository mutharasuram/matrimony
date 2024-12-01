<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Otp;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Services\MatchesService;

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
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
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
                'family_status' => 'required|in:middle_class,upper_middle_class,rich_affluent',
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

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $mId = User::generateUniqueMId();
            $input = $request->all();
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
                'star_nakshatram',
                'rasi',
                'gothram',
                'time_of_birth',
                'country_of_birth',
                'state_of_birth',
                'city_of_birth',
                'horoscope_chart_style'
            ]);
            $profileData['user_id'] = $user->id;
            Profile::create($profileData);
            $success['token'] =  $user->createToken('auth_token')->plainTextToken;
            $success['user'] =  User::with('profile')->where('id', $user->id)->first();
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
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        try {
            if (Auth::attempt(['email' => $request->value, 'password' => $request->password])) {
                $user = Auth::user();
                $success['token'] =  $user->createToken('auth_token')->plainTextToken;
                $success['name'] =  User::with('profile')->where('id', $user->id)->first();
                $success['list'] = $this->matchesService->getJustJoined($user->id);
                return $this->sendResponse($success, 'User login successfully.');
            } else if (Auth::attempt(['m_id' => $request->value, 'password' => $request->password])) {
                $user = Auth::user();
                $success['token'] =  $user->createToken('auth_token')->plainTextToken;
                $success['name'] =  User::with('profile')->where('id', $user->id)->first();
                $success['list'] = $this->matchesService->getJustJoined($user->id);
                return $this->sendResponse($success, 'User login successfully.');
            } else if (Auth::attempt(['mobile' => $request->value, 'password' => $request->password])) {
                $user = Auth::user();
                $success['token'] =  $user->createToken('auth_token')->plainTextToken;
                $success['user'] =  User::with('profile')->where('id', $user->id)->first();
                $success['list'] = $this->matchesService->getJustJoined($user->id);
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
        try {
            $apiKey = env('FAST2SMS_API_KEY');
            $url = env('FAST2SMS_URL');
            $otp = rand(100000, 999999);
            $fields = [
                'variables_values' => $otp,
                'route' => 'otp',
                'numbers' => $request->mobile,
            ];

            $response = Http::withHeaders([
                'authorization' => $apiKey,
                'accept' => '*/*',
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
            ])->post($url, $fields);

            $responseBody = json_decode($response->body());

            if ($responseBody && $responseBody->return) {
                Otp::where('mobile', $request->mobile)
                    ->delete();
                Otp::create(['mobile' => $request->mobile, 'otp' => $otp]);
                return $this->sendResponse($responseBody, 'OTP sent successfully.');
            } else {
                return $this->sendError('Failed to send OTP.', ['error' => $responseBody->message], 400);
            }
        } catch (\Exception $e) {
            return $this->sendError('Error', ['error' => $e->getMessage()]);
        }
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