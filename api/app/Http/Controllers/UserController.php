<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
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

    /****************************************************************
     * Getters requests
     */

    /**
     * Get all users using User model method
     */
    public function getAll()
    {
        $me=json_decode(json_encode($this->request->auth),true);
        $cod_user=$me[0]['cod_user'];
        return response()->json(['id'=>1,'me'=>$cod_user,'data'=>User::getAll()]);
    }

    /****************************************************************
     * Posts requests
     */

    /**
     * Update login Column
     *  
     */
    public function updateLogin()
    {
        $me=json_decode(json_encode($this->request->auth),true);
        $cod_user=$me[0]['cod_user'];
        $data=json_decode(file_get_contents("php://input"), true);
        if(User::updateLogin($data['login'],$cod_user))
        {
            return response()->json(['id'=>1,'me'=>$cod_user,'data'=>'Realizado']);
        }
        else
        {
            return response()->json(['id'=>-1,'error'=>'No se ha podido actualizar']);
        }        
    }


}
