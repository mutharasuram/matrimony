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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'img_path',
    ];

    /**
     * Define the relationship to the Profile model.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'profile_img_id', 'id');
    }
}
