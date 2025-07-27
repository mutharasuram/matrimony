<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Occupation;

class OccupationController extends BaseController
{
    // Get all occupations
    public function index()
    {
        $occupations = Occupation::select('id', 'category', 'occupation_name')->get();

        return $this->sendResponse($occupations, 'All occupations retrieved successfully.');
    }

    // Get occupations by category
    public function getByCategory($category)
    {
        $occupations = Occupation::where('category', $category)
            ->select('id', 'category', 'occupation_name')
            ->get();

        if ($occupations->isEmpty()) {
            return $this->sendError('No occupations found for this category.');
        }

        return $this->sendResponse($occupations, 'Occupations retrieved successfully for category: ' . $category);
    }
}
