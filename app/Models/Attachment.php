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
        'company'
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
        return asset('storage/'.$value);
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

    /**
     * Get the mime type of the attachment.
     *
     * @param string $value
     * @return string
     */
    public function getMimeTypeAttribute($value)
    {
        return $value;
    }

    /**
     * Get the filename of the attachment.
     *
     * @param string $value
     * @return string
     */
    public function getFilenameAttribute($value)
    {
        return $value;
    }

    /**
     * Get the company related to the attachment (attribute).
     * 
     * @param string $type
     * @return mixed
     */
    public function getCompanyAttribute($type)
    {
        $company = Company::find($this->company);
        
        if (!$company) {
            return null;
        }

        switch ($type) {
            case 'name':
                return $company->name;
            case 'id':
                return $company->id;
            default:
                return $company;
        }
    }

    /**
     * Set the company related to the attachment (attribute).
     * 
     * @param int $company_id
     * @return void
     */
    public function setCompanyAttribute($company_id)
    {
        $this->company = $company_id;
    }
}
