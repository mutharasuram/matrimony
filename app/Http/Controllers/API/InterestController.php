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
            $validatedData = $request->validate([
                'sender_id' =>'required', 
                'receiver_id' =>'required',
                'status'=>'required', 
                'message' => 'nullable'
            ]);
            $status=$validatedData['status'];
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
