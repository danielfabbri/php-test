<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class DbTestController extends Controller
{

    public function actionIndex()
    {
        $db = Yii::$app->db;
        try {
            $result = $db->createCommand('SELECT 1')->queryOne();
            $dbStatus = "Conexão com o MySQL está funcionando corretamente!";
        } catch (\Exception $e) {
            $dbStatus = "Erro ao conectar ao MySQL: " . $e->getMessage();
        }
        return $this->render('index', [
            'dbStatus' => $dbStatus,
        ]);
    }
}