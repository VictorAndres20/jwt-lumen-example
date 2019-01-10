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
     * 
     * @return response
     */
    public function getAll()
    {
        $me=json_decode(json_encode($this->request->auth),true);
        $cod_user=$me[0]['cod_user'];
        return response()->json(['id'=>1,'me'=>$cod_user,'data'=>User::getAll()]);
    }

    /****************************************************************
     * Puts requests
     */

    /**
     * Update user's login Column
     * 
     * @return response
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

    /****************************************************************
     * Posts requests
     */

    /**
     * Create a new User
     * 
     * @param name Name of the user
     * @param login User Name for login of the user
     * @param mail Mail of the user
     * @param pass Password of the user
     * 
     * @return response
     */
    public function insert()
    {
        $id=-1;
        $res="Error";
        $me=json_decode(json_encode($this->request->auth),true);
        $cod_user=$me[0]['cod_user'];
        $data=json_decode(file_get_contents("php://input"),true);
        if(count(User::getByLoginMail($data['login'],$data['mail']))!=0)
        {
            $res="Usuario ya esxiste";
        }
        else if(!filter_var($data['mail'], FILTER_VALIDATE_EMAIL))
        {
            $res="Correo no válido";
        }
        else if(User::insert($data['name'],$data['login'],$data['mail'],$data['pass']))
        {
            $id=1;
            $res="Usuario registrado";
        } 
        else
        {
            $res="Error en el registro";
        } 

        return response()->json(["id"=>$id,"me"=>$cod_user,"data"=>$res]);
    }

}
