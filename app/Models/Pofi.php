<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pofi extends Model
{
    protected $table = 'pofi';
    protected $fillable=['color','codigo','pofi','fonafe1','fonafe2','fonafe3','mef1','mef2','mef3'];
    public $timestamps = false;
}
