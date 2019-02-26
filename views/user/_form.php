<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$userModel = new User;
if ($role == User::ROLE_MANAGER) {
    $roles_list = [
        User::ROLE_MANAGER => $userModel->getRoleRuByRole(User::ROLE_MANAGER)
    ];
} else if ($role == User::ROLE_WORKER) {
    $roles_list = [
        User::ROLE_WORKER => $userModel->getRoleRuByRole(User::ROLE_WORKER),
        User::ROLE_BRIGADIER => $userModel->getRoleRuByRole(User::ROLE_BRIGADIER)
    ];
}
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'role')->dropDownList($roles_list, []) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
