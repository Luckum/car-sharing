<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brigade_status`.
 */
class m190112_112110_create_brigade_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('brigade_status', [
            'id' => $this->primaryKey(),
            'brigade_id' => $this->integer(11)->notNull(),
            'status' => "enum('online', 'offline', 'pause') NOT NULL",
            'started_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'finished_at' => $this->dateTime()->defaultValue(null)
        ], $tableOptions);
        
        $this->addForeignKey('brigade_status_ibfk_brigade', 'brigade_status', 'brigade_id', 'brigade', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('brigade_status');
    }
}
