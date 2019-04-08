<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Brigade */

$title = 'Создание бригады';
$this->title = Yii::$app->name . ' | ' . $title;
?>
<div class="brigade-create">

    <h1><?= Html::encode($title) ?></h1>
    
    <p><?= Html::a('Назад', Yii::$app->request->referrer, ['class' => 'btn btn-info']) ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'model_brigade_has_user' => $model_brigade_has_user,
        'model_brigade_has_area' => $model_brigade_has_area,
    ]) ?>

</div>
