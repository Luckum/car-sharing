<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JobType */

$title = 'Настройки: добавить вид работ';
$this->title = Yii::$app->name . ' | ' . $title;
?>
<div class="job-type-create">

    <h1><?= Html::encode($title) ?></h1>
    
    <p><?= Html::a('Назад', ['/settings/job-type'], ['class' => 'btn btn-info']) ?></p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
