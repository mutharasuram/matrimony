<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\InterestService;
use Illuminate\Contracts\Validation\ValidationRule;

class InterestController extends BaseController
{
    protected $interestservice;

    /**
     * MatchesController constructor.
     *
     * @param MatchesService $matchesService
     */
    public function __construct(InterestService $interestservice)
    {
        $this->interestservice = $interestservice;
    }
    public function store(Request $request){
        try {
            // Get parameters from request - handle JSON, form data, and raw body
            $sender_id = $request->get('sender_id');
            $receiver_id = $request->get('receiver_id');
            $status = $request->get('status');
            $message = $request->get('message');
            
            // If not found, try JSON data
            if (!$sender_id || !$receiver_id || !$status) {
                $jsonData = $request->json() ? $request->json()->all() : [];
                $sender_id = $sender_id ?: ($jsonData['sender_id'] ?? null);
                $receiver_id = $receiver_id ?: ($jsonData['receiver_id'] ?? null);
                $status = $status ?: ($jsonData['status'] ?? null);
                $message = $message ?: ($jsonData['message'] ?? null);
            }
            
            // If still not found, try parsing raw body as JSON
            if (!$sender_id || !$receiver_id || !$status) {
                $rawBody = $request->getContent();
                if ($rawBody) {
                    $rawData = json_decode($rawBody, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($rawData)) {
                        $sender_id = $sender_id ?: ($rawData['sender_id'] ?? null);
                        $receiver_id = $receiver_id ?: ($rawData['receiver_id'] ?? null);
                        $status = $status ?: ($rawData['status'] ?? null);
                        $message = $message ?: ($rawData['message'] ?? null);
                    }
                }
            }
            
            // Validate required parameters
            if (!$sender_id || !$receiver_id || !$status) {
                return $this->sendError('Missing required parameters.', [
                    'sender_id' => $sender_id,
                    'receiver_id' => $receiver_id,
                    'status' => $status
                ], 400);
            }
            
            // Validate status value
            if (!in_array($status, ['pending', 'accepted', 'declined', 'replied'])) {
                return $this->sendError('Invalid status value.', [
                    'status' => $status,
                    'valid_statuses' => ['pending', 'accepted', 'declined', 'replied']
                ], 400);
            }
            
            // Check if users exist
            if (!\App\Models\User::where('id', $sender_id)->exists()) {
                return $this->sendError('Sender not found.', ['sender_id' => $sender_id], 404);
            }
            
            if (!\App\Models\User::where('id', $receiver_id)->exists()) {
                return $this->sendError('Receiver not found.', ['receiver_id' => $receiver_id], 404);
            }
            
            if ($sender_id == $receiver_id) {
                return $this->sendError('Cannot send interest to yourself.', [], 400);
            }
            
            $validatedData = [
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'status' => $status,
                'message' => $message
            ];
            switch($status){
                case 'pending':
                    $interest = $this->interestservice->storeIntrest($validatedData);
                    return $this->sendResponse($interest['response'], $interest['message']);
                    break;
                case 'accepted':
                    $interest = $this->interestservice->acceptedIntrest($validatedData);
                    return $this->sendResponse($interest['response'], $interest['message']);
                    break;  
                case 'declined':
                    $interest = $this->interestservice->declinedIntrest($validatedData);
                    return $this->sendResponse($interest['response'], $interest['message']);
                    break;
                case 'replied':
                    $interest = $this->interestservice->repliedIntrest($validatedData);
                    return $this->sendResponse($interest['response'], $interest['message']);
                    break; 
                default:
                    return $this->sendError('Type not found.', array(), 404);
                    break;           
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Error processing request.', ['error' => $e->getMessage()], 500);
        }
    }
}
