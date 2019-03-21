<?php

use yii\db\Migration;

/**
 * Handles adding car_id to table `{{%ticket}}`.
 */
class m190315_170832_add_car_id_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ticket}}', 'car_id', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ticket}}', 'car_id');
    }
}
