<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Area;

?>
<table>
    <tbody>
        <tr class="tr-top">
            <td>
                <?= $model->completedTicketsForDay ?>
                &nbsp;/&nbsp;
                <?= $model->completedTotal ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= Html::dropDownList(
                    'area',
                    $model->area_id,
                    ArrayHelper::map(Area::find()->all(), 'id', 'title')
                )?>
            </td>
        </tr>
    </tbody>
</table>