<?php

namespace App\Models;

use App\Domain\Mine\MineType;
use App\Domain\Report\ReportType;
use App\Domain\Status\Status;
use App\Domain\Trait\HasCoordinates;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mine extends Model
{
    use HasFactory;
    use HasCoordinates;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'tax_number',
        'longitude',
        'latitude',
        'status',
        'type',
    ];

    protected $casts = [
        'status' => Status::class,
        'type' => MineType::class
    ];
    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class)->withTimestamps();
    }

    public function certifiers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            null,
            null,
            'certifier_id'
        )->withTimestamps();
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class)->where('type',ReportType::REPORT->value);
    }

    public function isValidated(): bool
    {
        return $this->status === Status::VALIDATED;
    }

    public function scopeValidated(Builder $query): void
    {
        $query->where('status',Status::VALIDATED->value);
    }

    public function evaluation(): HasOne
    {
        return $this->hasOne(Report::class)->where('type', ReportType::EVALUATION->value);
    }
}
