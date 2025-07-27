<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
  
class LocationController extends BaseController{
   public function getCountries()
    {
        $countries = Country::select('id as country_id', 'name')->get();

        return $this->sendResponse($countries, 'Country list retrieved successfully.');
    }

    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)
            ->select('id as state_id', 'name')->get();

        if ($states->isEmpty()) {
            return $this->sendError('No states found for the selected country.');
        }

        return $this->sendResponse($states, 'State list retrieved successfully.');
    }

    public function getCities($state_id)
    {
        $cities = City::where('state_id', $state_id)
            ->select('id as city_id', 'name')->get();

        if ($cities->isEmpty()) {
            return $this->sendError('No cities found for the selected state.');
        }

        return $this->sendResponse($cities, 'City list retrieved successfully.');
    }
}
    
    
    