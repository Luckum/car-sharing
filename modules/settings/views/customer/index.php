<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Настройки: компании каршеринга';
$this->title = Yii::$app->name . ' | ' . $title;
?>
<div class="customer-index">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('На главную', ['/'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Добавить компанию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'email:email',
            [
                'attribute' => 'phone',
                'content' => function ($data) {
                    return preg_replace("/^(\d{1})(\d{3})(\d{3})(\d{4})$/", "+$1 ($2)-$3-$4", $data->phone);
                }
            ],
            [
                'attribute' => 'subdomain',
                'content' => function ($data) {
                    return $data->subdomain . '.' . Yii::$app->request->hostName;
                }
            ],
            [
                'attribute' => 'site_url',
                'format' => 'raw',
                'content' => function ($data) {
                    return Html::a($data->site_url, Url::to($data->site_url, true), ['target' => '_blank']);
                }
            ],
            //'address_line',
            //'logo',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
