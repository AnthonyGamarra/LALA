<?php

namespace App\Models;

class CabezaExcelData
{
    public $idredx;
    public $redx;
    public $idesx;
    public $codesx;
    public $esx;
    public $idactiodx;
    public $nameactiodx;
    public $prioactiodx;
    public $cerradox;
    public $headerx;

    public function __construct()
    {
        $this->idredx = session('idredx');
        $this->redx = session('redx');
        $this->idesx = session('idesx');
        $this->codesx = session('codesx');
        $this->esx = session('esx');
        $this->idactiodx = session('idactiodx');
        $this->nameactiodx = session('nameactiodx');
        $this->prioactiodx = session('prioactiodx');
        $this->cerradox = session('cerradox');
        $this->headerx = session('headerx');
    }
}
