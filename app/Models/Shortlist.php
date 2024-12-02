<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortlist extends Model
{
    use HasFactory;

    protected $table = 'shortlists';

    protected $fillable = [
        'user_id',
        'shorted_id',
    ];

    /**
     * Get the user who created the shortlist.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who is shortlisted.
     */
    public function shortlistedUser()
    {
        return $this->belongsTo(User::class, 'shorted_id');
    }

    /**
     * Get the users that the current user has shortlisted.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function iShortlisted(int $userId)
    {
        return self::where('user_id', $userId)->with('shortlistedUser','shortlistedUser.profile', 'shortlistedUser.profile.images')->get();
    }

    /**
     * Get the users who have shortlisted the current user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function whoShortlistedMe(int $userId)
    {
        return self::where('shorted_id', $userId)->with('user','user.profile', 'user.profile.images')->get();
    }
}
