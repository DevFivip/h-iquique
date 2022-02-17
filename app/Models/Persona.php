<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    public $fillable = [
        "nombres",
        "apellidos",
        "documento",
        "sexo",
        "fecha_nacimiento",
        "fecha_recepcion_muestra",
        "_token",
        "origen",
        "status",
    ];
}
