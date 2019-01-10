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
     * SELECT Methods Using DB query
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
     * UPDATE methods
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

    /************************************************************
     * INSERT methods
     */

    /**
     * Insert a new User
     * @param name Name of the user
     * @param login User Name for login of the user
     * @param mail Mail of the user
     * @param pass Password of the user
     * 
     * @return boolean
     */
    public static function insert($name,$login,$mail,$pass)
    {
        $pass=hash('sha256' ,md5($pass));
        try
        {
            $query='INSERT INTO usuario(nom_user, login_user, mail_user, pass_user, img_user, date_login, cod_t_user, cod_state) VALUES (?, ?, ?, ?, "def.png", ?, 2, 1)';
            DB::insert($query,[$name,$login,$mail,$pass,date('Y-m-d H:i:s')]);
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }
}
