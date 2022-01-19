<?php

namespace Fshangala\Auth2Ation\Models;

use Illuminate\Database\Eloquent\Model;

class Authorization extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'resource',
        'type',
        'target',
        'grant'
    ];
}