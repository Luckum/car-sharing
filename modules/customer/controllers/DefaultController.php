<?php

namespace app\modules\customer\controllers;

use Yii;
use app\controllers\SiteController;
use app\modules\customer\models\OperatorLoginForm;

/**
 * Default controller for the `customer` module
 */
class DefaultController extends SiteController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new OperatorLoginForm();
        $model->_subdomain = Yii::$app->request->queryParams['subdomain'];
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        $this->viewPath = '@app/views/site';
        return $this->renderPartial('login', [
            'model' => $model,
        ]);
    }
}
