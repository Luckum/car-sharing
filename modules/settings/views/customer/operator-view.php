<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

$title = 'Настройки: компания каршеринга - ' . $model->title . ' - оператор - ' . $model_user->fullName;
$this->title = Yii::$app->name . ' | ' . $title;

?>

<div class="user-view">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('Назад', ['operator', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Редактировать', ['operator-update', 'id' => $model->id, 'oid' => $model_user->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['operator-delete', 'id' => $model->id, 'oid' => $model_user->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
    <?= DetailView::widget([
        'model' => $model_user,
        'formatter' => [
            'class' => 'yii\i18n\Formatter', 
            'dateFormat' => 'd MMMM y, HH:mm:ss',
            'locale' => 'ru'
        ],
        'attributes' => [
            'username',
            [
                'attribute' => 'active',
                'value' => $model_user->active == User::STATUS_ACTIVE ? "<i class='fa fa-check' style='color: green;'></i>" : "<i class='fa fa-times' style='color: red;'></i>",
                'format' => 'raw'
            ],
            [
                'attribute' => 'fullNameColumnHtmlFormatted',
                'value' => $model_user->fullName
            ],
            'email:email',
            'created_at:date',
        ],
    ]) ?>
</div>