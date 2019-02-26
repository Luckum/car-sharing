<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ticket_has_job_type`.
 */
class m190110_183852_create_ticket_has_job_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('ticket_has_job_type', [
            'id' => $this->primaryKey(),
            'ticket_id' => $this->integer(11)->notNull(),
            'job_type_id' => $this->integer(11)->notNull(),
        ], $tableOptions);
        
        $this->addForeignKey('ticket_has_job_type_ibfk_ticket', 'ticket_has_job_type', 'ticket_id', 'ticket', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('ticket_has_job_type_ibfk_job_type', 'ticket_has_job_type', 'job_type_id', 'job_type', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ticket_has_job_type');
    }
}
