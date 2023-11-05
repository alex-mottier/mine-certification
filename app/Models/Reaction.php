<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
      'comment',
      'criteria_report_id',
      'user_id',
      'status'
    ];

    public function criteriaReport(): BelongsTo
    {
        return $this->belongsTo(CriteriaReport::class);
    }

    public function certifier(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
