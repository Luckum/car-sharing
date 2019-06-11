<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\PasswordResetRequestForm;
use app\models\User;
use app\models\Ticket;

class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = User::find()->where(['!=', 'role', User::ROLE_ADMIN])->andWhere(['!=', 'role', User::ROLE_OPERATOR]);
        if (Yii::$app->user->identity->role == User::ROLE_MANAGER) {
            $query->andWhere(['!=', 'role', User::ROLE_MANAGER]);
        }
        
        $sort_order = Yii::$app->request->post('sort');
        $filter = Yii::$app->request->post('filter');
        switch ($sort_order) {
            case 'date':
                $query->orderBy('user.created_at DESC');
            break;
            case 'day':
                $date = date("Y-m-d");
                $sub_query = Ticket::find()
                    ->select('brigade_id, count(brigade_id) as b_cnt')
                    ->where(['status' => Ticket::STATUS_COMPLETED])
                    ->andWhere("DATE_FORMAT(finished_at, '%Y-%m-%d') = '$date'")
                    ->groupBy('brigade_id');
                
                $query->joinWith('brigadeHasUser');
                $query->leftJoin(['dayCnt' => $sub_query], 'dayCnt.brigade_id = brigade_has_user.brigade_id');
                $query->orderBy('dayCnt.b_cnt DESC'); 
            break;
            case 'total':
                $sub_query = Ticket::find()
                    ->select('brigade_id, count(brigade_id) as b_cnt')
                    ->where(['status' => Ticket::STATUS_COMPLETED])
                    ->groupBy('brigade_id');
                
                $query->joinWith('brigadeHasUser');
                $query->leftJoin(['totalCnt' => $sub_query], 'totalCnt.brigade_id = brigade_has_user.brigade_id');
                $query->orderBy('totalCnt.b_cnt DESC');
            break;
            default:
                $query->orderBy('user.created_at DESC');
        }
        
        switch ($filter) {
            case User::ROLE_WORKER:
                $query->andWhere(['role' => User::ROLE_WORKER]);
            break;
            case User::ROLE_BRIGADIER:
                $query->andWhere(['role' => User::ROLE_BRIGADIER]);
            break;
            case User::ROLE_MANAGER:
                $query->andWhere(['role' => User::ROLE_MANAGER]);
            break;
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->renderPartial('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $new_password = substr(md5(time()), 10, 8);
            $user = User::findOne([
                'active' => User::STATUS_ACTIVE,
                'email' => $model->email,
            ]);
            
            if ($user) {
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($new_password);
                if ($user->save()) {
                    Yii::$app->mailer
                        ->compose(
                            ['html' => 'passwordReset-html', 'text' => 'passwordReset-text'],
                            ['user' => $user, 'password' => $new_password]
                        )
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                        ->setTo($model->email)
                        ->setSubject('Восстановление доступа к ' . Yii::$app->name)
                        ->send();
                        
                    Yii::$app->session->setFlash('success', 'На указанный email было выслано письмо с новым паролем.');

                    return $this->goHome();
                }
            } else {
                Yii::$app->session->setFlash('error', 'Извините, мы не можем восстановить пароль для указанного email');
            }
        }

        return $this->renderPartial('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }
}
