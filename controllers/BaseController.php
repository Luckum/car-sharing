<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\widgets\Alert;

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
    
    public function afterAction($action, $result)
    {
        if (Yii::$app->request->isAjax && !empty(Yii::$app->session->getAllFlashes())) {
            echo Alert::widget();
        }
        return parent::afterAction($action, $result);
    }
}