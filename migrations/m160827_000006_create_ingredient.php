<?php
namespace altiore\recipe\migrations;

use altiore\base\console\Migration;
use Yii;

/**
 * Class m160827_000005_create_product
 */
class m160827_000006_create_ingredient extends Migration
{
    private $table = '{{%ingredient}}';
    private $tableCategory = '{{%ingredient_category}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableCategory, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
        ], $tableOptions);

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'name' => $this->string()->unique()->notNull(),
            'description' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createIndex(null, $this->table, 'created_by');
        $this->createIndex(null, $this->table, 'updated_by');
        $this->createIndex(null, $this->table, 'category_id');

        $userTable = Yii::$app->getModule('recipe')->userTable;
        $userPrimaryKey = Yii::$app->getModule('recipe')->userPrimaryKey;

        $this->addForeignKey(null, $this->table, 'created_by', $userTable, $userPrimaryKey, 'SET NULL', 'CASCADE');
        $this->addForeignKey(null, $this->table, 'updated_by', $userTable, $userPrimaryKey, 'SET NULL', 'CASCADE');
        $this->addForeignKey(null, $this->table, 'category_id', $this->tableCategory, 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey(null, $this->table, 'category_id');
        $this->dropForeignKey(null, $this->table, 'updated_by');
        $this->dropForeignKey(null, $this->table, 'created_by');


        $this->dropIndex(null, $this->table, 'created_by');
        $this->dropIndex(null, $this->table, 'updated_by');
        $this->dropIndex(null, $this->table, 'category_id');

        $this->dropTable($this->table);

        $this->dropTable($this->tableCategory);
    }
}
