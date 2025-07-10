<?php
namespace App\Http\Controllers;

use App\Models\Cabeza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DetalleExportOOCC;

class CabezaOoccController extends Controller
{
    public function ooccselect(Request $request)
    {
        $oocc = DB::table('oocc')
            ->select('id', 'oficina', 'numera')
            ->where('id', session('idocx'))
            ->first(); // ✅ Devuelve un solo registro como objeto

        if ($oocc) {
            // Guardamos los valores en la sesión
            Session::put('idocx', $oocc->id);
            Session::put('oficinax', $oocc->oficina);
            Session::put('numerax', $oocc->numera);
        }

        // Retornamos la vista con los valores de sesión
        return view('ooccselect', [
            'oficina' => session('oficinax'),
            'fondo'   => session('fondox'),
            'numera'  => session('numerax'),
            'idoc'    => session('idocx'),
        ]);
    }

    public function ooccmain(Request $request)
    {

        // Asegura que fondo e id estén en sesión si vienen por GET o POST
        if ($request->has('fondox')) {
            Session::put('fondox', $request->input('fondox'));
        }

        if ($request->has('idex')) {
            Session::put('idocx', $request->input('idex'));
        }

        if (session('idocx')) {
            Session::put('oocc_id', session('idocx'));
        }


        $oficina = session('oficinax');

        // Asignar texto del fondo a sesión
        switch (session('fondox')) {
            case 1:
                Session::put('fondotx', '11099A0000 :: AFESSALUD');
                break;
            case 2:
                Session::put('fondotx', '12099A0000 :: SALUD');
                break;
        }

        // Consulta principal
        $results = DB::table('actioocc')
            ->leftJoin('cabeza', function ($join) {
                $join->on('actioocc.id', '=', 'cabeza.actioocc_id')
                    ->where('cabeza.oocc_id', session('idocx'));
            })
            ->where('actioocc.fondo', session('fondox'))
            ->where('actioocc.oocc_id', session('idocx'))
            ->select(
                'cabeza.id as cabeza_id',
                'actioocc.id as actioocc_id',
                'actioocc.actividad',
                'actioocc.prioridad',
                'cabeza.cerrado'
            )
            ->orderBy('actioocc.id')
            ->get();

        // Retorna la vista con resultados
        return view('ooccmain', [
            'results' => $results
        ]);
    }

    public function agregarActividad(Request $request)
    {
        $request->validate([
            'actividad' => 'required|string|max:200',
            // 'prioridad' => 'required|integer|between:1,3',
            'fondo' => 'required|integer',
            'oocc_id' => 'required|integer'
        ]);

        $oocc = DB::table('oocc')->where('id', $request->oocc_id)->first();
        if (!$oocc || !isset($oocc->numera)) {
            return back()->with('error', 'No se encontró el OOCC.');
        }

        try {
            $resultado = DB::select('SELECT etapa1_alimentar_crearhoja_oocc_nueva_activ(?, ?, ?, ?, ?) AS resultado', [
                $oocc->numera,
                $request->fondo,
                '',
                $request->actividad,
                $request->prioridad
            ]);

            if ($resultado[0]->resultado == 1) {
                return back()->with('error', 'Error al crear la actividad.');
            }

            return redirect()->route('ooccmain')->with('success', 'Actividad agregada correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error en la base de datos: ' . $e->getMessage());
        }
    }

    public function renombrarActividad(Request $request)
    {
        $request->validate([
            'actioocc_id' => 'required|integer|exists:actioocc,id',
            'nuevo_nombre' => 'required|string|max:200'
        ]);

        try {
            DB::table('actioocc')
                ->where('id', $request->actioocc_id)
                ->update(['actividad' => $request->nuevo_nombre]);

            return back()->with('success', 'Nombre de actividad actualizado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al renombrar actividad: ' . $e->getMessage());
        }
    }


    public function oocchoja(Request $request)
    {
        // Guardar datos si vienen del formulario
        if ($request->filled('actividadd')) {
            Session::put('nameactiodx', $request->input('actividadd'));
        }
        if ($request->filled('prioridadd')) {
            Session::put('prioactiodx', $request->input('prioridadd'));
        }
        if ($request->filled('cerradd')) {
            Session::put('cerradox', $request->input('cerradd'));
        }
        if ($request->filled('cabezaidex')) {
            Session::put('headerx', $request->input('cabezaidex'));
        }

        // Obtener los detalles
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

        return view('oocchoja', compact('detalles'))
            ->with('cabezax',session('headerx'))
            ->with('cerradox', session('cerradox'));
    }



    public function cerraroocchoja(Request $request)
    {
        $cabeza = Cabeza::find($request->input('cabezaidex'));
        if ($cabeza) {
            $cabeza->cerrado = 1;
            $cabeza->save();
        }

        return redirect()->route('ooccmain')->with('success', 'Actividad cerrada correctamente.');
    }

    public function grabaoocchoja(Request $request)
    {
        if (!session('headerx')) return redirect()->route('login');

        $data = json_decode($request->input('DataGrabar'), true);

        DB::beginTransaction();
        try {
            DB::table('detalle')->where('cabeza_id', session('headerx'))->delete();

            foreach ($data as $fila) {
                $fondotemp = $fila['x'] ? DB::table('financia')->where('codigo', $fila['x'])->value('id') : null;
                $pofitemp = $fila['y'] ? DB::table('pofi')->where('codigo', $fila['y'])->value('id') : null;

                DB::table('detalle')->insert([
                    'cabeza_id' => session('headerx'),
                    'financia_id' => $fondotemp,
                    'pofi_id' => $pofitemp,
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
            }

            DB::commit();
            return redirect()->route('oocchoja');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al guardar.']);
        }
    }

    public function exportaoocchoja(Request $request)
    {
        $detalles = DB::table('detalle as d')
            ->leftJoin('financia as f', 'd.financia_id', '=', 'f.id')
            ->leftJoin('pofi as p', 'd.pofi_id', '=', 'p.id')
            ->select('d.*', 'f.codigo as financia_codigo', 'f.fondo', 'p.codigo as pofi_codigo', 'p.pofi', 'p.color')
            ->where('d.cabeza_id', session('headerx'))
            ->orderBy('d.pofi_id')
            ->get();

        $archivo = session('ocx') . '-'  . '.xlsx';
        return Excel::download(new DetalleExportOOCC($detalles, 1), $archivo);
    }

    public function consolida_oocc()
    {
        $detalles = DB::table('detalle as d')
            ->select([
                DB::raw('MAX(d.cabeza_id) AS cabeza_id'),
                'f.id as financia_id',
                'f.codigo as financia_codigo',
                'f.fondo',
                'p.id as pofi_id',
                'p.codigo as pofi_codigo',
                'p.pofi',
                'p.color',
                'd.tipo',
                DB::raw('SUM(d.estimacion) as estimacion'),
                DB::raw('SUM(d.enero) as enero'),
                DB::raw('SUM(d.febrero) as febrero'),
                DB::raw('SUM(d.marzo) as marzo'),
                DB::raw('SUM(d.abril) as abril'),
                DB::raw('SUM(d.mayo) as mayo'),
                DB::raw('SUM(d.junio) as junio'),
                DB::raw('SUM(d.julio) as julio'),
                DB::raw('SUM(d.agosto) as agosto'),
                DB::raw('SUM(d.septiembre) as septiembre'),
                DB::raw('SUM(d.octubre) as octubre'),
                DB::raw('SUM(d.noviembre) as noviembre'),
                DB::raw('SUM(d.diciembre) as diciembre'),
                DB::raw('SUM(d.total2026) as total2026'),
                DB::raw('SUM(d.proy2027) as proy2027'),
                DB::raw('SUM(d.proy2028) as proy2028'),
                DB::raw('SUM(d.proy2029) as proy2029')
            ])
            ->leftJoin('financia as f', 'd.financia_id', '=', 'f.id')
            ->leftJoin('pofi as p', 'd.pofi_id', '=', 'p.id')
            ->whereIn('d.cabeza_id', function ($query) {
                $query->select('c.id')
                    ->from('cabeza as c')
                    ->join('actioocc as a', 'c.actioocc_id', '=', 'a.id')
                    ->where('a.fondo', '=', session('fondox'))
                    ->where('a.oocc_id', '=', session('idocx'));
            })
            ->groupBy('f.id', 'p.id', 'd.tipo', 'f.fondo', 'p.color', 'p.pofi')
            ->orderBy('pofi_id')
            ->get();

        $archivo = session('ocx') . '-ConsolidadoOOCC.xlsx';
        return Excel::download(new DetalleExportOOCC($detalles, 2), $archivo);
    }

    public function consolidadoGeneralOOCC()
    {
        $detalles = DB::table('detalle as d')
            ->select([
                DB::raw('MAX(d.cabeza_id) AS cabeza_id'),
                'f.id as financia_id',
                'f.codigo as financia_codigo',
                'f.fondo',
                'p.id as pofi_id',
                'p.codigo as pofi_codigo',
                'p.pofi',
                'p.color',
                'd.tipo',
                DB::raw('SUM(d.estimacion) as estimacion'),
                DB::raw('SUM(d.enero) as enero'),
                DB::raw('SUM(d.febrero) as febrero'),
                DB::raw('SUM(d.marzo) as marzo'),
                DB::raw('SUM(d.abril) as abril'),
                DB::raw('SUM(d.mayo) as mayo'),
                DB::raw('SUM(d.junio) as junio'),
                DB::raw('SUM(d.julio) as julio'),
                DB::raw('SUM(d.agosto) as agosto'),
                DB::raw('SUM(d.septiembre) as septiembre'),
                DB::raw('SUM(d.octubre) as octubre'),
                DB::raw('SUM(d.noviembre) as noviembre'),
                DB::raw('SUM(d.diciembre) as diciembre'),
                DB::raw('SUM(d.total2026) as total2026'),
                DB::raw('SUM(d.proy2027) as proy2027'),
                DB::raw('SUM(d.proy2028) as proy2028'),
                DB::raw('SUM(d.proy2029) as proy2029')
            ])
            ->leftJoin('financia as f', 'd.financia_id', '=', 'f.id')
            ->leftJoin('pofi as p', 'd.pofi_id', '=', 'p.id')
            ->whereIn('d.cabeza_id', function ($query) {
                $query->select('c.id')
                    ->from('cabeza as c')
                    ->join('actioocc as a', 'c.actioocc_id', '=', 'a.id');
                    // <- No se filtra por fondo aquí, se incluyen todos
            })
            ->groupBy('f.id', 'p.id', 'd.tipo', 'f.fondo', 'p.color', 'p.pofi')
            ->orderBy('pofi_id')
            ->get();

        $archivo = 'OOCC-ConsolidadoGeneral.xlsx';
        return Excel::download(new DetalleExportOOCC($detalles, 2), $archivo);
    }    
}
