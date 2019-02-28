<?php

namespace app\controllers;

use Yii;
use app\models\Brigade;
use app\models\BrigadeHasUser;
use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BrigadeController implements the CRUD actions for Brigade model.
 */
class BrigadeController extends BaseController
{
    /**
     * {@inheritdoc}
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
     * Lists all Brigade models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Brigade::find(),
            'sort' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Brigade model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Brigade model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Brigade();
        $model_brigade_has_user = new BrigadeHasUser;

        if ($model->load(Yii::$app->request->post())) {
            $model->status = Brigade::STATUS_OFFLINE;
            if ($model->save()) {
                if ($model_brigade_has_user->load(Yii::$app->request->post())) {
                    $users = $model_brigade_has_user->user_id;
                    
                    $model_brigade_has_user->user_id = $model_brigade_has_user->is_master;
                    $model_brigade_has_user->brigade_id = $model->id;
                    $model_brigade_has_user->is_master = 1;
                    if ($model_brigade_has_user->save()) {
                        foreach ($users as $user_id) {
                            $model_brigade_has_user = new BrigadeHasUser;
                            $model_brigade_has_user->user_id = $user_id;
                            $model_brigade_has_user->brigade_id = $model->id;
                            $model_brigade_has_user->save();
                        }
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        Yii::$app->session->setFlash('error', 'Необходимо указать бригадира');
                        $model->delete();
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'model_brigade_has_user' => $model_brigade_has_user,
        ]);
    }

    /**
     * Updates an existing Brigade model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Brigade model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Brigade model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Brigade the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Brigade::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
