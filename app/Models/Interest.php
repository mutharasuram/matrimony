<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
        'message',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    /**
     * Get the users that the current user has shortlisted.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function iInterestedlisted(int $userId)
    {
        return self::where('sender_id', $userId)->with('receiver','receiver.profile', 'receiver.profile.images')->get();
    }

    /**
     * Get the users who have shortlisted the current user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function whoInterestedlistedMe(int $userId)
    {
        return self::where('receiver_id', $userId)->with('sender','sender.profile', 'sender.profile.images')->get();
    }
}
