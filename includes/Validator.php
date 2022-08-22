<?php
namespace Cesu;

class Validator
{
    private static $input = false;
    private static array $errors = [];

    public static function setInput( $array = false )
    {
        if ( $array !== false )
        {
            static::$input = $array;
        } else {
            static::$input = array_merge( $_GET, $_POST );
        }
    }

    private static function init()
    {
        if ( static::$input === false )
        {
            static::setInput();
        }
    }

    public static function input( string $name )
    {
        return static::$input[ $name ] ?? "";
    }

    public static function custom( string $error )
    {
        static::$errors[] = $error;
    }

    public static function empty( string $name, string $error )
    {
        static::init();

        if ( empty( static::$input[ $name ] ) )
        {
            static::$errors[] = $error;
        }
    }

    public static function hasErrors():bool
    {
        return count( static::$errors ) > 0;
    }

    public static function getErrors():array
    {
        return static::$errors;
    }
}