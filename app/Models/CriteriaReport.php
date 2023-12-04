<?php

namespace App\Models;

use App\Domain\Status\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CriteriaReport extends Model
{
    use HasFactory;

    protected $table = 'criteria_report';

    protected $fillable = [
      'criteria_id',
      'report_id',
      'comment',
      'score',
      'status'
    ];

    protected $casts = [
        'status' => Status::class
    ];

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
