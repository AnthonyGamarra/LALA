<?php
namespace App\Http\Controllers;

use App\Models\Cabeza;
use App\Models\Eess;
use App\Models\Oodd;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DetalleExport;
use App\Models\CabezaExcelData;

class CabezaController extends Controller
{
    public function ooddselect(Request $request)
    {
        Session::forget('idesx');
        $eess = DB::table('eess')
                            ->select('id','codigo', 'eess')
                            ->where('oodd_id',session('idredx'))
                            ->get();

                    return view('ooddselect', [
                        'organo' => session('redx'),
                        'eess' => $eess
                    ]);       
    }
    
    public function ooddmain(Request $request)
    {
        if (!session('idesx')) {
            Session::put('idesx', $request->input('ide_eess'));

            $eess = DB::table('eess')
                        ->select('codigo', 'eess')
                        ->where('id', $request->input('ide_eess'))
                        ->limit(1)
                        ->first();

            Session::put('codesx',$eess->codigo);
            Session::put('esx', $eess->eess);           
        }

        //obtener las actividades
        $results = DB::table('actioodd')
                ->join('cabeza', 'actioodd.id', '=', 'cabeza.actioodd_id')
                ->join('eess', 'cabeza.eess_id', '=', 'eess.id')
                ->where('eess_id', session('idesx')) 
                ->orderBy('actioodd.id')
                ->select('cabeza.id as cabeza_id','actioodd.id as actioodd_id','actioodd.actividad','actioodd.prioridad','cabeza.cerrado')
                ->get();

        return view('ooddmain', ['results' => $results]);        
    }

    public function ooddhoja(Request $request)
    {
        if ($request->input('actividadd')!=NULL) {
            Session::put('nameactiodx',$request->input('actividadd')); 
        } 
        if ($request->input('prioridadd')!=NULL) {
            Session::put('prioactiodx',$request->input('prioridadd')); 
        } 
        if ($request->input('cerradd')!=NULL) {
            Session::put('cerradox',$request->input('cerradd')); 
        }

        if ($request->input('cabezaidex')) {
            Session::put('headerx', $request->input('cabezaidex'));
        }

        $detalles = DB::table('detalle as d')
                ->leftJoin('financia as f', 'd.financia_id', '=', 'f.id')
                ->leftJoin('pofi as p', 'd.pofi_id', '=', 'p.id')
                ->select(
                    'd.cabeza_id',
                    'f.id as financia_id',
                    'f.codigo as financia_codigo',
                    'f.fondo',
                    'p.id as pofi_id',
                    'p.codigo as pofi_codigo',
                    'p.pofi',
                    'p.color',
                    'd.tipo',
                    'd.estimacion',
                    'd.enero',
                    'd.febrero',
                    'd.marzo',
                    'd.abril',
                    'd.mayo',
                    'd.junio',
                    'd.julio',
                    'd.agosto',
                    'd.septiembre',
                    'd.octubre',
                    'd.noviembre',
                    'd.diciembre',
                    'd.total2026',
                    'd.proy2027',
                    'd.proy2028',
                    'd.proy2029'
                )
                ->where('d.cabeza_id',session('headerx'))
                ->orderBy('d.pofi_id')
                ->get();

        return view('ooddhoja', compact('detalles'))
            ->with('cabezax',session('headerx'))
            ->with('cerradox', session('cerradox'));
        
    }

    public function cerrarooddhoja(Request $request)
    {
        $cabezaId = $request->input('cabezaidex');

        $cabeza = Cabeza::find($cabezaId);
        if ($cabeza) {
            $cabeza->cerrado = 1;
            $cabeza->save();
        }

        return redirect()->route('ooddmain')->with('success', 'Oodd cerrado exitosamente');
        
    }


    public function grabaooddhoja(Request $request)
    {
        if(session('headerx')){
            $totalFilasGrabar = $request->input('totalFilasGrabar'); 
            $DataGrabar = json_decode($request->input('DataGrabar'), true);
            $pofitemp = null; $fondotemp = null;

            DB::beginTransaction();
            try {
                DB::table('detalle')->where('cabeza_id',session('headerx'))->delete();
                foreach ($DataGrabar as $fila) {

                    if($fila['x']!=null){$fondotemp = DB::table('financia')->where('codigo', $fila['x'])->limit(1)->value('id');}
                    if($fila['y']!=null){$pofitemp = DB::table('pofi')->where('codigo', $fila['y'])->limit(1)->value('id');}
                    DB::table('detalle')->insert([
                        'cabeza_id' => session('headerx'),
                        'financia_id' => $fondotemp,  // Asignar el valor adecuado
                        'pofi_id' => $pofitemp,  // Asignar el valor adecuado
                        'tipo' => $fila['z'], 
                        'estimacion' => $fila['a'],
                        'enero' => $fila['b'],
                        'febrero' => $fila['c'],
                        'marzo' => $fila['d'],
                        'abril' => $fila['e'],
                        'mayo' => $fila['f'],
                        'junio' => $fila['g'],
                        'julio' => $fila['h'],
                        'agosto' => $fila['i'],
                        'septiembre' => $fila['j'],
                        'octubre' => $fila['k'],
                        'noviembre' => $fila['l'],
                        'diciembre' => $fila['m'],
                        'total2026' => $fila['n'],
                        'proy2027' => $fila['o'],
                        'proy2028' => $fila['p'],
                        'proy2029' => $fila['q'],
                    ]);
                    $pofitemp = null; $fondotemp = null;
                }

                DB::commit();
                //Log::info('Datos guardados correctamente', ['Data' => $DataGrabar, 'Headerx' => session('headerx')]);


                return redirect()->route('ooddhoja');  
            } catch (\Exception $r) {
                DB::rollback();
                //\Log::error("Error al insertar los datos: " . $r->getMessage());
                
                return back()->withErrors(['error' => 'Hubo un problema al guardar los datos.']);
            }
        }else{ return redirect()->route('login');}
    }

    public function exportaooddhoja(Request $request)
    {
        // Obtener los detalles de la base de datos
        $detalles = DB::table('detalle as d')
            ->leftJoin('financia as f', 'd.financia_id', '=', 'f.id')
            ->leftJoin('pofi as p', 'd.pofi_id', '=', 'p.id')
            ->select(
                'd.cabeza_id',
                'f.id as financia_id',
                'f.codigo as financia_codigo',
                'f.fondo',
                'p.id as pofi_id',
                'p.codigo as pofi_codigo',
                'p.pofi',
                'p.color',
                'd.tipo',
                'd.estimacion',
                'd.enero',
                'd.febrero',
                'd.marzo',
                'd.abril',
                'd.mayo',
                'd.junio',
                'd.julio',
                'd.agosto',
                'd.septiembre',
                'd.octubre',
                'd.noviembre',
                'd.diciembre',
                'd.total2026',
                'd.proy2027',
                'd.proy2028',
                'd.proy2029'
            )
            ->where('d.cabeza_id', session('headerx'))
            ->orderBy('d.pofi_id')
            ->get();
        
        $archivo1 = session('esx').'-'.session('nameactiodx').'.xlsx';
        return Excel::download(new DetalleExport($detalles,1),$archivo1);//tipo1=consolidado por actividad
    }

    public function consolidaeess()
    {
        $detalles = DB::table('detalle as d')
            ->select([
                DB::raw('MAX(d.cabeza_id) AS cabeza_id'),
                'f.id AS financia_id',
                'f.codigo AS financia_codigo',
                'f.fondo',
                'p.id AS pofi_id',
                'p.codigo AS pofi_codigo',
                'p.pofi',
                'p.color',
                'd.tipo',
                DB::raw('SUM(d.estimacion) AS estimacion'),
                DB::raw('SUM(d.enero) AS enero'),
                DB::raw('SUM(d.febrero) AS febrero'),
                DB::raw('SUM(d.marzo) AS marzo'),
                DB::raw('SUM(d.abril) AS abril'),
                DB::raw('SUM(d.mayo) AS mayo'),
                DB::raw('SUM(d.junio) AS junio'),
                DB::raw('SUM(d.julio) AS julio'),
                DB::raw('SUM(d.agosto) AS agosto'),
                DB::raw('SUM(d.septiembre) AS septiembre'),
                DB::raw('SUM(d.octubre) AS octubre'),
                DB::raw('SUM(d.noviembre) AS noviembre'),
                DB::raw('SUM(d.diciembre) AS diciembre'),
                DB::raw('SUM(d.total2026) AS total2026'),
                DB::raw('SUM(d.proy2027) AS proy2027'),
                DB::raw('SUM(d.proy2028) AS proy2028'),
                DB::raw('SUM(d.proy2029) AS proy2029')
            ])
            ->leftJoin('financia as f', 'd.financia_id', '=', 'f.id')
            ->leftJoin('pofi as p', 'd.pofi_id', '=', 'p.id')
            ->whereIn('d.cabeza_id', function ($query) {
                $query->select('c.id')
                    ->from('cabeza as c')
                    ->join('eess as e', 'c.eess_id', '=', 'e.id')
                    ->where('e.id', '=', session('idesx'));
            })
            ->groupBy('f.id', 'p.id', 'd.tipo', 'f.fondo', 'p.color', 'p.pofi')
            ->orderBy('pofi_id')
            ->get();

            $archivo2 = session('esx').'-ConsolidadoDeEESS.xlsx';
            return Excel::download(new DetalleExport($detalles,2),$archivo2);//tipo2=consolidado de EESS
    }


    public function consolidared()
    {
        $detalles = DB::table('detalle as d')
            ->select([
                DB::raw('MAX(d.cabeza_id) AS cabeza_id'),
                'f.id AS financia_id',
                DB::raw("'' AS tipo"),
                DB::raw("'' AS financia_codigo"),
                DB::raw("'' AS fondo"),
                'p.id AS pofi_id',
                'p.codigo AS pofi_codigo',
                'p.pofi',
                'p.color',
                DB::raw('SUM(d.estimacion) AS estimacion'),
                DB::raw('SUM(d.enero) AS enero'),
                DB::raw('SUM(d.febrero) AS febrero'),
                DB::raw('SUM(d.marzo) AS marzo'),
                DB::raw('SUM(d.abril) AS abril'),
                DB::raw('SUM(d.mayo) AS mayo'),
                DB::raw('SUM(d.junio) AS junio'),
                DB::raw('SUM(d.julio) AS julio'),
                DB::raw('SUM(d.agosto) AS agosto'),
                DB::raw('SUM(d.septiembre) AS septiembre'),
                DB::raw('SUM(d.octubre) AS octubre'),
                DB::raw('SUM(d.noviembre) AS noviembre'),
                DB::raw('SUM(d.diciembre) AS diciembre'),
                DB::raw('SUM(d.total2026) AS total2026'),
                DB::raw('SUM(d.proy2027) AS proy2027'),
                DB::raw('SUM(d.proy2028) AS proy2028'),
                DB::raw('SUM(d.proy2029) AS proy2029')
            ])
            ->leftJoin('financia as f', 'd.financia_id', '=', 'f.id')
            ->leftJoin('pofi as p', 'd.pofi_id', '=', 'p.id')
            ->whereExists(function ($query) {
                $query->select(DB::raw('1'))
                    ->from('cabeza as c')
                    ->join('oodd as o', 'c.oodd_id', '=', 'o.id')
                    ->where('o.id', '=', session('idredx'))
                    ->whereRaw('c.id = d.cabeza_id');
            })
            ->groupBy('f.id', 'p.id', 'p.color', 'p.pofi', DB::raw("''"), DB::raw("''"), DB::raw("''"))
            ->orderBy('pofi_id')
            ->get();

            $archivo3 = session('redx').'-ConsolidadoDeRed.xlsx';
            return Excel::download(new DetalleExport($detalles,3),$archivo3);//tipo3=consolidado de RED

    }

    // MASTER OOCC 
    public function ooccmastermain(Request $request)
    {
        $results  = DB::table('cabeza')
            ->join('oocc', 'cabeza.oocc_id', '=', 'oocc.id')
            ->join('actioocc', 'oocc.id', '=', 'actioocc.oocc_id')
            ->select(
                'cabeza.id as cabeza_id',
                'oocc.id as oocc_id',
                'actioocc.fondo',
                'actioocc.codigo',
                'actioocc.actividad',
                'actioocc.prioridad',
                'actioocc.estado'
            )
            ->get();

        return view('ooccmastermain',compact('results'));          
    }

    public function ooddmastermain(Request $request)
    {
        $results = DB::table('eess')
            ->join('cabeza', 'eess.id', '=', 'cabeza.eess_id')
            ->join('actioodd', 'cabeza.actioodd_id', '=', 'actioodd.id')
            ->select(
                'eess.id as eess_id', 
                'eess.codigo', 
                'eess.eess', 
                'cabeza.id as cabeza_id',
                'cabeza.cerrado',
                'actioodd.actividad as actividades'
            )
            ->where('eess.oodd_id', $request->input('ide_organo'))  // Puedes pasar dinámicamente el valor
            ->groupBy('eess.id', 'eess.codigo', 'eess.eess', 'cabeza.id', 'actioodd.actividad')
            ->orderBy('eess.id')
            ->orderBy('cabeza.id')
            ->get();
        
        return view('ooddmastermain',compact('results'));  
    }

    public function ooddmasterrestaura(Request $request)
    {
        $idexr = $request->input('idexr');
        $result = DB::select("SELECT etapa1_alimentar_restaurarhoja_oodd_valores(?)", [$idexr]);
        if ($result[0]->etapa1_alimentar_restaurarhoja_oodd_valores == 0) {
            return redirect()->route('ooddmastermain');
        }else{ return redirect()->route('login');}
        
    }

    public function ooddmastercambia(Request $request)
    {
        $idexc = intval($request->input('idexc'));
        $cabeza = Cabeza::find($idexc);
        if ($cabeza->cerrado==0) { $cabeza->cerrado = 1;}
        else{$cabeza->cerrado = 0;}
        $cabeza->save();

        return redirect()->route('ooddmastermain')->with('success', 'Oodd cerrado exitosamente');
    }


}










