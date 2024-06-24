<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subject',
        'description',
        'content',
        'project_id',
        'status',
        'type',
        'user_id',
        'assigned_to',
        'completed_at',
    ];

    /**
     * Get the project that owns the ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the assigned users for the ticket.
     *
     * @return array
     */
    public function getAssignedToAttribute()
    {
        return json_decode($this->assigned_to, true);
    }

    /**
     * Set the assigned users for the ticket.
     *
     * @param array $value
     * @return void
     */
    public function setAssignedToAttribute(array $value)
    {
        $this->attributes['assigned_to'] = json_encode($value);
    }

    /**
     * Get the status for the ticket.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Set the status for the ticket.
     *
     * @param string $value
     * @return void
     */
    public function setStatusAttribute(string $value)
    {
        $this->attributes['status'] = strtolower($value);
    }
}
