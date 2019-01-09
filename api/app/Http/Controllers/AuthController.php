<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;

class AuthController extends Controller
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     * 
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * Create a new token.
     * 
     * @param  Array $user Data user to store on Token
     * @return string
     */
    protected function jwt($user)
    {
        $payload =
        [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];
        
        /**
         * Return TOKEN.
         * Here we encode using JWT_SECRET, configurated on .env file.
         * This secret we use it to decode the Token too.
         */
        return JWT::encode($payload, env('JWT_SECRET'));
    } 

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * 
     * @param  \App\User $user Model to use his atributes and methods 
     * @return mixed
     */
    public function authenticate(User $user)
    {
        $this->validate($this->request,
        [
            //'email'     => 'required|email', // This one if you are auth with email only
            'login'     => 'required', //Use this if you are auth with a UserName
            'pass'  => 'required'
        ]);
        /** Find the user on DB */
        //$user = User::where('email', $this->request->input('email'))->first(); //This when use ORM
        $user = User::getByLoginMail($this->request->input('login'),$this->request->input('login'));
        /** Convert user resultset into an array */
        $user=json_decode(json_encode($user),true);
        if(!$user)
        {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the 
            // below respose for now.
            return response()->json([
                'id'=>-1,
                'error' => 'Usuario no existe'
            ], 400);
        }
        /** Verify the password using your prefer cypher method and generate the token.
         * In this example we use md5(md5) 
         * 
        */
        else if(hash('sha256' ,md5($this->request->input('pass')))==$user[0]['pass_user'])
        {        
            /** Remove pass to put whole user on Token */
            $user[0]['pass_user']='Nada que ver aquí';
            return response()->json([
                'id'=>1,
                'token' => $this->jwt($user)
            ], 200);
        }
        else
        {
            /** Bad CREDENTIALS */
            return response()->json([
                'id'=>-1,
                'name' => 'Contraseña incorrecta'
            ], 400);
        }

        /**
         * NOTE THAT ABOVE IF ELSE STATEMENTS YOU CAN MODIFY
         * TO RESPONSE SOME MORE INFO TO CLIENT
         */
        
    }
}