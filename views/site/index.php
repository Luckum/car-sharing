<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;

//'htmlFormattedInformation:raw',
        //getHtmlFormattedInformation()
        /*return Yii::$app->view->renderFile('@app/modules/site/views/order/snippets/information.php', [
            'model' => $this,
        ]);*/
//$.pjax.reload({container:"#fund-deduction-pjax"});
$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="site-index-buttons">
        <?= Html::a('Собрать бригаду', 'javascript:void();', ['class' => 'btn btn-default']) ?>
        <?= Html::a('Добавить рабочего', ['/user/create', 'role' => User::ROLE_WORKER], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Добавить менеджера', ['/user/create', 'role' => User::ROLE_MANAGER], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Список бригад', 'javascript:void();', ['class' => 'btn btn-default']) ?>
    </div>
    <div class="site-index-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-striped table-fixed'
            ],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => '№ п/п',
                    'headerOptions' => ['width' => '5%'],
                    'contentOptions' => ['width' => '5%']
                ],
                [
                    'attribute' => 'fullNameColumnHtmlFormatted',
                    'format' => 'raw',
                    'headerOptions' => ['width' => '15%'],
                    'contentOptions' => ['width' => '15%']
                ],
                [
                    'attribute' => 'roleColumnHtmlFormatted',
                    'format' => 'raw',
                    'headerOptions' => ['width' => '9%'],
                    'contentOptions' => ['width' => '9%']
                ],
                [
                    'attribute' => 'brigadeColumnHtmlFormatted',
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
                    'format' => 'raw',
                    'header' => 'Фото',
                    'headerOptions' => ['width' => '20%'],
                    'contentOptions' => ['width' => '20%'],
                    'value' => function ($data) {
                        return !empty($data->avatar) ? Html::img('/uploads/avatars/' . $data->avatar) : '';
                    }
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
