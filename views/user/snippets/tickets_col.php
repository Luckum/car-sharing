<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Brigade;

?>
<table>
    <tbody>
        <tr class="tr-top">
            <td>
                <?= isset($model->brigadeHasUser->brigade) ? $model->brigadeHasUser->brigade->completedTicketsForDay : 0 ?>
                &nbsp;/&nbsp;
                <?= isset($model->brigadeHasUser->brigade) ? $model->brigadeHasUser->brigade->completedTotal : 0 ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= Html::dropDownList(
                    'brigade',
                    (isset($model->brigadeHasUser->brigade->id) ? $model->brigadeHasUser->brigade->id : null),
                    ArrayHelper::map(Brigade::find()->all(), 'id', 'title')
                )?>
            </td>
        </tr>
    </tbody>
</table>