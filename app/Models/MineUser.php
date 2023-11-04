<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MineUser extends Model
{
    use HasFactory;

    protected $table = 'mine_user';

    protected $fillable = [
        'mine_id',
        'certifier_id'
    ];
}
