<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\UserProfile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-profile-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phone')->widget(MaskedInput::className(), [
        'mask' => '+7 (999)-999-9999',
    ]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address_line')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'whatsapp_account')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telegram_account')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
