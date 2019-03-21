<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Настройки: виды работ';
$this->title = Yii::$app->name . ' | ' . $title;
?>
<div class="job-type-index">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('На главную', ['/'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Добавить вид', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'value',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
