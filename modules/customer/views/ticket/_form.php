<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use softark\duallistbox\DualListbox;
use app\models\JobType;

/* @var $this yii\web\View */
/* @var $model app\models\Ticket */
/* @var $form yii\widgets\ActiveForm */

$jobs = ArrayHelper::map(JobType::find()->all(), 'id', 'value');
$jobs_selected = [];
if (!$model->isNewRecord) {
    foreach ($model->ticketHasJobTypes as $job) {
        $jobs_selected = ArrayHelper::merge($jobs_selected, [$job->job_type_id => ['selected' => true]]);
    }
}
?>

<div class="ticket-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'car_id')->widget(Select2::classname(), [
        'data' => $cars_model->modelWithNumber,
        'options' => ['placeholder' => 'Выберите автомобиль'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'disabled' => !$model->isNewRecord
    ]) ?>
    
    <?= $form->field($model, 'status')->dropDownList($model->createStatuses) ?>

    <?= $form->field($model, 'rent_type')->dropDownList($model->rentTypeRu) ?>
    
    <?= $form->field($ticket_has_job_model, 'job_type_id')->widget(DualListbox::className(), [
        'items' => $jobs,
        'options' => [
            'multiple' => true,
            'size' => 20,
            'options' => $jobs_selected,
        ],
        'clientOptions' => [
            'moveOnSelect' => true,
            'selectedListLabel' => 'Выбранные виды работ',
            'nonSelectedListLabel' => 'Доступные виды работ',
            'infoTextEmpty' => 'Список пуст',
            'infoText' => 'Всего {0}',
            'infoTextFiltered' => '<span class="label label-warning">Отфильтровано</span> {0} из {1}',
            'filterPlaceHolder' => 'Фильтр',
            'moveAllLabel' => 'Переместить всех',
            'removeAllLabel' => 'Удалить всех',
            'filterTextClear' => 'Показать всех',
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
