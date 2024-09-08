<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
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
    
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('auth_token')->plainTextToken;
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User register successfully.');
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('auth_token')->plainTextToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }



    public function sendSms()
    {
        $apiKey = '8DUyL51bGqH30aTO26Kki9fVBdIgtMSCJFzXmQA4lRusrn7pjPZnUhbCSBa52OykEeN4pqtfVjTFYx76';
        $url = 'https://www.fast2sms.com/dev/bulkV2';

        // Define the request payload
        $fields = [
            'variables_values' => '5599',
            'route' => 'otp',
            'numbers' => '7708468980',
        ];

        // Make the POST request
        $response = Http::withHeaders([
            'authorization' => $apiKey,
            'accept' => '*/*',
            'cache-control' => 'no-cache',
            'content-type' => 'application/json',
        ])->post($url, $fields);


        return $response->body();
    }
}