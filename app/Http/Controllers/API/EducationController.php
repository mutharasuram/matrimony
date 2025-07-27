<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Education;
use Illuminate\Http\Request;


class EducationController extends BaseController
{
    // Get all education degrees
    public function index()
    {
        $educations = Education::select('id', 'category', 'degree_name')->get();

        return $this->sendResponse($educations, 'All educational qualifications retrieved successfully.');
    }

    // Get degrees by category
    public function getByCategoryQuery(Request $request)
    {
        $category = $request->query('category');

        if (!$category) {
            return $this->sendError('Category is required.');
        }

        $educations = Education::where('category', $category)
            ->select('id', 'category', 'degree_name')
            ->get();

        if ($educations->isEmpty()) {
            return $this->sendError('No degrees found for the given category.');
        }

        return $this->sendResponse($educations, 'Degrees retrieved successfully.');
    }

    public function getCategories()
    {
        $categories = Education::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category'); // returns as array of values

        if ($categories->isEmpty()) {
            return $this->sendError('No education categories found.');
        }

        return $this->sendResponse($categories, 'Education categories retrieved successfully.');
    }
}
