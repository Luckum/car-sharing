<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ticket`.
 */
class m190110_182838_create_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('ticket', [
            'id' => $this->primaryKey(),
            'type' => "enum('auto', 'handle') NOT NULL",
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'started_at' => $this->dateTime()->defaultValue(null),
            'finished_at' => $this->dateTime()->defaultValue(null),
            'external_id' => $this->integer(11)->notNull(),
            'status' => $this->tinyInteger(1)->notNull(),
            'rent_type' => $this->tinyInteger(1)->notNull(),
            'comment' => $this->text()->defaultValue(null),
            'brigade_id' => $this->integer(11)->defaultValue(null),
            'lat' => $this->decimal(11, 8)->notNull(),
            'lon' => $this->decimal(11, 8)->notNull(),
        ], $tableOptions);
        
        $this->addForeignKey('ticket_ibfk_brigade', 'ticket', 'brigade_id', 'brigade', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ticket');
    }
}
