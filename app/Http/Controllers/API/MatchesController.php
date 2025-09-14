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
                'id' => 'required|integer|min:1'
            ]);

            $type = $validatedData['type'];
            $userId = $validatedData['id'];
            
            // Get pagination parameters
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            
            // Validate pagination parameters
            $perPage = max(1, min(100, (int)$perPage)); // Limit between 1-100
            $page = max(1, (int)$page);

            switch ($type) {
                case 'just_joined':
                    $matches = $this->matchesService->getJustJoined($userId, $perPage, $page);
                    break;
                case 'matches':
                    $matches = $this->matchesService->getMatches($userId, $perPage, $page);
                    break;
                case 'nearby':
                    $matches = $this->matchesService->getNearBy($userId, $perPage, $page);
                    break;
                case 'shortlisted':
                    $matches = $this->matchesService->getShortlisted($userId, $perPage, $page);
                    break;
                case 'shortlisted_by':
                    $matches = $this->matchesService->getShortlistedBy($userId, $perPage, $page);
                    break;
                case 'interested':
                    $matches = $this->matchesService->getInterested($userId, $perPage, $page);
                    break; 
                case 'interested_by':
                    $matches = $this->matchesService->getInterestedBy($userId, $perPage, $page);
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
}
