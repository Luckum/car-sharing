<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\MaskedInput;
use app\models\User;
use app\models\UserProfile;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$title = 'Профиль пользователя: ' . $model->fullName;
$this->title = Yii::$app->name . ' | ' . $title;

?>
<div class="user-view">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('Назад', ['/'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php if (!empty($model->avatar)): ?>
        <p>
            <?= Html::img(['/uploads/avatars/' . $model->avatar]) ?>
        </p>
    <?php endif; ?>
    <?= DetailView::widget([
        'model' => $model,
        'formatter' => [
            'class' => 'yii\i18n\Formatter', 
            'dateFormat' => 'd MMMM y, HH:mm:ss',
            'locale' => 'ru'
        ],
        'attributes' => [
            'username',
            [
                'attribute' => 'active',
                'value' => $model->active == User::STATUS_ACTIVE ? "<i class='fa fa-check' style='color: green;'></i>" : "<i class='fa fa-times' style='color: red;'></i>",
                'format' => 'raw'
            ],
            [
                'attribute' => 'role',
                'value' => $model->roleRu
            ],
            [
                'attribute' => 'fullNameColumnHtmlFormatted',
                'value' => $model->fullName
            ],
            'email:email',
            [
                'attribute' => UserProfile::instance()->getAttributeLabel('phone'),
                'value' => isset($model->userProfile) ? preg_replace("/^(\d{1})(\d{3})(\d{3})(\d{4})$/", "+$1 ($2)-$3-$4", $model->userProfile->phone) : '',
            ],
            [
                'attribute' => UserProfile::instance()->getAttributeLabel('city'),
                'value' => isset($model->userProfile) ? $model->userProfile->city : '',
            ],
            [
                'attribute' => UserProfile::instance()->getAttributeLabel('address_line'),
                'value' => isset($model->userProfile) ? $model->userProfile->address_line : '',
            ],
            [
                'attribute' => UserProfile::instance()->getAttributeLabel('whatsapp_account'),
                'value' => isset($model->userProfile) ? $model->userProfile->whatsapp_account : '',
            ],
            [
                'attribute' => UserProfile::instance()->getAttributeLabel('telegram_account'),
                'value' => isset($model->userProfile) ? $model->userProfile->telegram_account : '',
            ],
            [
                'attribute' => UserProfile::instance()->getAttributeLabel('comment'),
                'value' => isset($model->userProfile) ? $model->userProfile->comment : '',
                'format' => 'ntext'
            ],
            
            'created_at:date',
            
        ],
    ]) ?>

</div>
