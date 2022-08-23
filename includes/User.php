<?php
namespace Cesu;

use Cesu\Db;

class User
{
    public static function isValid()
    {
        return isset( $_SESSION["user"] );
    }

    public static function logout()
    {
        unset( $_SESSION["user"] );
    }

    public static function get( $field = false )
    {
        if ( !static::isValid() ) return false;

        return $field !== false
            ? ( $_SESSION["user"][ $field ] ?? false )
            : $_SESSION["user"];
    }

    public static function tryLogin( string $login, string $password )
    {
        $user = Db::selectRow( "*", "users", [
            "login" => $login,
            "password" => md5( $password ),
        ] );

        if ( $user )
        {
            $_SESSION["user"] = $user;

            return true;
        }

        return false;
    }

    public static function isWorker()
    {
        return isset( $_SESSION["user"] ) ? $_SESSION["user"]["type"] === "worker" : false;
    }

    public static function isBoss()
    {
        return isset( $_SESSION["user"] ) ? $_SESSION["user"]["type"] === "boss" : false;
    }

    public static function isAccountant()
    {
        return isset( $_SESSION["user"] ) ? $_SESSION["user"]["type"] === "accountant" : false;
    }

}