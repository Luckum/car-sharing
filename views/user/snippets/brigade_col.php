<?php

use app\models\User;

?>

<?php if ($model->role != User::ROLE_MANAGER): ?>
    <table>
        <tbody>
            <tr class="tr-top">
                <td><?= isset($model->brigadeHasUser->brigade->title) ? $model->brigadeHasUser->brigade->title : 'Нет привязки' ?></td>
            </tr>
            <tr>
                <td><span>Изменить бригаду:</span></td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>