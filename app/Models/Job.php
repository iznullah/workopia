<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;
    protected $table = 'job_listings';
    protected $fillable = ['user_id', 'title', 'description', 'salary',
        'tags', 'job_type', 'remote',
        'address', 'city', 'state', 'zip-code', 'contact-email',
        'contact-phone', 'company-name', 'company-description',
        'company-logo', 'company-website'];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
