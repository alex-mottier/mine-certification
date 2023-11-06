<?php

namespace App\Models;

use App\Domain\Status\Status;
use App\Domain\Trait\HasCoordinates;
use App\Domain\User\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;
    use HasCoordinates;
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'fullname',
        'email',
        'password',
        'type',
        'created_by',
        'validated_by',
        'validated_at',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'type' => UserType::class,
        'status' => Status::class
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function isAdmin(): bool
    {
        return $this->type === UserType::ADMINISTRATOR;
    }

    public function isValidated(): bool
    {
        return $this->status === Status::VALIDATED;
    }

    public function mines(): BelongsToMany
    {
        return $this->belongsToMany(Mine::class, null, 'certifier_id');
    }

    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class);
    }

    public function scopeInArea(Builder $query, float $longitude, float $latitude, float $radius): void
    {
        $query->selectRaw(
            '*, (? * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance',
            [
                self::EARTH_RADIUS_KM,
                $latitude,
                $longitude,
                $latitude
            ]
        )
            ->having('distance', '<', $radius);
    }

    public function scopeIsAdmin(Builder $query): void
    {
        $query->where('type', UserType::ADMINISTRATOR->value);
    }

    public function isCertifier(): bool
    {
        return $this->type === UserType::CERTIFIER;
    }

    public function hasMine(int $mineId): bool
    {
        foreach ($this->mines()->get() as $mine){
            if($mine->id == $mineId){
                return true;

            }
        }
        return false;
    }
}
