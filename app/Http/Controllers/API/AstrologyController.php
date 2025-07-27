<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\AstrologyDetail;

class AstrologyController extends BaseController
{
    public function getRasi()
    {
        $rasi = AstrologyDetail::where('type', 'rasi')->pluck('name');
        if ($rasi->isEmpty()) {
            return $this->sendError('No Rasi records found.');
        }
        return $this->sendResponse($rasi, 'Rasi list retrieved successfully.');
    }

    public function getStar()
    {
        $stars = AstrologyDetail::where('type', 'star')->pluck('name');
        if ($stars->isEmpty()) {
            return $this->sendError('No Star records found.');
        }
        return $this->sendResponse($stars, 'Star list retrieved successfully.');
    }
}
