<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    use HasFactory;

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function criteriaReports(): HasMany
    {
        return $this->hasMany(CriteriaReport::class);
    }
}
