<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Oodd;
use App\Models\Eess;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        session([
                'idredx' => null,
                'redx' => null,
                'idesx' => null,
                'codesx' => null,
                'esx' => null,
                'idactiodx' => null,
                'nameactiodx' => null,
                'prioactiodx' => null,
                'idactiocx' => null,
                'nameactiocx' => null,
                'prioactiocx' => null,
                'cerradox' => null,
                'headerx' => null,
                'idocx' => null,
                'ocx' => null,
                'acti_ocx' => null,
                'fondox' => null,
                'codex' => null,
                'actix' => null,
                'priox' => null,
                'ooccx' => null,
                'numerax' => null,
                'oficinax' => null,
                'fondox' => null,
            ]);

        if ($request->isMethod('get')) { return view('login'); }

        // Si form POST, procesamos la autenticación
        $validated = $request->validate(['usuario'=>'required','clave'=>'required']);

        $usu = Usuario::where('usuario', $validated['usuario'])->first();

        if ($usu && $usu->clave === $validated['clave']){ // LOGIN::ok
            switch ($usu->tipo) {
                case 'a':
                    $organo = DB::table('oodd')
                        ->select('id','oodd')
                        ->orderBy('numera')
                        ->get();

                    return view('ooddmasterselect', compact('organo'));
                    break;

                case 'b': // control maestro de oocc
                    //session()->flush();
                    $oficina = DB::table('oocc')
                        ->select('id','oocc')
                        ->orderBy('numera')
                        ->get();

                    return view('ooccmasterselect', compact('oficina'));
                    break;

                case 'm': return redirect()->route('mastermaster');break;
                case 'c':
                    $oocc = DB::table('oocc')
                        ->select('id', 'oficina')
                        ->where('numera', $usu->numera)
                        ->limit(1)
                        ->first();

                    Session::put('idocx',$oocc->id);
                    Session::put('ocx',$oocc->oficina);

                    return view('ooccselect', [
                        'oficina' => $oocc->oficina
                    ]);
                    break;

                case 'd':
                    $oodd = DB::table('oodd')
                            ->select('id','oodd')
                            ->where('numera',$usu->numera)
                            ->limit(1)
                            ->first();

                    Session::put('idredx',$oodd->id);
                    Session::put('redx',$oodd->oodd);

                    $eess = DB::table('eess')
                            ->select('id','codigo', 'eess')
                            ->where('oodd_id',$usu->numera)
                            ->get();

                    return view('ooddselect', [
                        'organo' => $oodd,
                        'eess' => $eess
                    ]);
                    break;

                default: return route('login'); break;
            }
        }else{
            return redirect()->route('login');
        }
    }

    // En LoginController.php
    public function logout(Request $request)
    {
        // Destruir todas las variables de sesión
        Session::flush();

        // Redirigir al usuario a la página de login
        return redirect()->route('login');
    }

}
