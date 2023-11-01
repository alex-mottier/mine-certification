<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'criteria_report_id',
        'filename',
        'path'
    ];

    public function criteriaReport(): BelongsTo
    {
        return $this->belongsTo(CriteriaReport::class);
    }
}
