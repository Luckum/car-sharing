<?php

namespace app\controllers;

use Yii;
use app\models\UserProfile;
use app\models\User;
use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserProfileController implements the CRUD actions for UserProfile model.
 */
class UserProfileController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all UserProfile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => UserProfile::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new UserProfile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new UserProfile();
        $model_user = User::findOne($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id  = $id;
            $model->phone = preg_replace('/\D+/', '', $model->phone);
            if ($model->save()) {
                return $this->redirect(['/']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'model_user' => $model_user
        ]);
    }
}
