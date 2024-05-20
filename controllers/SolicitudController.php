<?php

namespace app\controllers;

use Yii;
use app\models\Solicitud;
use app\models\SolicitudSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Empleado;
use app\models\Notificacion;
/**
 * SolicitudController implements the CRUD actions for Solicitud model.
 */
class SolicitudController extends Controller
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
     * Lists all Solicitud models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SolicitudSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Solicitud model.
     * @param int $id ID
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
     * Creates a new Solicitud model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Solicitud();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    public function actionAprobarSolicitud($id)
    {
        $model = $this->findModel($id);
    
        if (Yii::$app->request->isPost) {
            $status = Yii::$app->request->post('status');
            $comentario = Yii::$app->request->post('comentario');
    
            // Actualizar el estado y el comentario
            $model->status = $status;
            $model->comentario = $comentario;
    
            // Obtener el empleado que inició sesión
            $usuarioId = Yii::$app->user->identity->id;
            $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();
    
            if ($empleado) {
                // Actualizar la fecha de aprobación y el nombre del aprobante
                $model->fecha_aprobacion = date('Y-m-d H:i:s');
                $model->nombre_aprobante = $empleado->nombre . ' ' . $empleado->apellido;
    
                if ($model->save()) {
                    $notificacion = new Notificacion();
                    $notificacion->usuario_id = $model->empleado->usuario_id;
                    
                    $notificacion->mensaje = 'Su solicitud ha sido ' . ($status == 'Aprobado' ? 'aprobada' : 'rechazada') . '.';
                    $notificacion->created_at = date('Y-m-d H:i:s');
                    $notificacion->leido = 0; // Marcar la notificación como no leída
                    if ($notificacion->save()) {
                        Yii::$app->session->setFlash('success', 'Solicitud modificada correctamente.');
                    } else {
                        Yii::$app->session->setFlash('error', 'Hubo un error al guardar la notificación.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Hubo un error al modificar la solicitud.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
            }
    
            return $this->redirect(['view', 'id' => $model->id]);
        }
    
        return $this->redirect(['index']);
    }
    
    


    /**
     * Updates an existing Solicitud model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
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
     * Deletes an existing Solicitud model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Solicitud model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Solicitud the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Solicitud::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
