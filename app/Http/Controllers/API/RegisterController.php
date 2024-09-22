<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'mobile' => 'required|string|max:15',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $mId = User::generateUniqueMId();
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['m_id'] = $mId;
            $user = User::create($input);
            $success['token'] =  $user->createToken('auth_token')->plainTextToken;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User register successfully.');
        } catch (\Exception $e) {
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
        if (Auth::attempt(['email' => $request->value, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('auth_token')->plainTextToken;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        } else if (Auth::attempt(['m_id' => $request->value, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('auth_token')->plainTextToken;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        } else if (Auth::attempt(['mobile' => $request->value, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('auth_token')->plainTextToken;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }



    public function sendSms(Request $request)
    {
        try {
            $apiKey = env('FAST2SMS_API_KEY');
            $url = env('FAST2SMS_URL');
            $otp = rand(1000, 9999);
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
                'otp' => 'required|digits:4',
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
}
