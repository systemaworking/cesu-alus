<?php
define( "ROOT_DIR", __DIR__ );

session_start();
error_reporting( E_ALL ^ E_WARNING );

require __DIR__."/vendor/autoload.php";

use \Cesu\User;
use \Cesu\Views\Login;
use \Cesu\Views\Worker;
use \Cesu\Views\Accountant;
use \Cesu\Views\Boss;
use \Cesu\Router;

if ( !User::isValid())
{
    Login::execute();
}

if ( isset( $_GET["logout"] ) )
{
    User::logout();

    Router::redirect( "/" );
}

if ( User::isWorker() )
{
    Worker::setup();
} elseif ( User::isBoss() )
{
    Boss::setup();
} elseif ( User::isAccountant() )
{
    Accountant::setup();
}

Router::run();