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
                'id' => 'required|integer|min:1',
                'search' => 'nullable|string|max:255'
            ]);

            $type = $validatedData['type'];
            $userId = $validatedData['id'];
            
            // Get pagination parameters
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            
            // Validate pagination parameters
            $perPage = max(1, min(100, (int)$perPage)); // Limit between 1-100
            $page = max(1, (int)$page);

            // Get search parameter
            $searchParams = [
                'search' => $request->get('search')
            ];

            switch ($type) {
                case 'just_joined':
                    $matches = $this->matchesService->getJustJoined($userId, $perPage, $page, $searchParams);
                    break;
                case 'matches':
                    $matches = $this->matchesService->getMatches($userId, $perPage, $page, $searchParams);
                    break;
                case 'nearby':
                    $matches = $this->matchesService->getNearBy($userId, $perPage, $page, $searchParams);
                    break;
                case 'shortlisted':
                    $matches = $this->matchesService->getShortlisted($userId, $perPage, $page, $searchParams);
                    break;
                case 'shortlisted_by':
                    $matches = $this->matchesService->getShortlistedBy($userId, $perPage, $page, $searchParams);
                    break;
                case 'interested':
                    $matches = $this->matchesService->getInterested($userId, $perPage, $page, $searchParams);
                    break; 
                case 'interested_by':
                    $matches = $this->matchesService->getInterestedBy($userId, $perPage, $page, $searchParams);
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

    /**
     * Get home profiles - random 10 profiles of opposite gender
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function home(Request $request)
    {
        try {
            // Validate required parameters
            $validatedData = $request->validate([
                'id' => 'required|integer|min:1',
                'search' => 'nullable|string|max:255'
            ]);

            $userId = $validatedData['id'];

            // Get search parameter
            $searchParams = [
                'search' => $request->get('search')
            ];

            $profiles = $this->matchesService->getHomeProfiles($userId, $searchParams);

            return $this->sendResponse($profiles, 'Home profiles retrieved successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError('Validation Error', $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError('Error retrieving home profiles.', ['error' => $e->getMessage()], 500);
        }
    }
}
