<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brigade`.
 */
class m190110_181800_create_brigade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('brigade', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'status' => "enum('online', 'offline', 'pause') NOT NULL",
            'active' => $this->tinyInteger(1)->notNull(),
            'area_id' => $this->integer(11)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);
        
        $this->addForeignKey('brigade_ibfk_area', 'brigade', 'area_id', 'area', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('brigade');
    }
}
