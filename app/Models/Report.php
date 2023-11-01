<?php

namespace App\Models;

use App\Domain\Status\Status;
use App\Domain\Type\ReportType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'mine_id',
      'type',
      'status',
      'created_by',
    ];

    protected $casts = [
        'type' => ReportType::class,
        'status' => Status::class
    ];

    public function criteriaReports(): HasMany
    {
        return $this->hasMany(CriteriaReport::class);
    }

    public function mine(): BelongsTo
    {
        return $this->belongsTo(Mine::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
