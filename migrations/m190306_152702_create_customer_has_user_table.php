<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_has_user}}`.
 */
class m190306_152702_create_customer_has_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%customer_has_user}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull()
        ]);
        
        $this->addForeignKey('customer_has_user_ibfk_user', 'customer_has_user', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('customer_has_user_ibfk_customer', 'customer_has_user', 'customer_id', 'customer', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%customer_has_user}}');
    }
}
