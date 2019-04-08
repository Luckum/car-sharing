<?php

use yii\db\Migration;

/**
 * Handles adding customer_id to table `{{%ticket}}`.
 */
class m190327_164751_add_customer_id_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ticket}}', 'customer_id', $this->integer(11)->notNull());
        
        $this->addForeignKey('ticket_ibfk_customer', 'ticket', 'customer_id', 'customer', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ticket}}', 'customer_id');
    }
}
