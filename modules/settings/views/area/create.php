<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Area */

$title = 'Настройки: добавить квадрант';
$this->title = Yii::$app->name . ' | ' . $title;
?>
<div class="area-create">

    <h1><?= Html::encode($title) ?></h1>
    
    <p><?= Html::a('Назад', ['/settings/area'], ['class' => 'btn btn-info']) ?></p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
