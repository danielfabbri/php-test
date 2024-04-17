<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m240413_003624_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull(),
            'preco' => $this->decimal(10, 2)->notNull(),
            'cliente_id' => $this->integer()->notNull(),
            'foto' => $this->string(),
        ]);

        $this->addForeignKey(
            'fk-products-cliente_id',
            'products',
            'cliente_id',
            'customers',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-products-cliente_id', 'products');
        $this->dropTable('{{%products}}');
    }
}