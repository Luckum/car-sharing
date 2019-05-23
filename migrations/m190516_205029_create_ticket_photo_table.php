<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket_photo}}`.
 */
class m190516_205029_create_ticket_photo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%ticket_photo}}', [
            'id' => $this->primaryKey(),
            'ticket_id' => $this->integer(11)->notNull(),
            'path' => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);
        
        $this->addForeignKey('ticket_photo_ibfk_ticket', 'ticket_photo', 'ticket_id', 'ticket', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ticket_photo}}');
    }
}
