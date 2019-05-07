<?php

namespace app\modules\customer\controllers;

use Yii;
use app\controllers\SiteController;
use app\modules\customer\models\OperatorLoginForm;
use app\modules\api\models\Car;

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
        $cars_model = new Car();
        $cars_model->getData();
        
        return $this->render('index', [
            'cars_model' => $cars_model
        ]);
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
