<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customers}}`.
 */
class m240413_003324_create_customers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customers}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull(),
            'cpf' => $this->string(14)->notNull()->unique(),
            'cep' => $this->string(8)->notNull(),
            'logradouro' => $this->string()->notNull(),
            'numero' => $this->string()->notNull(),
            'cidade' => $this->string()->notNull(),
            'estado' => $this->string(2)->notNull(),
            'complemento' => $this->string(),
            'foto' => $this->string(),
            'sexo' => $this->char(1)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%customers}}');
    }
}