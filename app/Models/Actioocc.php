<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Actioocc extends Model
{
    protected $table = 'actioocc';
    protected $fillable=['oocc_id','fondo','codigo','actividad','prioridad'];
    public $timestamps = false;
}