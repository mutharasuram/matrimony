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
        'horoscope_chart_style'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function profileImg()
    {
        return $this->belongsTo(ProfileImg::class, 'profile_img_id', 'id');
    }
}
