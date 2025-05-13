<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FLTShift extends Model
{
    //
    protected $connection = 'focus';
    protected $table = 'FLT_SHIFT';

    protected $guarded = [];
}
