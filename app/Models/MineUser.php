<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MineUser extends Model
{
    protected $table = 'mine_user';

    protected $fillable = [
        'mine_id',
        'user_id'
    ];
}
