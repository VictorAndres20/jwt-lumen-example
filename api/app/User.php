<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom_user', 'login_user',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'pass_user',
    ];

    /********************************************************
     * Select Methods Using DB query
     */

    /**
     * Get all users
     * 
     * @return result set
     */
    public static function getAll()
    {
        $users=DB::select('SELECT cod_user,nom_user, login_user, mail_user, img_user, date_login, cod_t_user, cod_state FROM usuario');
        return $users;
    }

    /**
     * Get user WITH login_user OR WITH mail_user Column
     * 
     * @param String $login Login user
     * @param String $mail Mail user
     * 
     * @return result set
     */
    public static function getByLoginMail($login,$mail)
    {
        $user=DB::select('SELECT cod_user,nom_user, login_user, mail_user, pass_user, img_user, date_login, cod_t_user, cod_state, id_user FROM usuario WHERE login_user=? OR mail_user=?',[$login,$mail]);
        return $user;
    }

    /************************************************************
     * Update methods
     */

    /**
     * Update Login Column from user by cod_user
     * 
     * @param String $login New login to put
     * @param int $cod_user User Cod
     * 
     * @return boolean
     */
    public static function updateLogin($login,$cod_user)
    {
        try
        {
            DB::update('UPDATE usuario SET login_user=? WHERE cod_user=?',[$login,$cod_user]);
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }
}
