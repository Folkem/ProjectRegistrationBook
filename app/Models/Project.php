<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'registered_at' => 'datetime',
        'defended_at' => 'datetime',
    ];

    public function projectType(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class);
    }
    
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }
    
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
