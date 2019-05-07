<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m190110_163849_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'role' => "enum('administrator', 'manager', 'worker', 'brigadier')",
            'username' => $this->string(45)->notNull()->unique(),
            'password' => $this->string(255)->notNull(),
            'email' => $this->string(50)->notNull()->unique(),
            'firstname' => $this->string(100)->notNull(),
            'midname' => $this->string(100)->defaultValue(null),
            'lastname' => $this->string(100)->notNull(),
            'avatar' => $this->string(50)->defaultValue(null),
            'active' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);
        
        $this->insert('user', [
            'role' => 'administrator',
            'username' => 'administrator',
            'password' => '$2y$13$B/Wo7QcI4iSCvjEjgIa3h.CH5ZbSizBNkLR2sHLQRi6I9ykYCo9/a',
            'email' => 'admin@care-assist.ru',
            'firstname' => 'admin',
            'lastname' => 'admin'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
