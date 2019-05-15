<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$title = 'Настройки: компания каршеринга - ' . $model_customer->title . ' - настройка API';
$this->title = Yii::$app->name . ' | ' . $title;
?>

<div class="customer-api">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('Назад', ['/settings/customer'], ['class' => 'btn btn-info']) ?>
    </p>
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'api_url')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'api_url_params')->textInput(['maxlength' => true]) ?>
    
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>