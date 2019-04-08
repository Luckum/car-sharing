<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use softark\duallistbox\DualListbox;

use app\models\Area;
use app\models\User;
use app\models\BrigadeHasUser;

/* @var $this yii\web\View */
/* @var $model app\models\Brigade */
/* @var $form yii\widgets\ActiveForm */

$areas = ArrayHelper::map(Area::find()->all(), 'id', 'titleWithZip');
$masters = ArrayHelper::map(User::getFreeMasters(), 'id', 'fullName');
$workers = ArrayHelper::map(User::getFreeWorkers(), 'id', 'fullName');

$selected = null;
$workers_selected = $areas_selected = [];
if (!$model->isNewRecord) {
    foreach ($model->brigadeHasUsers as $user) {
        if ($user->is_master) {
            $masters = ArrayHelper::merge([$user->user_id => $user->user->fullName], $masters);
            $selected = $user->user_id;
        } else {
            $workers = ArrayHelper::merge($workers, [$user->user_id => $user->user->fullName]);
            $workers_selected = ArrayHelper::merge($workers_selected, [$user->user_id => ['selected' => true]]);
        }
    }
    foreach ($model->brigadeHasAreas as $area) {
        $areas_selected = ArrayHelper::merge($areas_selected, [$area->area_id => ['selected' => true]]);
    }
}

?>

<div class="brigade-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model_brigade_has_area, 'area_id')->widget(DualListbox::className(), [
        'items' => $areas,
        'options' => [
            'multiple' => true,
            'size' => 20,
            'options' => $areas_selected,
        ],
        'clientOptions' => [
            'moveOnSelect' => true,
            'selectedListLabel' => 'Выбранные квадранты',
            'nonSelectedListLabel' => 'Доступные квадранты',
            'infoTextEmpty' => 'Список пуст',
            'infoText' => 'Всего {0}',
            'infoTextFiltered' => '<span class="label label-warning">Отфильтровано</span> {0} из {1}',
            'filterPlaceHolder' => 'Фильтр',
            'moveAllLabel' => 'Переместить всех',
            'removeAllLabel' => 'Удалить всех',
            'filterTextClear' => 'Показать всех',
        ],
    ]); ?>
    
    <?= $form->field($model_brigade_has_user, 'is_master')->dropDownList($masters, ['options' => [$selected => ['selected' => true]]]) ?>
    
    <?= $form->field($model_brigade_has_user, 'user_id')->widget(DualListbox::className(), [
        'items' => $workers,
        'options' => [
            'multiple' => true,
            'size' => 20,
            'options' => $workers_selected,
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
