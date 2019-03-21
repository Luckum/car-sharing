<?php

namespace app\modules\customer\models;

use app\models\LoginForm;
use app\models\Customer;
use app\models\User;

class OperatorLoginForm extends LoginForm
{
    public $_subdomain;
    private $_user = false;
    
    public function getUser()
    {
        $customer = Customer::find()->where(['subdomain' => $this->_subdomain])->one();
        if (!$customer || !$customer->customerHasUsers) {
            return false;
        }
        
        if ($this->_user === false) {
            $this->_user = User::findOperatorByUsername($this->username, $customer->id);
        }

        return $this->_user;
    }
}