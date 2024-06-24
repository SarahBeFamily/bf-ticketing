<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'user_id',
        'ticket_id',
    ];

    /**
     * Get the user that owns the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ticket that owns the comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the content of the comment.
     *
     * @param string $value
     * @return string
     */
    public function getContentAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Set the content of the comment.
     *
     * @param string $value
     * @return void
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = strtolower($value);
    }
}
