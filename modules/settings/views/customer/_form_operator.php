<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="operator-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?php if ($model_user->isNewRecord): ?>
        <?= $form->field($model_user, 'username')->textInput(['maxlength' => true]) ?>
    
        <?= $form->field($model_user, 'password')->passwordInput(['maxlength' => true]) ?>
    <?php endif; ?>
    
    <?= $form->field($model_user, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_user, 'firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_user, 'midname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_user, 'lastname')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model_user, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>