<?php

namespace App\Models;

use App\Domain\Status\Status;
use App\Domain\Trait\HasCoordinates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mine extends Model
{
    use HasFactory;
    use HasCoordinates;
    use SoftDeletes;

    protected $casts = [
        'status' => Status::class
    ];
    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class);
    }

    public function certifiers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, null,null,'certifier_id');
    }
}
