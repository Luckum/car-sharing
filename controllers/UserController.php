<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\UserProfile;
use app\helpers\ImageHelper;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($role)
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            $model->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            $model->avatar_file = UploadedFile::getInstance($model, 'avatar_file');
            if ($model->avatar_file && $model->validate()) {
                $f_name = md5($model->avatar_file->baseName . microtime());
                $model->avatar_file->saveAs('uploads/avatars/' . $f_name . '.' . $model->avatar_file->extension);
                $model->avatar = $f_name . '.' . $model->avatar_file->extension;
                $model->avatar_file = null;
                ImageHelper::imageResize(Yii::getAlias('@webroot') . '/uploads/avatars/' . $model->avatar, Yii::getAlias('@webroot') . '/uploads/avatars/' . $model->avatar, 120, 120);
            }
            if ($model->save()) {
                return $this->redirect(['/user-profile/create', 'id' => $model->id]);
            }
        }

        $model->password = null;
        return $this->render('create', [
            'model' => $model,
            'role' => $role
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_profile = UserProfile::find()->where(['user_id' => $model->id])->one();
        if (!$model_profile) {
            $model_profile = new UserProfile;
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('delete_avatar') !== null) {
                if (!empty($model->avatar) && file_exists(Yii::getAlias('@webroot') . '/uploads/avatars/' . $model->avatar)) {
                    unlink(Yii::getAlias('@webroot') . '/uploads/avatars/' . $model->avatar);
                }
                $model->avatar = null;
                $model->avatar_file = null;
            } else {
                $model->avatar_file = UploadedFile::getInstance($model, 'avatar_file');
                if ($model->avatar_file && $model->validate()) {
                    if (!empty($model->avatar) && file_exists(Yii::getAlias('@webroot') . '/uploads/avatars/' . $model->avatar)) {
                        unlink(Yii::getAlias('@webroot') . '/uploads/avatars/' . $model->avatar);
                    }
                    $f_name = md5($model->avatar_file->baseName . microtime());
                    $model->avatar_file->saveAs('uploads/avatars/' . $f_name . '.' . $model->avatar_file->extension);
                    $model->avatar = $f_name . '.' . $model->avatar_file->extension;
                    $model->avatar_file = null;
                    ImageHelper::imageResize(Yii::getAlias('@webroot') . '/uploads/avatars/' . $model->avatar, Yii::getAlias('@webroot') . '/uploads/avatars/' . $model->avatar, 120, 120);
                }
            }
            if ($model->save()) {
                if ($model_profile->load(Yii::$app->request->post())) {
                    $model_profile->phone = preg_replace('/\D+/', '', $model_profile->phone);
                    $model_profile->user_id = $model->id;
                    if ($model_profile->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'role' => $model->role,
            'model_profile' => $model_profile,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['/']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionToggleActive($id)
    {
        $model = $this->findModel($id);
        if ($model->active == User::STATUS_ACTIVE) {
            $model->active = User::STATUS_INACTIVE;
        } else if ($model->active == User::STATUS_INACTIVE) {
            $model->active = User::STATUS_ACTIVE;
        }
        
        if ($model->save()) {
            return $this->redirect(['/']);
        }
    }
    
    public function actionAccess($id)
    {
        $model = $this->findModel($id);
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
}
