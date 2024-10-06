<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'm_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function isMobileExist($number)
    {
        return self::where('mobile', $number)->exists();
    }

    public static function isEmailExist($email)
    {
        return self::where('email', $email)->exists();
    }

    public static function generateUniqueMId()
    {
        do {
            $timestamp = (int)(microtime(true));
            $m_id = 'mv' . $timestamp . mt_rand(1000, 99999);
        } while (self::where('m_id', $m_id)->exists());

        return $m_id;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
