<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cabeza extends Model
{
    protected $table = 'cabeza';
    protected $fillable=['oodd_id','eess_id','actioodd_id','oocc_id','actioocc_id','cerrado','imagen'];
    public $timestamps = false;
}
