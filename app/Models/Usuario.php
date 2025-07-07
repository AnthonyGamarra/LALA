<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    protected $fillable=['tipo','numera','cargo','usuario','clave','redirige'];
    public $timestamps = false;
}
