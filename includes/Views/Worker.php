<?php
namespace Cesu\Views;

use Cesu\Template;
use Cesu\Validator;
use Cesu\User;
use Cesu\Router;

class Worker
{
    public static function setup()
    {
        Router::get( "/", [ static::class, "viewReports" ] );
        Router::get( "/add-report", [ static::class, "addReport" ], "add-report" );
    }

    public static function addReport()
    {
        echo Template::create( "Views\Worker\AddReport.twig" );
    }

    public static function viewReports()
    {
        echo Template::create( "Views\Worker.twig" );
    }
}