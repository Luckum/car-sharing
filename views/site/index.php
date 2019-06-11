<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\User;
use yii\widgets\Pjax;

$this->title = Yii::$app->name;

$sort_items = [
    'date' => 'дате добавления',
    'day' => 'кол-ву выполненных заявок за сутки',
    'total' => 'кол-ву выполненных заявок всего',
];

$filter_items = [
    'all' => 'всех пользователей',
    User::ROLE_WORKER => 'только рабочих',
    User::ROLE_BRIGADIER => 'только бригадиров',
];
if (Yii::$app->user->identity->role == User::ROLE_ADMIN) {
    $filter_items[User::ROLE_MANAGER] = 'только менеджеров';
}
?>
<div class="site-index">
    <div class="site-index-buttons">
        <?= Html::a('Собрать бригаду', ['/brigade/create'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Добавить рабочего', ['/user/create', 'role' => User::ROLE_WORKER], ['class' => 'btn btn-default']) ?>
        <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN): ?>
            <?= Html::a('Добавить менеджера', ['/user/create', 'role' => User::ROLE_MANAGER], ['class' => 'btn btn-default']) ?>
        <?php endif; ?>
        <?= Html::a('Список бригад', ['/brigade/index'], ['class' => 'btn btn-default']) ?>
        
        <div class="pull-right">
            <div>
                <?= Html::label('Показать:', 'filter-site-index', ['class' => 'control-label']) ?>
                <?= Html::dropDownList(
                    'filter_site_index',
                    null,
                    $filter_items,
                    [
                        'class' => 'form-control sort-dropdown',
                        'id' => 'filter-site-index',
                        'onchange' => '$.pjax.reload({
                            container: "#site-index-pjax",
                            url: "' .  Url::to(['/']) . '",
                            data: {filter: $(this).val(), sort: $("#sort-site-index").val()},
                            type: "POST"
                        });'
                    ]
                ) ?>
            </div>
            <div>
                <?= Html::label('Сортировать по:', 'sort-site-index', ['class' => 'control-label']) ?>
                <?= Html::dropDownList(
                    'sort_site_index',
                    null,
                    $sort_items,
                    [
                        'class' => 'form-control sort-dropdown',
                        'id' => 'sort-site-index',
                        'onchange' => '$.pjax.reload({
                            container: "#site-index-pjax",
                            url: "' .  Url::to(['/']) . '",
                            data: {sort: $(this).val(), filter: $("#filter-site-index").val()},
                            type: "POST"
                        });'
                    ]
                ) ?>
            </div>
        </div>
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
