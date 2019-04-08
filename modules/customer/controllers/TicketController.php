<?php

namespace app\modules\customer\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Ticket;
use app\models\TicketHasJobType;
use app\models\Customer;

use app\modules\api\models\Car;

class TicketController extends \app\controllers\TicketController
{
    public function actionIndex($status = '')
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Ticket::find(),
            'sort' => false
        ]);
        
        $this->viewPath = '@app/views/ticket';
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'status' => $status,
        ]);
    }
    
    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $cars_model = new Car();
        $cars_model->getData();
        $model = new Ticket();
        $ticket_has_job_model = new TicketHasJobType();

        if ($model->load(Yii::$app->request->post())) {
            $car = new Car();
            $car->carId = $model->car_id;
            $car->getData();
            $customer = Customer::find()->where(['subdomain' => Yii::$app->request->queryParams['subdomain']])->one();
            $model->type = Ticket::TICKET_TYPE_HANDLE;
            $model->lat = $car->lat;
            $model->lon = $car->lon;
            $model->customer_id = $customer->id;
            if ($model->save()) {
                if ($ticket_has_job_model->load(Yii::$app->request->post())) {
                    $job_types = $ticket_has_job_model->job_type_id;
                    foreach ($job_types as $job_type_id) {
                        $ticket_has_job_model = new TicketHasJobType;
                        $ticket_has_job_model->ticket_id = $model->id;
                        $ticket_has_job_model->job_type_id = $job_type_id;
                        $ticket_has_job_model->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'cars_model' => $cars_model,
            'ticket_has_job_model' => $ticket_has_job_model,
        ]);
    }
    
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $car = new Car();
        $car->carId = $model->car_id;
        $car->getData();
        
        $this->viewPath = '@app/views/ticket';
        return $this->render('view', [
            'model' => $model,
            'car' => $car,
        ]);
    }
    
    /**
     * Updates an existing Ticket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $cars_model = new Car();
        $cars_model->getData();
        $model = $this->findModel($id);
        $ticket_has_job_model = new TicketHasJobType();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                TicketHasJobType::deleteAll(['ticket_id' => $model->id]);
                if ($ticket_has_job_model->load(Yii::$app->request->post())) {
                    $job_types = $ticket_has_job_model->job_type_id;
                    foreach ($job_types as $job_type_id) {
                        $ticket_has_job_model = new TicketHasJobType;
                        $ticket_has_job_model->ticket_id = $model->id;
                        $ticket_has_job_model->job_type_id = $job_type_id;
                        $ticket_has_job_model->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'cars_model' => $cars_model,
            'ticket_has_job_model' => $ticket_has_job_model,
        ]);
    }
    
    /**
     * Deletes an existing Ticket model.
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
}
