<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Brigade;

/* @var $this yii\web\View */
/* @var $model app\models\Brigade */

$title = 'Карточка бригады: ' . $model->title;
$this->title = Yii::$app->name . ' | ' . $title;
\yii\web\YiiAsset::register($this);
?>
<div class="brigade-view">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Ds уверены, что хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

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
            [
                'attribute' => 'status',
                'value' => $model->statusRu,
            ],
            [
                'attribute' => 'active',
                'value' => $model->active == Brigade::STATUS_ACTIVE ? "<i class='fa fa-check' style='color: green;'></i>" : "<i class='fa fa-times' style='color: red;'></i>",
                'format' => 'raw'
            ],
            [
                'label' => 'Квадрант',
                'format' => 'raw',
                'value' => function ($data) {
                    $ret = '';
                    foreach ($data->brigadeHasAreas as $row) {
                        $ret .= $row->area->titleWithZip . '<br />';
                    }
                    return $ret;
                }
            ],
            'created_at:date',
            [
                'label' => 'Состав бригады',
                'value' => function ($data) {
                    $ret = '';
                    foreach ($data->brigadeHasUsers as $row) {
                        $ret .= $row->user->fullName . ' (' . $row->user->roleRu . ')' . '<br />';
                    }
                    return $ret;
                },
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>
