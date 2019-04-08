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
            <td></td>
        </tr>
    </tbody>
</table>