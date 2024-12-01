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
        $type = $request->type;
        $userId = $request->id;
        switch ($type) {
            case 'just_joined':
                $matches = $this->matchesService->getJustJoined($userId);
                return $this->sendResponse($matches, 'Matches retrieved successfully.');
                break;
            case 'matches':
                $matches = $this->matchesService->getMatches($userId);
                return $this->sendResponse($matches, 'Matches retrieved successfully.');
                break;
            case 'nearby':
                $matches = $this->matchesService->getNearBy($userId);
                return $this->sendResponse($matches, 'Matches retrieved successfully.');
                break;
            case 'shortedlist':
                $matches = $this->matchesService->getshortedlist($userId);
                return $this->sendResponse($matches, 'Matches retrieved successfully.');
                break;
            case 'shortedby':
                $matches = $this->matchesService->getshortedby($userId);
                return $this->sendResponse($matches, 'Matches retrieved successfully.');
                break;    
            default:
                return $this->sendError('Type not found.', array(), 404);
                break;
        }
    }
}
