<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Actioodd extends Model
{
    protected $table = 'actioodd';
    protected $fillable=['actividad','prioridad'];
    public $timestamps = false;
}