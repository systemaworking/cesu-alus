<?php
namespace Cesu\Db;

class SqlBuilder
{
    private object $db;

    public function __construct( object $db )
    {
        $this->db = $db;
    }

    protected function getSQLParamValue( $value )
    {
        if ( $value === null || $value === false || ( is_string( $value ) && strlen( $value ) == 0 ) )
        {
            return "NULL";
        }

        if ( is_int( $value ) )
        {
            return sprintf( "%d", $value );
        }

        if( is_array( $value ) )
        {
            return $value[0]; # raw sql
        }

        return "'".$this->db->real_escape_string( $value )."'";
    }

    protected function fieldsToString( $fields )
    {
        if ( !is_array( $fields ) )
        {
            # will be error on execution
            return "";
        }

        $sql = "";
        $tab = str_repeat( " ", 4 );

        $counter = 0;
        foreach( $fields as $fieldName => $fieldValue )
        {
            $sql .= ( $counter ? ",\n{$tab}" : "" ). $fieldName." = ".$this->getSQLParamValue( $fieldValue );
            $counter++;
        }

        return $sql;
    }

    public function build( $options )
    {
        $sql = "";
        $tab = str_repeat( " ", 4 );

        #var_dump( $options );

        $options["type"] = strtolower( $options["type"] );

        if ( !is_array( $options['tables'] ) )
            $options['tables'] = [ $options['tables'] ];

        if ( $options["type"] == "select" )
        {
            $options['fields'] = is_array( $options['fields'] ) ? implode( ",\n{$tab}", $options['fields'] ) : $options['fields'];

            if ( $options["tables"][0] instanceof MysqlUnion )
            {
                $sql .= "SELECT \n{$tab}{$options['fields']} \nFROM ( ".$options["tables"][0]->getSQL()." ) ".$options["tables"][0]->getAlias();
            }else{
                $sql .= "SELECT \n{$tab}{$options['fields']} \nFROM {$options['tables'][0]}";
            }
        }elseif ( $options["type"] == "delete" )
        {
            $sql .= "DELETE FROM \n{$tab}{$options['tables'][0]}";
        }elseif( $options["type"] == "insert" )
        {
            $sql .= "INSERT INTO \n{$tab}{$options['tables'][0]} \nSET \n{$tab}".$this->fieldsToString( $options["fields"] ).
                ( isset( $options["onDuplicateKeyUpdate"] ) && $options["onDuplicateKeyUpdate"] ? " \nON DUPLICATE KEY UPDATE \n".$this->fieldsToString( $options['onDuplicateKeyUpdate'] ) : "" );
        }elseif( $options["type"] == "update" )
        {
            $sql .= "UPDATE \n{$tab}{$options['tables'][0]} \nSET \n{$tab}".$this->fieldsToString( $options["fields"] );
        }else{
            return false;
        }

        foreach( $options["tables"] as $k => $v )
        {
            if ( $k == "0" ) continue;

            $sql .= " \nLEFT JOIN {$k} ON {$v}";
        }

        if ( isset( $options["where"] ) && $options["where"] )
        {
            $sql .= " \nWHERE \n{$tab}";

            if ( is_array( $options["where"] ) )
            {
                if ( isset( $options["where"][0] ) )
                {
                    $sql .= implode( " AND \n{$tab}", $options["where"] );
                }else{
                    $counter = 0;
                    foreach( $options["where"] as $k => $v )
                    {
                        $sql .= ( $counter ? " AND \n{$tab}" : "" ).$k." = ".$this->getSQLParamValue( $v );
                        $counter++;
                    }
                }
            }else{
                $sql .= $options["where"];
            }
        }

        if ( isset( $options["group"] ) && $options["group"] )
        {
            $sql .= " \nGROUP BY ".$options["group"];
        }

        if ( isset( $options["order"] ) && $options["order"] )
        {
            $sql .= " \nORDER BY ".$options["order"];
        }

        if ( isset( $options["limit"] ) && $options["limit"] )
        {
            $sql .= " \nLIMIT ".( is_array( $options["limit"] ) ? $options["limit"][0].",".$options["limit"][1] : $options['limit'] );
        }

        #echo $sql;

        return $sql;
    }
}