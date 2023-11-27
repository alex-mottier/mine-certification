<?php

namespace App\Models;

use App\Domain\Institution\InstitutionType;
use App\Domain\Status\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'status',
        'validated_by',
        'validated_at'
    ];

    protected $casts = [
        'status' => Status::class,
        'type' => InstitutionType::class
    ];

    public function mines(): BelongsToMany
    {
        return $this->belongsToMany(Mine::class)->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
