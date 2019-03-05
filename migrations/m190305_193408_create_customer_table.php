<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer}}`.
 */
class m190305_193408_create_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'subdomain' => $this->string(20)->notNull(),
            'email' => $this->string(50)->notNull(),
            'phone' => $this->string(17)->notNull(),
            'site_url' => $this->string(50)->defaultValue(null),
            'address_line' => $this->string(255)->defaultValue(null),
            'logo' => $this->string(50)->defaultValue(null),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%customer}}');
    }
}
