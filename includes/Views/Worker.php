<?php
namespace Cesu\Views;

use Cesu\Template;
use Cesu\Validator;
use Cesu\User;
use Cesu\Router;
use Cesu\Db;

class Worker
{
    public static function setup()
    {
        Router::get( "/", [ static::class, "myReports" ], "my-reports" );
        Router::get( "/worker/add-report", [ static::class, "addReport" ], "add-report" );
        #Router::post( "/worker/add-report", [ static::class, "addReport" ], "add-report" );
        Router::post( "/worker/add-document", [ static::class, "addDocument" ], "add-document" );
    }

    private static function removeTmpReports()
    {
        Db::delete( "reports", "( select count(*) from documents d where d.report_id = reports.id) = 0" );
    }

    public static function saveReport()
    {
        static::removeTmpReports();

        $reportID = Db::insert( "reports",[
            "user_id" => User::get( "id" ),
        ] );

        Validator::json( $reportID );
    }

    public static function addDocument()
    {
        Validator::dbNotExists( "report_id", "reports", "id", "report_id not exists" );

        if ( !Validator::empty( "create_date", "Ievadiet documenta datumu" ) )
        {
            Validator::dateInvalid( "create_date", "Documenta datums nepareizs" );
        }

        Validator::empty( "number", "Ievadiet numuru"  );
        Validator::empty( "producer", "Ievadiet uzņēmumu"  );

        if ( !Validator::empty( "price", "Ievadiet summu" ) )
        {
            Validator::regexp( "price", "#^[0-9]{1,}(\.[0-9]{1,2})?$#si", "Summas formats nepareizs (NNNNNNNNNNN.NN)" );
        }

        Validator::empty( "comment", "Ievadiet komentaru"  );
        if ( !Validator::file( "picture", "Izvēlēties attēlu" ) )
        {
            Validator::notFileTypes( "picture", [ "gif", "png", "jpg", "bmp" ], "Files var būt tikai: gif, png, jpg, bmp" );
        }

        if ( !Validator::hasErrors() )
        {
            $picturePath = ROOT_DIR."/files/reports/".Validator::input( "report_id" )."/".md5( microtime( true ) );
            $pictureDir = dirname( $picturePath );

            @mkdir( $pictureDir, 0777, true );
            if ( !is_dir( $pictureDir ) )
            {
                Validator::custom( "Can not create directory for picture" );
            } elseif( !move_uploaded_file( $_FILES["picture"]["tmp_name"], $picturePath ) ) {
                Validator::custom( "Can note move picture to directory" );
            }

            if ( !Validator::hasErrors() )
            {
                $documentID = Db::insert( "documents", [
                    "report_id"   => Validator::input( "report_id" ),
                    "create_date" => Validator::input( "create_date", "date" ),
                    "number"      => Validator::input( "number" ),
                    "producer"    => Validator::input( "producer" ),
                    "price"       => Validator::input( "price" ),
                    "comment"     => Validator::input( "comment" ),
                    "picture"     => basename( $picturePath ),
                ] );

                if ( $documentID === false )
                {
                    Validator::custom( Db::getError() );
                    Validator::custom( "Internal error" );
                }

                Validator::json( $documentID );
            }
        }

        Validator::json();
    }

    public static function addReport()
    {
        static::removeTmpReports();

        $reportID = Db::insert( "reports",[
            "user_id" => User::get( "id" ),
        ] );

        echo Template::create( "Views\Worker\AddReport.twig", [
            "report_id" => $reportID,
        ] );
    }

    public static function myReports()
    {
        static::removeTmpReports();

        $reports = Db::selectArray( "*", "reports", [ "user_id" => User::get( "id" ) ] );
        if ( $reports === false ) Validator::custom( "Internal error" );

        echo Template::create( "Views\Worker.twig", [
            "reports" => is_array( $reports ) ? $reports : [],
        ] );
    }
}