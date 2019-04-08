<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JobType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="job-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>
                                                                                                                      
    <?= $form->field($model, 'job_time')->textInput(['maxlength' => true])->label($model->getAttributeLabel('job_time') . ' (x.01 - x.59 для минут)') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
