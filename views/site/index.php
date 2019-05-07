<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use yii\widgets\Pjax;

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="site-index-buttons">
        <?= Html::a('Собрать бригаду', ['/brigade/create'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Добавить рабочего', ['/user/create', 'role' => User::ROLE_WORKER], ['class' => 'btn btn-default']) ?>
        <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN): ?>
            <?= Html::a('Добавить менеджера', ['/user/create', 'role' => User::ROLE_MANAGER], ['class' => 'btn btn-default']) ?>
        <?php endif; ?>
        <?= Html::a('Список бригад', ['/brigade/index'], ['class' => 'btn btn-default']) ?>
    </div>
    <?php Pjax::begin(['id' => 'site-index-pjax']); ?>
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
                        'contentOptions' => ['width' => '13%'],
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
    <?php Pjax::end(); ?>
</div>
