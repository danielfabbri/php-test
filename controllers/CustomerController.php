<?php

namespace app\controllers;

use Yii;
use app\models\Customer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CustomerController extends Controller
{
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['delete'],
                ],
            ],
        ];
    }

    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate() {
        $model = new Customer();
        if ($model->load(Yii::$app->request->post())) {
            $model->foto = UploadedFile::getInstance($model, 'foto');
            if ($model->validate()) {
                if ($model->foto) {
                    $uploadPath = Yii::getAlias('@webroot/uploads');
                    $filename = uniqid() . '.' . $model->foto->extension;
                    $filePath = $uploadPath . '/' . $filename;
                    if ($model->foto->saveAs($filePath)) {
                        $model->foto = $filename;
                    } else {
                        Yii::$app->session->setFlash('error', 'Erro ao salvar a foto.');
                        return $this->redirect(['create']);
                    }
                }
                if ($model->save()) {
                    return $this->redirect(['site/customer']);
                } else {
                    Yii::$app->session->setFlash('error', 'Erro ao salvar o cliente.');
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();
    }

    protected function findModel($id) {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}