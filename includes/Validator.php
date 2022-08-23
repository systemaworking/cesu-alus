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

    public static function input( string $name, $format = false )
    {
        static::init();

        $return = static::$input[ $name ] ?? "";

        if ( $format !== false )
        {
            if ( $format == "date" ) $return = date( "Y-m-d", strtotime( $return ) );
        }

        return $return;
    }

    public static function custom( string $error )
    {
        static::$errors[] = $error;

        return true;
    }

    private static function condition( bool $trueIfSetError, string $error )
    {
        if ( $trueIfSetError )
        {
            return static::custom( $error );
        }

        return false;
    }

    public static function notIsset( string $name, string $error )
    {
        static::init();

        return static::condition( !isset( static::$input[ $name ] ), $error );
    }

    public static function empty( string $name, string $error )
    {
        static::init();

        return static::condition( empty( static::$input[ $name ] ), $error );
    }

    public static function dbNotExists( string $name, string $table, string $field, string $error )
    {
        $count = Db::countRows( $table, [
            $field => static::input( $name )
        ] );

        return static::condition( $count == 0, $error );
    }

    public static function regexp( string $name, string $regexp, string $error )
    {
        return static::condition( !preg_match( $regexp, static::input( $name ) ), $error );
    }

    public static function notFileTypes( string $name, array $types, string $error )
    {
        if ( !isset( $_FILES[ $name ] ) )
        {
            return static::custom( $error );
        }

        $tmpType = mime_content_type( $_FILES[ $name ]["tmp_name"] );

        $knownTypes = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
        ];

        foreach ( $types as $type )
        {
            $typeMime = $knownTypes[ $type ];
            if ( !strcasecmp( $tmpType, $typeMime ) )
            {
                return false;
            }
        }

        return static::condition( true, $error );
    }

    public static function file( string $name, string $error )
    {
        if ( !isset( $_FILES[ $name ] ) )
        {
            return static::custom( $error );
        }

        if ( filesize( $_FILES[ $name ]["tmp_name"] ) == 0 )
        {
            return static::custom( $error );
        }

        $phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );

        if ( $_FILES[ $name ]["error"] != 0 )
        {
            return static::custom( $name.": ".$phpFileUploadErrors[ $_FILES[ $name ]["error"] ] );
        }

        return false;
    }

    public static function dateInvalid( string $name, string $error )
    {
        static::init();

        $ts = strtotime( static::$input[$name ] );

        return static::condition( $ts === false || date( "Y", $ts ) < 1900, $error );
    }

    public static function hasErrors():bool
    {
        return count( static::$errors ) > 0;
    }

    public static function getErrors():array
    {
        return static::$errors;
    }

    public static function json( $data = null )
    {
        header( "Content-Type: application/json" );

        $errors = static::getErrors();

        $json = [
            "success" => count( $errors ) == 0,
            "errors" => $errors,
            "data" => $data,
        ];

        echo json_encode( $json );
        exit;
    }
}