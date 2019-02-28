<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use softark\duallistbox\DualListbox;

use app\models\Area;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Brigade */
/* @var $form yii\widgets\ActiveForm */

$areas = ArrayHelper::map(Area::find()->all(), 'id', 'titleWithZip');
$masters = ArrayHelper::map(User::getFreeMasters(), 'id', 'fullName');
$workers = ArrayHelper::map(User::getFreeWorkers(), 'id', 'fullName');

?>

<div class="brigade-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area_id')->dropDownList($areas) ?>
    
    <?= $form->field($model_brigade_has_user, 'is_master')->dropDownList($masters) ?>
    
    <?= $form->field($model_brigade_has_user, 'user_id')->widget(DualListbox::className(), [
        'items' => $workers,
        'options' => [
            'multiple' => true,
            'size' => 20,
        ],
        'clientOptions' => [
            'moveOnSelect' => true,
            'selectedListLabel' => 'Выбранные рабочие',
            'nonSelectedListLabel' => 'Доступные рабочие',
            'infoTextEmpty' => 'Список пуст',
            'infoText' => 'Всего {0}',
            'infoTextFiltered' => '<span class="label label-warning">Отфильтровано</span> {0} из {1}',
            'filterPlaceHolder' => 'Фильтр',
            'moveAllLabel' => 'Переместить всех',
            'removeAllLabel' => 'Удалить всех',
            'filterTextClear' => 'Показать всех',
        ],
    ]); ?>
    
    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
