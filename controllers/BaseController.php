<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use app\widgets\Alert;
use app\models\User;

class BaseController extends Controller
{
    public $publicActions = [
        'login',
    ];
    public $brigadierActions = [
        'basic' => [
            'brigade' => [
                'set-pause',
                'set-online',
                'set-offline',
                'view'
            ],
            'ticket' => [
                'index',
                'view',
                'reject',
                'accept',
                'close',
                'photo-upload',
            ],
            'site' => [
                'logout',
            ]
        ]
    ];
    public $managerActions = [
        'basic' => [
            'ticket' => [
                'index',
                'view',
                'attach'
            ],
            'site' => [
                'logout',
                'index'
            ],
            'brigade' => [
                'index',
                'view',
                'create',
                'update',
                'delete',
                'set-offline',
                'set-online',
                'set-pause',
                'set-brigade',
            ],
            'user' => [
                'view',
                'create',
                'update',
                'delete',
                'toggle-active',
                'access'
            ],
            'user-profile' => [
                'create'
            ],
            'photo' => [
                'index'
            ],
        ]
    ];
    public $workerActions = [
        'basic' => [
            'ticket' => [
                'index',
                'view',
            ],
            'site' => [
                'logout',
            ],
            'brigade' => [
                'view',
            ]
        ]
    ];
    
    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->role == User::ROLE_BRIGADIER) {
                if (isset($this->brigadierActions[$this->module->id][$this->id]) && in_array($action->id, $this->brigadierActions[$this->module->id][$this->id])) {
                    return true;
                } else {
                    return $this->redirect(['/ticket/index'])->send();
                }
            }
            if (Yii::$app->user->identity->role == User::ROLE_MANAGER) {
                if (isset($this->managerActions[$this->module->id][$this->id]) && in_array($action->id, $this->managerActions[$this->module->id][$this->id])) {
                    return true;
                } else {
                    return $this->redirect(['/'])->send();
                }
            }
            if (Yii::$app->user->identity->role == User::ROLE_WORKER) {
                if (isset($this->workerActions[$this->module->id][$this->id]) && in_array($action->id, $this->workerActions[$this->module->id][$this->id])) {
                    return true;
                } else {
                    return $this->redirect(['/ticket/index'])->send();
                }
            }
            return true;
        }
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