<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m240413_004010_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'senha' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}