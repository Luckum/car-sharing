<?php

namespace app\modules\customer\controllers;

use Yii;
use app\controllers\SiteController;
use app\modules\customer\models\OperatorLoginForm;
use app\modules\api\models\Car;
use app\modules\api\models\Geo;
use yii\data\ArrayDataProvider;
use yii\web\Response;

use app\models\User;

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
        
        if (!$cars_model->getData()) {
            $cars_model = [];
            Yii::$app->session->setFlash('error', 'Не настроен доступ к АПИ компании ' . Yii::$app->user->identity->customerHasUser->customer->title . '!');
        }
        
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
    
    public function actionList()
    {
        $cars_model = new Car();
        $cars_model->getData();
        
        /*$cities = [];
        foreach ($cars_model->cars as $car) {
            $location = new Geo;
            $location->lon = $car->lon;
            $location->lat = $car->lat;
            $location->getData();
            $cities[] = $location->city;
        }
        
        print_r($cities);*/
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $cars_model->cars
        ]);
        
        return $this->render('list', [
            //'cars_model' => $cars_model,
            'dataProvider' => $dataProvider
        ]);
    }
    
    public function actionAccess()
    {
        $model = User::findOne(Yii::$app->user->identity->id);
        $model->scenario = 'update';
        
        if ($model->load(Yii::$app->request->post())) {
            if (!empty(Yii::$app->request->post('new_password'))) {
                if (Yii::$app->request->post('new_password') == Yii::$app->request->post('new_password_repeat')) {
                    $model->password = Yii::$app->getSecurity()->generatePasswordHash(Yii::$app->request->post('new_password'));
                } else {
                    Yii::$app->session->setFlash('error', 'Пароли не совпадают');
                }
            }
            if ($model->save()) {
                return $this->redirect(['/']);
            }
        }
        
        $model->password = null;
        
        return $this->render('access', [
            'model' => $model,
        ]);
    }
    
    public function actionPasswordReset()
    {
        $this->viewPath = '@app/views/site';
        return parent::actionPasswordReset();
    }
    
    public function actionGetCoordinates()
    {
        $cars_model = new Car();
        $cars_model->getData();
        $ret = [];
        
        foreach ($cars_model->cars as $car) {
            $ret[$car->car_id] = [
                'lat' => $car->lat,
                'lon' => $car->lon
            ];
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $ret;
    }
}
