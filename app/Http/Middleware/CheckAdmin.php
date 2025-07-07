<?php
namespace App\Http\Middleware;
use Closure;

class CheckAdmin
{
    public function handle($request, Closure $next)
    {
        if($request->token==md5('system')){ return $next($request); }
        else{
            return response()->json([
                "errores" => 1,
                "color" => 'danger',
                "message" => 'Usted no tiene autorización.',
            ]);
        }
    }
}
