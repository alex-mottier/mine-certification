<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    public function criteriaReports(): HasMany
    {
        return $this->hasMany(CriteriaReport::class);
    }

    public function mine(): BelongsTo
    {
        return $this->belongsTo(Mine::class);
    }
}
