<?php
namespace Cesu;

use Cesu\Db\SqlBuilder;

class Db
{
    private static $instance = null;

    private static function init()
    {
        if ( !is_object( static::$instance ) )
        {
            $config = require ROOT_DIR."/phinx.php";
            if ( !is_array( $config ) )
            {
                die( "Db init error" );
            }

            $use = $config[ "environments" ][ "default_environment" ];
            $config = $config[ "environments" ][ $use ];

            $db = new \Mysqli( $config[ "host" ], $config[ "user" ], $config[ "pass" ], $config[ "name" ] );
            if ( $db->connect_errno )
            {
                die( 'Db connect error: '.$db->connect_error );
            }

            $db->set_charset( $config[ "charset" ] );

            static::$instance = $db;
        }

        return static::$instance;
    }

    public static function query( $sql )
    {
        $db = static::init();

        return $db->query( $sql );
    }

    public static function queryArray( $sql )
    {
        $rs = static::query( $sql );
        if ( !$rs ) return false;

        $rows = [];
        while ( $row = $rs->fetch_assoc() )
        {
            $rows[] = $row;
        }

        return $rows;
    }

    public static function queryRow( $sql )
    {
        $rs = static::query( $sql );
        if ( !$rs ) return false;

        $row = $rs->fetch_assoc();

        return $row ? $row : null;
    }

    public static function selectArray( $fields, $tables, $where = false, $group = false, $order = false, $limit = false )
    {
        static::init();

        $sql = ( new SqlBuilder( static::$instance ) )->build([
            "type" 	 => "select",
            "fields" => $fields,
            "tables" => $tables,
            "where"  => $where,
            "group"  => $group,
            "order"  => $order,
            "limit"  => $limit,
        ]);

        return static::queryArray( $sql );
    }

    public static function selectRow( $fields, $tables, $where = false, $group = false, $order = false, $limit = false )
    {
        static::init();

        $sql = ( new SqlBuilder( static::$instance ) )->build([
            "type" 	 => "select",
            "fields" => $fields,
            "tables" => $tables,
            "where"  => $where,
            "group"  => $group,
            "order"  => $order,
            "limit"  => $limit,
        ]);

        return static::queryRow( $sql );
    }
}