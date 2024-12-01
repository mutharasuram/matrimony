<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\InterestService;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    protected $interestService;

    public function __construct(InterestService $interestService)
    {
        $this->interestService = $interestService;
    }
}
