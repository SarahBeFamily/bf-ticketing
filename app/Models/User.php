<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Notification;


class User extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'company',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * User avatar
     */
    public function avatar()
    {
        // If user is team member
        if ($this->hasRole('team')) {
            return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?s=80';
        }
    }

    /**
     * Get customers
     * 
     * @return void
     */
    public function customers()
    {
        return $this->role('customer')->get();
    }

    /**
     * Get notifications attribute
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get unread notifications
     * 
     * @return void
     */
    public function getUnreadNotification()
    {
        return Notification::where('user_id', $this->id)->where('status', 'unread')->get();
    }

    /**
     * Get all notifications
     * 
     * @return void
     */
    public function getNotifications()
    {
        $userNotifications = Notification::where('user_id', $this->id)->get();
        return $userNotifications;
    }

    /**
     * Get a specific notification
     * 
     * @return void
     */
    public function getNotification($notificationId)
    {
        $notification = Notification::where('user_id', $this->id)->where('id', $notificationId)->first();
        return $notification;
    }
}
