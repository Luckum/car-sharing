<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$userModel = new User;
if ($role == User::ROLE_MANAGER) {
    $roles_list = [
        User::ROLE_MANAGER => $userModel->getRoleRuByRole(User::ROLE_MANAGER)
    ];
} else if ($role == User::ROLE_WORKER || $role == User::ROLE_BRIGADIER) {
    $roles_list = [
        User::ROLE_WORKER => $userModel->getRoleRuByRole(User::ROLE_WORKER),
        User::ROLE_BRIGADIER => $userModel->getRoleRuByRole(User::ROLE_BRIGADIER)
    ];
}
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'role')->dropDownList($roles_list, []) ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'midname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

    <?php if (empty($model->avatar)): ?>
        <?= $form->field($model, 'avatar_file')->fileInput() ?>
    <?php else: ?>
        <?= $form->field($model, 'avatar_file')->fileInput() ?>
        <?= Html::img('/uploads/avatars/' . $model->avatar) ?>
        <div>
            <?= Html::checkbox('delete_avatar', false, ['label' => 'Удалить аватар?']) ?>
        </div>
    <?php endif; ?>

    <?php if (!$model->isNewRecord): ?>
        <?= $form->field($model_profile, 'phone')->widget(MaskedInput::className(), [
            'mask' => '+7 (999)-999-9999',
        ]) ?>

        <?= $form->field($model_profile, 'city')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model_profile, 'address_line')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model_profile, 'whatsapp_account')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model_profile, 'telegram_account')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model_profile, 'comment')->textarea(['rows' => 6]) ?>
    <?php endif; ?>
    
    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
