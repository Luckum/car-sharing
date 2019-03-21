<?php

namespace app\modules\customer\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Ticket;
use app\models\TicketHasJobType;

use app\modules\api\models\Car;

class TicketController extends \app\controllers\TicketController
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Ticket::find(),
            'sort' => false
        ]);
        
        $this->viewPath = '@app/views/ticket';
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $cars_model = new Car;
        $model = new Ticket();
        $ticket_has_job_model = new TicketHasJobType();

        if ($model->load(Yii::$app->request->post())) {
            $model->type = Ticket::TICKET_TYPE_HANDLE;
            $model->lat = Car::getLatById($model->car_id);
            $model->lon = Car::getLonById($model->car_id);
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
}
