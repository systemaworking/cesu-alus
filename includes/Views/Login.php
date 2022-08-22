<?php
namespace Cesu\Views;

use Cesu\Template;
use Cesu\Validator;
use Cesu\User;
use Cesu\Router;

class Login
{
    public static function execute()
    {
        if ( $_POST )
        {
            Validator::empty( "login", "Login required" );
            Validator::empty( "password", "Password required" );

            if ( !Validator::hasErrors() )
            {
                if ( User::tryLogin( $_POST["login"], $_POST["password"] ) )
                {
                    Router::redirect( "/" );
                } else {
                    Validator::custom( "No such user" );
                }
            }
        }

        echo Template::create( "Views\Login.twig" );
        exit;
    }
}