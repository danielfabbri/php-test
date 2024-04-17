<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    public $auth_key;
    public $access_token;


    public static function tableName()
    {
        return 'users'; // Nome da tabela no banco de dados
    }

    public function rules()
    {
        return [
            [['nome', 'senha'], 'required'],
            [['nome', 'senha', 'auth_key', 'access_token'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($nome)
    {
        return static::findOne(['nome' => $nome]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($senha)
    {
        return Yii::$app->security->validatePassword($senha, $this->senha);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
                $this->access_token = Yii::$app->security->generateRandomString();
            }
            if ($this->senha !== '') { // Usando a propriedade `senha`
                $this->senha = Yii::$app->security->generatePasswordHash($this->senha);
            }
            return true;
        }
        return false;
    }
}