<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profile';

    protected $fillable = [
        'user_id',
        'profile_created_by',
        'gender',
        'name',
        'dob',
        'mother_tongue',
        'subcaste',
        'sub_caste_details',
        'willing_to_marry_from_subcaste',
        'marital_status',
        'country_living_in',
        'residing_state',
        'residing_city',
        'citizenship',
        'height',
        'education',
        'employed_in',
        'occupation',
        'annual_income',
        'physical_status',
        'family_status',
        'family_type',
        'about_me',
        'dosham',
        'star_nakshatram',
        'rasi',
        'gothram',
        'time_of_birth',
        'country_of_birth',
        'state_of_birth',
        'city_of_birth',
        'horoscope_chart_style',
        // Family Details Fields
        'father_occupation',
        'mother_occupation',
        'no_of_brothers',
        'no_of_sisters',
        // Partner Preference Fields
        'preferred_age_min',
        'preferred_age_max',
        'preferred_height_min',
        'preferred_height_max',
        'preferred_marital_status',
        'preferred_physical_status',
        'preferred_mother_tongue',
        'preferred_subcaste',
        'preferred_chevvai_dosham',
        'preferred_education',
        'preferred_employed_in',
        'preferred_occupation',
        'preferred_annual_income_min',
        'preferred_annual_income_max',
        'preferred_country',
        'preferred_citizenship',
        'eating_habit',
        'drinking_habit',
        'smoking_habit',
        'hobbies_and_interests',
        'music',
        'sports',
        'food'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function images()
    {
        return $this->hasMany(ProfileImg::class, 'profile_id');
    }
}
