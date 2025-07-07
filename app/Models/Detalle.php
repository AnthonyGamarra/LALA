<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $table = 'detalle';
    protected $fillable=['cabeza_id','financia_id','pofi_id','tipo','estimacion','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre','total2026','proy2027','proy2028','proy2029'];
    public $timestamps = false;
}
