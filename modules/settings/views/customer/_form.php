<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\Customer;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subdomain')->textInput(['maxlength' => true])->label(Customer::instance()->getAttributeLabel('subdomain') . ' для ' . Yii::$app->request->hostName . ' (без указания основного домена)') ?>
    
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'phone')->widget(MaskedInput::className(), [
        'mask' => '+7 (999)-999-9999',
    ]) ?>

    <?= $form->field($model, 'site_url')->textInput(['maxlength' => true, 'placeholder' => 'http://example.com']) ?>

    <?= $form->field($model, 'address_line')->textInput(['maxlength' => true]) ?>

    <?php if (empty($model->logo)): ?>
        <?= $form->field($model, 'logo_file')->fileInput() ?>
    <?php else: ?>
        <?= $form->field($model, 'logo_file')->fileInput() ?>
        <?= Html::img('/uploads/logos/' . $model->logo) ?>
        <div>
            <?= Html::checkbox('delete_logo', false, ['label' => 'Удалить логотип?']) ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
