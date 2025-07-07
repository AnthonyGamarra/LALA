<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Oodd extends Model
{
    protected $table = 'oodd';
    protected $fillable=['numera','oodd'];
    public $timestamps = false;
}