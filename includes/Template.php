<?php
namespace Cesu;

use Engine\User;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Cesu\Validator;
use Twig\TwigFunction;
use Cesu\Router;

class Template
{
    private static $instance = null;

    public static function create( $template, $args = [] )
    {
        if ( !is_object( static::$instance ) )
        {
            $loader = new FilesystemLoader( ROOT_DIR."/includes/Templates/" );
            $twig = new Environment( $loader, [
                'cache'       => ROOT_DIR.'/cache',
                'auto_reload' => true,
            ] );

            $twig->addFunction( new TwigFunction( "route", function ( $routeName, $params = false ) {
                return Router::getLink( $routeName, $params );
            } ) );

            $twig->addFunction( new TwigFunction( "input", function ( $name ) {
                return Validator::input( $name );
            } ) );

            $twig->addFunction( new TwigFunction( "mix", function ( $name ) {
                global $mixCache;

                if ( !isset( $mixCache ) )
                {
                    $mixCache = json_decode( file_get_contents( ROOT_DIR."/dist/mix-manifest.json" ), true );
                }

                return $mixCache[ $name ] ? Router::getUrlPrefix()."/dist".$mixCache[ $name ] : "";
            } ) );

            static::$instance = $twig;
        }

        $args["errors"] = Validator::getErrors();

        return static::$instance->render( $template, $args );
    }
}