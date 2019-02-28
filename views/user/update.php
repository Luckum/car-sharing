<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$title = 'Редактирование пользователя: ' . $model->fullName;
$this->title = Yii::$app->name . ' | ' . $title;

?>
<div class="user-update">

    <h1><?= Html::encode($title) ?></h1>
    
    <p><?= Html::a('Назад', ['view', 'id' => $model->id], ['class' => 'btn btn-info']) ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'role' => $role,
        'model_profile' => $model_profile,
    ]) ?>

</div>
