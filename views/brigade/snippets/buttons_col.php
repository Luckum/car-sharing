<?php 

use yii\helpers\Html;

?>
<table>
    <tbody>
        <tr class="tr-bottom">
            <td></td>
        </tr>
        <tr>
            <td><?= Html::a('снять с линии', ['/brigade/', 'id' => $model->id], ['class' => 'btn btn-default']) ?></td>
        </tr>
    </tbody>
</table>