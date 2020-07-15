<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        "rut","nombre","telefono","email"
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];
}
