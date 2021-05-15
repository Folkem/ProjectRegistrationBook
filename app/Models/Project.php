<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected array $guarded = [];

    public function projectType(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class);
    }
}
