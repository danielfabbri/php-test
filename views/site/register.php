<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>

    <?= $form->field($model, 'nome')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'senha')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>