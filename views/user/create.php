<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::$app->name . ' | Добавление пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= Html::a('Назад', ['/'], ['class' => 'btn btn-info']) ?>
    <h1><?= Html::encode('Добавление пользователя') ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'role' => $role
    ]) ?>

</div>
