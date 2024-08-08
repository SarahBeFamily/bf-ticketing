<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Ticket;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'division',
        'company_id',
        'status',
        'assigned_to',
        'started_at',
        'deadline',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'assigned_to' => 'array',
        'status' => 'array',
    ];

    /**
     * Access to the customers user lists.
     * 
     * @return array
     */
    public function customers()
    {
        // Access user roles
        
        $customers = User::role('customer')->get();
        return $customers;
    }

    /**
     * Access to the team user lists.
     * 
     * @return array
     */
    public function team_members()
    {
        $team = User::role('team')->get();
        return $team;
    }

    /**
     * Get the customer that owns the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tickets for the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    /**
     * Get query for filtering the projects.
     * 
     * @param mixed $query
     * @param array $filters
     * @return mixed
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

        return $query;
    }
}
