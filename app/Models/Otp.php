<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;
    
    protected $table = 'otps';

    // If you want to allow mass assignment, specify the fillable attributes
    protected $fillable = [
        'mobile',
        'otp',
    ];

}
