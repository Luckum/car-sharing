<?php

namespace app\modules\settings\controllers;

use Yii;
use app\models\Customer;
use app\models\User;
use app\models\CustomerHasUser;
use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use app\helpers\ImageHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends BaseController
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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Customer::find(),
            'sort' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
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
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($model->load(Yii::$app->request->post())) {
            $model->subdomain = strtolower($model->subdomain);
            $model->logo_file = UploadedFile::getInstance($model, 'logo_file');
            $model->phone = preg_replace('/\D+/', '', $model->phone);
            if ($model->logo_file && $model->validate()) {
                $f_name = md5($model->logo_file->baseName . microtime());
                $model->logo_file->saveAs('uploads/logos/' . $f_name . '.' . $model->logo_file->extension);
                $model->logo = $f_name . '.' . $model->logo_file->extension;
                $model->logo_file = null;
                ImageHelper::imageResize(Yii::getAlias('@webroot') . '/uploads/logos/' . $model->logo, Yii::getAlias('@webroot') . '/uploads/logos/' . $model->logo, 120, 120);
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('delete_logo') !== null) {
                if (!empty($model->logo) && file_exists(Yii::getAlias('@webroot') . '/uploads/logos/' . $model->logo)) {
                    unlink(Yii::getAlias('@webroot') . '/uploads/logos/' . $model->logo);
                }
                $model->logo = null;
                $model->logo_file = null;
            } else {
                $model->logo_file = UploadedFile::getInstance($model, 'logo_file');
                if ($model->logo_file && $model->validate()) {
                    if (!empty($model->logo) && file_exists(Yii::getAlias('@webroot') . '/uploads/logos/' . $model->logo)) {
                        unlink(Yii::getAlias('@webroot') . '/uploads/logos/' . $model->logo);
                    }
                    $f_name = md5($model->logo_file->baseName . microtime());
                    $model->logo_file->saveAs('uploads/logos/' . $f_name . '.' . $model->logo_file->extension);
                    $model->logo = $f_name . '.' . $model->logo_file->extension;
                    $model->logo_file = null;
                    ImageHelper::imageResize(Yii::getAlias('@webroot') . '/uploads/logos/' . $model->logo, Yii::getAlias('@webroot') . '/uploads/logos/' . $model->logo, 120, 120);
                }
            }
            
            $model->phone = preg_replace('/\D+/', '', $model->phone);
            $model->subdomain = strtolower($model->subdomain);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Customer model.
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
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionOperator($id)
    {
        $model = $this->findModel($id);
        
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['role' => User::ROLE_OPERATOR]),
            'sort' => false
        ]);
        
        return $this->render('operator', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
    
    public function actionOperatorCreate($id)
    {
        $model = $this->findModel($id);
        $model_user = new User;
        
        if ($model_user->load(Yii::$app->request->post())) {
            $model_user->password = Yii::$app->getSecurity()->generatePasswordHash($model_user->password);
            $model_user->role = User::ROLE_OPERATOR;
            if ($model_user->save()) {
                $model_customer_has_user = new CustomerHasUser;
                $model_customer_has_user->user_id = $model_user->id;
                $model_customer_has_user->customer_id = $model->id;
                if ($model_customer_has_user->save()) {
                    return $this->redirect(['operator-view', 'id' => $model->id, 'oid' => $model_user->id]);
                }
            }
        }
        
        return $this->render('operator-create', [
            'model' => $model,
            'model_user' => $model_user,
        ]);
    }
    
    public function actionOperatorView($id, $oid)
    {
        $model = $this->findModel($id);
        $model_user = User::findOne($oid);
        
        return $this->render('operator-view', [
            'model' => $model,
            'model_user' => $model_user,
        ]);
    }
    
    public function actionOperatorUpdate($id, $oid)
    {
        $model = $this->findModel($id);
        $model_user = User::findOne($oid);
        
        if ($model_user->load(Yii::$app->request->post()) && $model_user->save()) {
            return $this->redirect(['operator-view', 'id' => $model->id, 'oid' => $model_user->id]);
        }
        
        return $this->render('operator-update', [
            'model' => $model,
            'model_user' => $model_user,
        ]);
    }
    
    public function actionOperatorDelete($id, $oid)
    {
        $model_user = User::findOne($oid);
        $model_user->delete();
        
        return $this->redirect(['operator', 'id' => $id]);
    }
}
