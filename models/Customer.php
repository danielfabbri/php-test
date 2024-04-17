<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Customer extends ActiveRecord
{
    public $fotoFile;

    public static function tableName() {
        return 'customers';
    }

    public function rules() {
        return [
            [['nome', 'cpf', 'cep', 'logradouro', 'numero', 'cidade', 'estado', 'sexo'], 'required'],
            ['cpf', 'unique'],
            ['cpf', 'string', 'max' => 11],
            [['nome', 'logradouro', 'cidade', 'estado', 'complemento'], 'string', 'max' => 255],
            ['sexo', 'in', 'range' => ['M', 'F']],
            [['foto'], 'file', 'extensions' => ['jpg', 'jpeg', 'png'], 'skipOnEmpty' => true, 'uploadRequired' => 'Por favor, selecione um arquivo para fazer o upload.'],
        ];
    }

    public function savePhoto() {
        if ($this->fotoFile instanceof UploadedFile) {
            $fileName = uniqid() . '.' . $this->fotoFile->extension;
            $filePath = Yii::getAlias('@webroot/uploads/') . $fileName;
            if ($this->fotoFile->saveAs($filePath)) {
                return $fileName;
            }
        }
        return null;
    }
        
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->fotoFile instanceof UploadedFile) {
                $this->foto = $this->savePhoto();
            }
            return true;
        }
        return false;
    }
}