<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileImg extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profile_img';

    /**
     * Define the relationship to the Profile model.
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }
}
