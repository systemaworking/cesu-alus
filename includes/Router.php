<?php
namespace Cesu;

class Router
{
    private static $routes = [];

    public static function get( $uri, $callback, $name = false )
    {
        static::$routes[] = [
            "method" => "GET",
            "uri" => $uri,
            "callback" => $callback,
            "name" => $name,
        ];
    }

    public static function post( $uri, $callback, $name = false )
    {
        static::$routes[] = [
            "method" => "GET",
            "uri" => $uri,
            "callback" => $callback,
            "name" => $name,
        ];
    }

    public static function getLink( $routeName, $params = false )
    {
        foreach( static::$routes as $route )
        {
            if ( $route["name"] != $routeName ) continue;

            return static::getUrlPrefix().$route["uri"];
        }

        return false;
    }

    private static function getUrlPrefix()
    {
        $uriPrefix = isset($_SERVER["DOCUMENT_ROOT"]) && $_SERVER["DOCUMENT_ROOT"]
            ? str_ireplace(rtrim(str_replace("\\", "/", $_SERVER["DOCUMENT_ROOT"]), "/\\"), "", str_replace("\\", "/", ROOT_DIR )) : "";

        return $uriPrefix;
    }

    public static function redirect( $url )
    {
        header( "Location: ".static::getUrlPrefix().$url );
        exit;
    }

    public static function run()
    {
        $currentUri = str_replace( static::getUrlPrefix(), "", $_SERVER["REQUEST_URI"] );

        foreach( static::$routes as $route )
        {
            if ( $_SERVER["REQUEST_METHOD"] != $route['method'] )
            {
                continue;
            }

            if ( strcmp( $route["uri"], $currentUri ) != 0 )
            {
                continue;
            }

            return $route["callback"]();
        }

        http_response_code(404);
        echo "Page not found";
    }
}