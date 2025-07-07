<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Financia extends Model
{
    protected $table = 'financia';
    protected $fillable=['codigo','fondo'];
    public $timestamps = false;
}