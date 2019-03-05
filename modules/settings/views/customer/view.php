<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */

$title = 'Настройки: rомпания каршеринга: ' . $model->title;
$this->title = Yii::$app->name . ' | ' . $title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-view">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('Назад', ['/settings/customer'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удлаить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
    <?php if (!empty($model->logo)): ?>
        <p>
            <?= Html::img(['/uploads/logos/' . $model->logo]) ?>
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
            'id',
            'title',
            'email:email',
            [
                'attribute' => 'phone',
                'value' => preg_replace("/^(\d{1})(\d{3})(\d{3})(\d{4})$/", "+$1 ($2)-$3-$4", $model->phone),
            ],
            [
                'attribute' => 'site_url',
                'format' => 'raw',
                'value' => Html::a($model->site_url, Url::to($model->site_url, true), ['target' => '_blank'])
            ],
            'address_line',
            'created_at:date',
        ],
    ]) ?>

</div>
