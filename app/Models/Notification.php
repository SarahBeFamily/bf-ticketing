<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'notifiable_id',
        'notifiable_type',
        'data',
        'read_at',
    ];

    /**
     * Get the notifiable entity that the notification belongs to.
     *
     * @return void
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that the notification belongs to.
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead()
    {
        $this->read_at = now();
        $this->status = 'read';
        $this->save();
    }

    /**
     * Mark the notification as unread.
     *
     * @return void
     */
    public function markAsUnread()
    {
        $this->read_at = null;
        $this->status = 'unread';
        $this->save();
    }

    /**
     * Determine if the notification is read.
     *
     * @return boolean
     */
    public function isRead()
    {
        return $this->read_at !== null;
    }

    /**
     * Determine if the notification is unread.
     *
     * @return boolean
     */
    public function isUnread()
    {
        return $this->read_at === null;
    }

    /**
     * Scope a query to only include read notifications.
     *
     * @param [type] $query
     * @return void
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope a query to only include unread notifications.
     *
     * @param [type] $query
     * @return void
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include notifications for the given user.
     *
     * @param [type] $query
     * @param User $user
     * @return void
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope a query to only include notifications of a given type.
     *
     * @param [type] $query
     * @param string $type
     * @return void
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include notifications for the given notifiable.
     *
     * @param [type] $query
     * @param Model $notifiable
     * @return void
     */
    public function scopeNotifiable($query, Model $notifiable)
    {
        return $query->where('notifiable_id', $notifiable->id)
            ->where('notifiable_type', $notifiable->getMorphClass());
    }

    /**
     * Create a new notification for the given user.
     *
     * @param User $user
     * @param string $type
     * @param Model $notifiable
     * @param array $data
     * @return void
     */
    public static function createForUser(User $user, string $type, Model $notifiable, array $data = [])
    {
        return $user->notifications()->create([
            'type' => $type,
            'id' => $notifiable->id,
            'notifiable' => $notifiable->getMorphClass(),
            'data' => $data,
        ]);
    }

    /**
     * Create a new notification for the given users.
     *
     * @param array $users
     * @param string $type
     * @param Model $notifiable
     * @param array $data
     * @return void
     */
    public static function createForUsers(array $users, string $type, Model $notifiable, array $data = [])
    {
        foreach ($users as $user) {
            self::createForUser($user, $type, $notifiable, $data);
        }
    }

    /**
     * Mark all notifications as read.
     *
     * @return void
     */
    public function markAllAsRead()
    {
        $this->unreadNotifications->markAsRead();
    }

    /**
     * Mark all notifications as unread.
     *
     * @return void
     */
    public function markAllAsUnread()
    {
        $this->readNotifications->markAsUnread();
    }

    /**
     * Set unread notifications for the user.
     * 
     * @return void
     */
    public function unreadNotifications($user)
    {
        return $this->notifications()->where('user_id', $user->id)->whereNull('read_at')->get();
    }

}
