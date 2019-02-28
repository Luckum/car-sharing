<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Настройки: квадранты';
$this->title = Yii::$app->name . ' | ' . $title;

?>

<div class="area-index">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('На главную', ['/'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Добавить квадрант', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'zip',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
