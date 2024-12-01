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
        switch ($type) {
            case 'just_joined':
                $matches = $this->matchesService->getJustJoined();
                return $this->sendResponse($matches, 'Matches retrieved successfully.');
                break;
            default:
                return $this->sendError('Type not found.', array(), 404);
                break;
        }
    }
}
