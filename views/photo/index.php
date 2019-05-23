<?php

use yii\helpers\Html;
use mihaildev\elfinder\ElFinder;

$title = 'Фотографии';
$this->title = Yii::$app->name . ' | ' . $title;
?>

<div class="photo-index">
    <h1><?= Html::encode($title) ?></h1>
    
    <p><?= Html::a('На главную', ['/'], ['class' => 'btn btn-info']) ?></p>
    

    <?= ElFinder::widget([
        'language'         => 'ru',
        'controller'       => 'elfinder',
        'filter'           => 'image',
    ]); ?>
</div>