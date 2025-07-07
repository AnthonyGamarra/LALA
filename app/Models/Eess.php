<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Eess extends Model
{
    protected $table = 'eess';
    protected $fillable=['id_oodd','codigo','eess'];
    public $timestamps = false;
}
