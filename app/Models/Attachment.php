<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'filename',
        'path',
        'mime_type',
        'size',
        'project_id',
        'user_id',
        'ticket_id',
    ];

    /**
     * Get the user that owns the attachment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project that owns the attachment.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the ticket that owns the attachment.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the path to the attachment.
     *
     * @param string $value
     * @return string
     */
    public function getPathAttribute($value)
    {
        return asset($value);
    }

    /**
     * Get the size of the attachment.
     *
     * @param string $value
     * @return string
     */
    public function getSizeAttribute($value)
    {
        return number_format($value / 1024, 2) . ' KB';
    }
}
