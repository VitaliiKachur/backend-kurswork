<?php
namespace models;

use core\Model;
use core\Core;

    /**
    * @property int $id ID користувача
    * @property string $first_name Ім'я користувача
    * @property string $last_name Прізвище користувача
    * @property string $username логін користувача
    * @property string $password Пароль користувача
    * @property string $status Біо користувача
    * @property string $profile_picture Аватарка користувача
    * @property bool $is_online Чи в мережі користувач
    * @property bool $is_admin Чи адмін месенджера цей користувач
    * @property bool $is_banned Чи забанено користувача
    */
class Users extends Model
{
    public static $tableName = 'users';

    public static function FindByLoginAndPassword($username, $password)
    {
        $rows = self::findByCondition(['username' => $username, 'password' => $password]);
        if(!empty($rows))
            return $rows[0];
        else
            return null;
    }
    public static function FindByLogin($username)
    {
        $rows = self::findByCondition(['username' => $username]);
        if(!empty($rows))
            return $rows[0];
        else
            return null;
    }
    public static function isUserLogged()
    {
        return !empty(Core::get()->session->get('user'));
    }
    public static function LoginUser($user)
    {
        Core::get()->session->set('user', $user);
    }
    public static function LogoutUser($user)
    {
        Core::get()->session->remove('user');
    }
    public static function RegisterUser($username, $password, $last_name, $first_name, $status, $profile_picture)
    {
        $user = new Users();
        $user->username = $username;
        $user->password = $password;
        $user->last_name = $last_name;
        $user->first_name = $first_name;
        $user->status = $status;
        $user->profile_picture = $profile_picture;
        $user->save();
    }
    public static function isAdminLogged()
    {
        $user = Core::get()->session->get('user');
        return !empty($user) && $user['is_admin'];
    }
}