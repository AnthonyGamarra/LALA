<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Oocc extends Model
{
    protected $table = 'oocc';
    protected $fillable=['numera','oficina'];
    public $timestamps = false;
}