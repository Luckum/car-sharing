<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$title = 'Настройки: компания каршеринга - ' . $model->title . ' - список операторов';
$this->title = Yii::$app->name . ' | ' . $title;
$customer_id = $model->id;
?>

<div class="customer-index">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('Назад', ['/settings/customer'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Добавить оператора', ['operator-create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'email:email',
            [
                'attribute' => 'fullNameColumnHtmlFormatted',
                'content' => function ($data) {
                    return $data->fullName;
                }
            ],
            //'firstname',
            //'midname',
            //'lastname',
            //'avatar',
            //'active',
            //'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) use ($customer_id) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['operator-view', 'id' => $customer_id, 'oid' => $model->id], ['title' => 'Просмотр']);
                    },
                    'update' => function ($url, $model, $key) use ($customer_id) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['operator-update', 'id' => $customer_id, 'oid' => $model->id], ['title' => 'Редактировать']);
                    },
                    'delete' => function ($url, $model, $key) use ($customer_id) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['operator-delete', 'id' => $customer_id, 'oid' => $model->id], [
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                                'method' => 'post',
                            ]
                        ]);
                    } 
                ],
            ],
        ],
    ]); ?>
</div>