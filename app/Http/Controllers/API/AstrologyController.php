<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\AstrologyDetail;

class AstrologyController extends BaseController
{
    public function getRasi()
    {
        $rasi = AstrologyDetail::where('type', 'rasi')->pluck('name');
        $stars = AstrologyDetail::where('type', 'star')->pluck('name');
        $data = [
        'rasi' => $rasi,
        'star' => $stars
        ];
        return $this->sendResponse($data, 'Rasi & star list retrieved successfully.');
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
