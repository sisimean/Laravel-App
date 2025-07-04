<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $table = 'registers';

    protected $primaryKey = 'register_id';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
