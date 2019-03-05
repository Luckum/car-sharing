<?php

namespace app\controllers;

use Yii;
use app\models\Brigade;
use app\models\BrigadeHasUser;
use app\models\User;
use app\models\Ticket;
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
    public function actionIndex($status = '')
    {
        $query = Brigade::find();
        if (!empty($status)) {
            $query->where(['brigade.status' => $status]);
        }
        
        $sort_order = Yii::$app->request->post('sort');
        switch ($sort_order) {
            case 'date':
                $query->orderBy('brigade.created_at DESC');
            break;
            case 'day':
                $date = date("Y-m-d");
                $sub_query = Ticket::find()
                    ->select('brigade_id, count(brigade_id) as b_cnt')
                    ->where(['status' => Ticket::STATUS_COMPLETED])
                    ->andWhere("DATE_FORMAT(finished_at, '%Y-%m-%d') = '$date'")
                    ->groupBy('brigade_id');
                
                $query->leftJoin(['dayCnt' => $sub_query], 'dayCnt.brigade_id = brigade.id');
                $query->orderBy('dayCnt.b_cnt DESC'); 
            break;
            case 'total':
                $sub_query = Ticket::find()
                    ->select('brigade_id, count(brigade_id) as b_cnt')
                    ->where(['status' => Ticket::STATUS_COMPLETED])
                    ->groupBy('brigade_id');
                
                $query->leftJoin(['totalCnt' => $sub_query], 'totalCnt.brigade_id = brigade.id');
                $query->orderBy('totalCnt.b_cnt DESC');
            break;
            default:
                $query->orderBy('brigade.created_at DESC');
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'status' => $status,
            'sortorder' => $sort_order,
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
                            $model_brigade_has_user->is_master = 0;
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
        $model_brigade_has_user = new BrigadeHasUser;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            BrigadeHasUser::deleteAll(['brigade_id' => $model->id]);
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
                        $model_brigade_has_user->is_master = 0;
                        $model_brigade_has_user->save();
                    }
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Необходимо указать бригадира');
                    $model->delete();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'model_brigade_has_user' => $model_brigade_has_user,
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
    
    public function actionSetOffline($id)
    {
        $model = $this->findModel($id);
        $model->status = Brigade::STATUS_OFFLINE;
        $model->save();
        
        return $this->redirect(['index']);
    }
    
    public function actionSetBrigade()
    {
        $brigade_id = Yii::$app->request->post('brigade');
        $user_id = Yii::$app->request->post('user');
        
        $model = BrigadeHasUser::find()->where(['user_id' => $user_id])->one();
        if ($model) {
            if ($brigade_id == 0) {
                Yii::$app->session->setFlash('warning', 'Привязка к бригаде успешно удалена');
                $model->delete();
            } else {
                $model->brigade_id = $brigade_id;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Привязка к бригаде успешно изменена');
                }
            }
        } else {
            if ($brigade_id != 0) {
                $user = User::findOne($user_id);
                $model = new BrigadeHasUser;
                $model->user_id = $user_id;
                $model->brigade_id = $brigade_id;
                $model->is_master = $user->role == User::ROLE_BRIGADIER ? 1 : 0;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Привязка к бригаде успешно изменена');
                }
            }
        }
        
        return false;
    }
    
    public function actionSetArea()
    {
        $brigade_id = Yii::$app->request->post('brigade');
        $area_id = Yii::$app->request->post('area');
        
        $model = Brigade::findOne($brigade_id);
        if ($model) {
            $model->area_id = $area_id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Привязка к квадранту успешно изменена');
            }
        }
        
        return false;
    }
}
