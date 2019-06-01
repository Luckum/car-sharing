<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['active' => User::STATUS_ACTIVE],
                'message' => 'Нет пользователя с таким email.'
            ],
        ];
    }
}
