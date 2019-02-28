<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Список бригад';
$this->title = Yii::$app->name . ' | ' . $title;
?>
<div class="brigade-index">

    <div class="brigade-index-buttons">
        <?= Html::a('Все', 'javascript:void(0)', ['class' => 'btn btn-default']) ?>
        <?= Html::a('На линии', 'javascript:void(0)', ['class' => 'btn btn-default']) ?>
        <?= Html::a('Простой', 'javascript:void(0)', ['class' => 'btn btn-default']) ?>
        <?= Html::a('Офлайн', 'javascript:void(0)', ['class' => 'btn btn-default']) ?>
        
        <?= Html::a('Собрать бригаду', ['/brigade/create'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('На главную', ['/'], ['class' => 'btn btn-info']) ?>
    </div>
    <div class="brigade-index-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-striped table-fixed'
            ],
            'columns' => [
                [
                    'attribute' => 'title',
                    'headerOptions' => ['width' => '5%'],
                    'contentOptions' => ['width' => '5%']
                ],
                [
                    'attribute' => 'teamColumnHtmlFormatted',
                    'format' => 'raw',
                    'headerOptions' => ['width' => '15%'],
                    'contentOptions' => ['width' => '15%']
                ],
                [
                    'attribute' => 'statusColumnHtmlFormatted',
                    'format' => 'raw',
                    'headerOptions' => ['width' => '9%'],
                    'contentOptions' => ['width' => '9%']
                ],
                [
                    'attribute' => 'areaColumnHtmlFormatted',
                    'format' => 'raw',
                    'headerOptions' => ['width' => '13%'],
                    'contentOptions' => ['width' => '13%']
                ],
                [
                    'attribute' => 'ticketsColumnHtmlFormatted',
                    'format' => 'raw',
                    'headerOptions' => ['width' => '20%'],
                    'contentOptions' => ['width' => '20%']
                ],
                [
                    'attribute' => 'currentTicketColumnHtmlFormatted',
                    'format' => 'raw',
                    'headerOptions' => ['width' => '20%'],
                    'contentOptions' => ['width' => '20%']
                ],
                [
                    'attribute' => 'buttonsColumnHtmlFormatted',
                    'format' => 'raw',
                    'label' => 'Действия',
                    'headerOptions' => ['width' => '18%'],
                    'contentOptions' => ['width' => '18%']
                ]
                
            ],
        ]); ?>
    </div>
</div>
