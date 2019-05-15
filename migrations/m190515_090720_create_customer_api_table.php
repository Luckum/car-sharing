<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_has_api}}`.
 */
class m190515_090720_create_customer_api_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        
        $this->createTable('{{%customer_api}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(11)->notNull(),
            'api_url' => $this->string(150)->notNull(),
            'api_url_params' => $this->string(150)->defaultValue(null),
        ], $tableOptions);
        
        $this->addForeignKey('customer_api_ibfk_customer', 'customer_api', 'customer_id', 'customer', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%customer_api}}');
    }
}
