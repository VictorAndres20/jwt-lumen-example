<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use App\Shop;

class ShopController extends Controller
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
     * Get shops by cod_user.
     * Relation= Shop belong to User | User has many Shop.
     */
     public function getAllShopsByUSer($cod_user)
     {
        $me=json_decode(json_encode($this->request->auth),true);
        $cod=$me[0]['cod_user'];
        return response()->json(['id'=>1,'me'=>$cod,'data'=>Shop::getAllShopsByUSer($cod_user)]);
     }
}
