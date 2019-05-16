<?php

namespace app\controllers;

use Yii;
use app\models\Ticket;
use app\models\User;
use app\models\Brigade;
use app\models\BrigadeStatus;
use app\models\Customer;
use app\modules\api\models\Car;

use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends BaseController
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
     * Lists all Ticket models.
     * @return mixed
     */
    public function actionIndex($status = '')
    {
        $query = Ticket::find()->joinWith('customer')->joinWith('customer.customerApi');
        
        if (!empty($status)) {
            if ($status == 'new') {
                $query->where(['ticket.status' => Ticket::STATUS_ASAP]);
                $query->orWhere(['ticket.status' => Ticket::STATUS_COMMON]);
            } else {
                $query->where(['ticket.status' => $status]);
            }
        }
        
        if (Yii::$app->user->identity->role == User::ROLE_BRIGADIER) {
            $tickets = Ticket::getTicketsForBrigade(Yii::$app->user->identity->brigadeHasUser->brigade_id);
            if (!empty($status)) {
                $query->andWhere(['ticket.id' => $tickets]);
            } else {
                $query->where(['ticket.id' => $tickets]);
            }
            
        }
        
        $sort_order = Yii::$app->request->post('sort');
        switch ($sort_order) {
            case 'urgency':
                $query->orderBy([new \yii\db\Expression(
                    'FIELD(ticket.status, ' . 
                    Ticket::STATUS_ASAP . ', ' . 
                    Ticket::STATUS_COMMON . ', ' . 
                    Ticket::STATUS_COMPLETED . ', ' . 
                    Ticket::STATUS_IN_WORK . ', ' . 
                    Ticket::STATUS_REJECTED . ', ' .
                    Ticket::STATUS_DELAYED . ')'
                )]);
            break;
            case 'date':
                $query->orderBy('ticket.created_at DESC');
            break;
            case 'address':
                
            break;
            default:
                $query->orderBy([new \yii\db\Expression(
                    'FIELD(ticket.status, ' . 
                    Ticket::STATUS_ASAP . ', ' . 
                    Ticket::STATUS_COMMON . ', ' . 
                    Ticket::STATUS_COMPLETED . ', ' . 
                    Ticket::STATUS_IN_WORK . ', ' . 
                    Ticket::STATUS_REJECTED . ', ' .
                    Ticket::STATUS_DELAYED . ')'
                )]);
        }
        
        $query->andWhere(['not', ['customer_api.id' => null]]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);
        
        $customers = Customer::find()->all();
        if ($customers) {
            foreach ($customers as $customer) {
                if (!$customer->customerApi) {
                    Yii::$app->session->addFlash('error', 'Не настроен доступ к АПИ компании ' . $customer->title . '!');
                }
            }
        }
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'status' => $status,
        ]);
    }

    /**
     * Displays a single Ticket model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionAttach($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $id]);
        }
        
        return $this->render('attach', [
            'model' => $model,
        ]);
    }
    
    public function actionReject($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) { 
            if (!empty($model->comment)) {
                $model->status = Ticket::STATUS_REJECTED;
                $model->rejected_at = date('Y-m-d H:i:s');
                $model->brigade_id = Yii::$app->user->identity->brigadeHasUser->brigade_id;
                if ($model->save()) {
                    Yii::$app->session->setFlash('error', 'Заявка отклонена');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Необходимо указать причину отклонения заявки');
            }
        }
        
        return $this->redirect(['view', 'id' => $id]);
    }
    
    public function actionAccept($id)
    {
        $model = $this->findModel($id);
        
        $model->status = Ticket::STATUS_IN_WORK;
        $model->started_at = date('Y-m-d H:i:s');
        $model->brigade_id = Yii::$app->user->identity->brigadeHasUser->brigade_id;
        if ($model->save()) {
            $model_brigade = Brigade::findOne(Yii::$app->user->identity->brigadeHasUser->brigade_id);
            $model_brigade->status = Brigade::STATUS_IN_WORK;
            BrigadeStatus::changeStatus(Yii::$app->user->identity->brigadeHasUser->brigade_id, Brigade::STATUS_IN_WORK);
            if ($model_brigade->save()) {
                Yii::$app->session->setFlash('success', 'Заявка принята в работу');
                return $this->redirect(['view', 'id' => $id]);
            }
        }
    }
    
    public function actionClose($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->milage)) {
                if (!empty($model->fuel)) {
                    $model->status = Ticket::STATUS_COMPLETED;
                    $model->finished_at = date('Y-m-d H:i:s');
                    if ($model->save()) {
                        $model_brigade = Brigade::findOne(Yii::$app->user->identity->brigadeHasUser->brigade_id);
                        $model_brigade->status = Brigade::STATUS_ONLINE;
                        BrigadeStatus::changeStatus(Yii::$app->user->identity->brigadeHasUser->brigade_id, Brigade::STATUS_ONLINE);
                        if ($model_brigade->save()) {
                            Yii::$app->session->setFlash('success', 'Заявка завершена');
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Необходимо указать уровень топлива');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Необходимо указать пробег');
            }
        }
        
        return $this->redirect(['view', 'id' => $id]);
    }
}
