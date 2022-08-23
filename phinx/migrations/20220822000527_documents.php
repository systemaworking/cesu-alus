<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class Documents extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table( "documents" );
        $table
            ->addColumn( "report_id", "integer", [ "null" => false ] )
            ->addColumn( "create_date", "date", [ "null" => false ] )
            ->addColumn( "number", "string", ["limit" => 50, "null" => false ] )
            ->addColumn( "producer", "string", ["limit" => 50, "null" => false ] )
            ->addColumn( "price", "decimal", [ "null" => false, 'precision'=> 25, 'scale'=> 2 ] )
            ->addColumn( "comment", "text", ["limit" => MysqlAdapter::TEXT_MEDIUM, "null" => false ] )
            ->addColumn( "picture", "string", [ "limit" => 50, "null" => false ] )
            ->addForeignKey( "report_id", "reports", "id", ['delete'=> 'CASCADE', 'update'=> 'CASCADE'] )
            ->save();
    }
}
