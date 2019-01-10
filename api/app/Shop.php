<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shop extends Model
{
    /*************************************************************
     * UPDATE statements
     */

    /**
     * ******* WITH LARAVEL TRANSACTION ***********
     */
    public static function updateUserAndInsert($id_user,$cod_user,$nit_shop,$nom_shop,$addr_shop,$phon_shop,$cod_d_open)
    {
        $transaction="
            BEGIN;
                UPDATE usuario SET id_user=?, cod_t_user=3 WHERE cod_user=?;
                INSERT INTO shop(nit_shop, nom_shop, addr_shop, phon_shop, img_shop, cod_city, cod_user, cod_state) VALUES ('93498567234',?,'crr56 n° 165 - 67','4562347658','def.png',1,3,1);
            COMMIT;";
        try
        {
            $params=[$id_user,$cod_user,$nit_shop,$nom_shop,$addr_shop,$phon_shop,$cod_d_open];
            DB::transaction(function ($params) use ($params) 
            {
                DB::table('usuario')->where([
                        ['cod_user','=',$params[1]]
                    ])->update([
                        'id_user' => $params[0],
                        'cod_t_user' => 3
                    ]);
            
                DB::table('shop')->insert([
                        'nit_shop' => $params[2],
                        'nom_shop' => $params[3],
                        'addr_shop' => $params[4],
                        'phon_shop' => $params[5],
                        'img_shop' => 'def.png',
                        'cod_d_open' => $params[6],
                        'cod_city' => 1,
                        'cod_user' => $params[1],
                        'cod_state' => 1
                    ]
                );
            });
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }

    }

    /********************************************************************
     * SELECT statements
     */

    /**
     * Get All Active Shops
     */
    public static function getAllShopsByState($cod_state)
    {
        $sql="SELECT shop.cod_shop, shop.nit_shop, shop.nom_shop, shop.addr_shop, shop.phon_shop, shop.img_shop, usuario.cod_user, city.cod_city, city.nom_city, d_open.nom_d_open 
        FROM shop,city, usuario, state, d_open 
        WHERE shop.cod_d_open=d_open.cod_d_open AND shop.cod_city=city.cod_city AND shop.cod_user=usuario.cod_user AND shop.cod_state=state.cod_state AND state.cod_state =?";
        return DB::select($sql,[$cod_state]);
    }

    /**
     * Get shop by city
     */
    public static function getAllShopsByCity($nom_city)
    {
        $sql="SELECT shop.cod_shop, shop.nit_shop, shop.nom_shop, shop.addr_shop, shop.phon_shop, shop.img_shop, usuario.cod_user, city.cod_city, city.nom_city, d_open.nom_d_open 
        FROM shop,city, usuario, state, d_open 
        WHERE shop.cod_d_open=d_open.cod_d_open AND shop.cod_city=city.cod_city AND shop.cod_user=usuario.cod_user AND shop.cod_state=state.cod_state AND state.cod_state =1 AND city.nom_city=?";
        return DB::select($sql,[$nom_city]);
    }

    /**
     * Get shop by user
     * Relations =>  Shop belong to User | User has many Shop.
     *               Shop belong to City | City has many Shop.
     *               Shop has one State | State belongs to many Shop.
     *               Shop has one DayOpen | DayOpen belongs to many Shop.
     *               
     * But in this case THERE IS NO ORM.
     * Relations made with SQL sintax
     */
    public static function getAllShopsByUSer($cod_user)
    {
        $sql="SELECT shop.cod_shop, shop.nit_shop, shop.nom_shop, shop.addr_shop, shop.phon_shop, shop.img_shop, usuario.cod_user, city.cod_city, city.nom_city, d_open.nom_d_open 
        FROM shop,city, usuario, state, d_open 
        WHERE shop.cod_d_open=d_open.cod_d_open AND shop.cod_city=city.cod_city AND shop.cod_user=usuario.cod_user AND shop.cod_state=state.cod_state AND state.cod_state =1 AND usuario.cod_user=?";
        return DB::select($sql,[$cod_user]);
    }
}
