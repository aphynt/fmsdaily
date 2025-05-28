<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentOperator extends Model
{
    //
    protected $connection = 'assignment';
    protected $table = 'REF_OPERATOR';

    protected $guarded = [];
}
