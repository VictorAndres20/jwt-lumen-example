<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\User; // Model we are using VERY IMPORTANT TO CONFIGURE THIS IF YOU NEED
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        /** Get Token with GET input */
        $token = $request->get('token');
        
        if(!$token) {
            /** Unauthorized, no token */
            return response()->json([
                'id'=>-1,
                'error' => 'No hay Token.'
            ], 401);
        }
        try {
            /** Decode Token */
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'id'=>-1,
                'error' => 'Token a expirado.'
            ], 400);
        } catch(Exception $e) {
            return response()->json([
                'id'=>-1,
                'error' => 'Error docodificando Token.'
            ], 400);
        }
        //$user = User::find($credentials->sub); //If you are using ORM and only store the ID on TOKEN
        $user=$credentials->sub; //If you store whole user data on Token 
        /** Put user data in the request so that you can grab it from there */
        $request->auth = $user;
        return $next($request);
    }
}