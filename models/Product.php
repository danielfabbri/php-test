<?php

namespace app\models;

use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    public static function tableName()
    {
        return 'products'; // Nome da tabela no banco de dados
    }

    public function rules()
    {
        return [
            [['nome', 'preco', 'cliente_id'], 'required'],
            [['preco'], 'number'],
            [['nome', 'foto'], 'string', 'max' => 255],
            [['cliente_id'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'preco' => 'Preço',
            'cliente_id' => 'Cliente',
            'foto' => 'Foto',
        ];
    }
}