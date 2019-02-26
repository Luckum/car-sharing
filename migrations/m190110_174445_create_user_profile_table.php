<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_profile`.
 */
class m190110_174445_create_user_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('user_profile', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'phone' => $this->string(17)->notNull(),
            'city' => $this->string(45)->defaultValue(null),
            'comment' => $this->text()->defaultValue(null),
            'address_line' => $this->string(255)->defaultValue(null),
            'whatsapp_account' => $this->string(45)->defaultValue(null),
            'telegram_account' => $this->string(45)->defaultValue(null),
        ], $tableOptions);
        
        $this->addForeignKey('user_profile_ibfk_user', 'user_profile', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_profile');
    }
}
