<?php 

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    <br>
    <p><strong>Nome:</strong> <?= $user->nome ?></p>
    <p><strong>Email:</strong> <?= $user->email ?></p>
</div>