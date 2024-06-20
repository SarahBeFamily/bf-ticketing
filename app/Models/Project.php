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
        'user_id',
        'status',
        'assigned_to',
    ];

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
     * Get the assigned users for the project.
     *
     * @return array
     */
    public function getAssignedToAttribute()
    {
        return json_decode($this->assigned_to, true);
    }

    /**
     * Set the assigned users for the project.
     *
     * @param array $value
     * @return void
     */
    public function setAssignedToAttribute($value)
    {
        $this->attributes['assigned_to'] = json_encode($value);
    }

    /**
     * Get the status for the project.
     *
     * @return array
     */
    public function getStatusAttribute()
    {
        return json_decode($this->status, true);
    }
}
