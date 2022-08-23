<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Users extends AbstractMigration
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
        $table = $this->table( "users" );
        $table
            ->addColumn( "login", "string", [ "limit" => 50, "null" => false ] )
            ->addColumn( "password", "string", [ "limit" => 32, "null" => false ] )
            ->addColumn("type", 'enum', ['values' => ['worker', 'boss', 'accountant'], "null" => false ] )
            ->addIndex("login", ['unique' => true] )
            ->create();

        $table->truncate();

        $table->insert( [ "login" => "worker1", "password" => md5( "test" ), "type" => "worker" ] )->saveData();
        $table->insert( [ "login" => "worker2", "password" => md5( "test" ), "type" => "worker" ] )->saveData();
        $table->insert( [ "login" => "worker3", "password" => md5( "test" ), "type" => "worker" ] )->saveData();
        $table->insert( [ "login" => "boss", "password" => md5( "test" ), "type" => "boss" ] )->saveData();
    }
}
