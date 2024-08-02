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
     * @param string $value
     * @return void
     */
    public function setAssignedToAttribute(string $value)
    {
        $value = explode(',', $value);
        $this->attributes['assigned_to'] = json_encode($value);
    }

    /**
     * Get the status for the ticket.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        return ucfirst($this->attributes['status']);
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

    /**
     * Set the division for the ticket, heritable from the project.
     * 
     * @param string $value
     * @return void
     */
    public function setDivisionAttribute(string $value)
    {
        $this->attributes['division'] = $this->project->division;
    }

    /**
     * Get the division for the ticket.
     * 
     * @return string
     */
    public function getDivisionAttribute()
    {
        return $this->project->division;
    }
    

    /**
     * Get query for filtering the projects.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['status'] ?? false, function ($query, $status) {
            $query->where('status', $status);
        });

        $query->when($filters['division'] ?? false, function ($query, $division) {
            $query->where('division', $division);
        });

        $query->when($filters['user_id'] ?? false, function ($query, $user_id) {
            $query->where('user_id', $user_id);
        });

        $query->when($filters['assigned_to'] ?? false, function ($query, $assigned_to) {
            $query->where('assigned_to', $assigned_to);
        });

        $query->when($filters['project_id'] ?? false, function ($query, $project_id) {
            $query->where('project_id', $project_id);
        });

        return $query;
    }

    /**
     * Get the comments for the ticket.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the comments for the ticket.
     * 
     * @return array
     */
    public function getComments() {
        return $this->comments()->get();
    }

    /**
     * Get the attachments for the ticket.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Get the attachments for the ticket.
     * 
     * @return array
     */
    public function getAttachments() {
        return $this->attachments()->get();
    }
}
