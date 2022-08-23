<?php
namespace Cesu;

use Cesu\Db\SqlBuilder;

class Db
{
    private static $instance = null;
    private static $lastSQL = "";

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

    public static function getSQL():string
    {
        return static::$lastSQL;
    }

    public static function getError():string
    {
        return static::getSQL()."\n".static::$instance->error;
    }

    public static function query( $sql )
    {
        $db = static::init();

        static::$lastSQL = $sql;

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

    public static function delete( $tables, $where = false )
    {
        static::init();

        $sql = ( new SqlBuilder( static::$instance ) )->build([
            "type" 	 => "delete",
            "tables" => $tables,
            "where"  => $where,
        ]);

        return static::query( $sql );
    }

    public static function update( $tables, $fields, $where = false )
    {
        static::init();

        $sql = ( new SqlBuilder( static::$instance ) )->build([
            "type" 	 => "update",
            "fields" => $fields,
            "tables" => $tables,
            "where"  => $where,
        ]);

        return static::query( $sql );
    }

    public static function countRows( $tables, $where = false, $limit = false )
    {
        static::init();

        $sql = ( new SqlBuilder( static::$instance ) )->build( [
            "type" => "select",
            "fields" => "count(*) cnt",
            "tables" => $tables,
            "where" => $where,
            "limit" => $limit,
        ] );

        $row = static::queryRow( $sql );
        if ( $row === false ) return false;

        return $row[ "cnt" ];
    }

    public static function insert( $tables, $fields, $onDuplicateKeyUpdate = false )
    {
        static::init();

        $sql = ( new SqlBuilder( static::$instance ) )->build( [
            "type"   => "insert",
            "fields" => $fields,
            "tables" => $tables,
            "onDuplicateKeyUpdate" => $onDuplicateKeyUpdate,
        ] );

        $rs = static::query( $sql );
        if ( !$rs )
        {
            return false;
        }

        return static::$instance->insert_id > 0 ? static::$instance->insert_id : true;
    }
}