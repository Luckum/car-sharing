<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ticket */

$title = 'Создать заявку';
$this->title = Yii::$app->name . ' | ' . $title;
?>
<div class="ticket-create">

    <h1><?= Html::encode($title) ?></h1>
    
    <p><?= Html::a('Назад', Yii::$app->request->referrer, ['class' => 'btn btn-info']) ?></p>

    <?= $this->render('_form', [
        'model' => $model,
        'cars_model' => $cars_model,
        'ticket_has_job_model' => $ticket_has_job_model,
        'car_id' => $car_id,
    ]) ?>

</div>
