<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;
use App\Models\User;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'workers',
    ];

    /**
     * Get the projects related to the company.
     * 
     * @return void
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Set the workers related to the company.
     * 
     * @param array $workers
     * @return void
     */
    public function setWorkersAttribute($workers)
    {
        $this->attributes['workers'] = $workers;
    }

    /**
     * Get the logo of the company.
     * 
     * @param string $value
     * @return void
     */
    public function getLogoAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    /**
     * Set the logo of the company.
     * 
     * @param string $value
     * @return void
     */
    public function setLogoAttribute($value)
    {
        $this->attributes['logo'] = $value ?? null;
    }

    /**
     * Delete the company's logo.
     * 
     * @return void
     */
    public function deleteLogo()
    {
        // Delete the file from the storage
        Storage::delete($this->logo);
        $this->logo = null;

        $this->save();
    }

    /**
     * Remove the specified company from storage.
     * 
     * @return void
     */
    public function deleteCompany()
    {
        // controllo se la collezione ha logo
        if ($this->logo != null) {
            // Delete the file from the storage
            Storage::delete($this->logo);
        }

        if ($this->workers != null) {
            // Rimuovo l'azienda ai dipendenti
            $workers = json_decode($this->workers);
            foreach ($workers as $worker) {
                $user = User::find($worker);
                $user->company = null;
                $user->save();
            }
        }

        $this->delete();
    }
}
