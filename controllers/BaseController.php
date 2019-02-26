<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

class BaseController extends Controller
{
    public $publicActions = [
        'login',
    ];
    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) return true;
        if (!in_array($action->id, $this->publicActions)) {
            return $this->redirect(Yii::$app->user->loginUrl)->send();
        }
        return true;
    }
}