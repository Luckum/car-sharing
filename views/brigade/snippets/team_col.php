<?php

use yii\helpers\Html;

?>

<table>
    <tbody>
        <tr class="tr-top">
            <td>
                <?php foreach ($model->brigadeHasUsers as $user): ?>
                    <?php if ($user->is_master): ?>
                        <?= $user->user->fullName ?>
                        <br />
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php foreach ($model->brigadeHasUsers as $user): ?>
                    <?php if (!$user->is_master): ?>
                        <?= $user->user->fullName ?>
                        <br />
                    <?php endif; ?>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td><?= Html::a('посмотреть карточку', ['/brigade/view', 'id' => $model->id], ['class' => 'btn btn-default']) ?></td>
        </tr>
    </tbody>
</table>