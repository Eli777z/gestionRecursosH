<?php

namespace app\controllers;

use app\models\ActividadReporteTiempoExtraGeneral;
use Yii;
use app\models\ReporteTiempoExtraGeneral;
use app\models\ReporteTiempoExtraGeneralSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Empleado;
use app\models\Solicitud;

/**
 * ReporteTiempoExtraGeneralController implements the CRUD actions for ReporteTiempoExtraGeneral model.
 */
class ReporteTiempoExtraGeneralController extends Controller
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
     * Lists all ReporteTiempoExtraGeneral models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioId = Yii::$app->user->identity->id;

    $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

    if ($empleado !== null) {
        $searchModel = new ReporteTiempoExtraGeneralSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andFilterWhere(['empleado_id' => $empleado->id]);

        

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    } else {
        Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
        return $this->redirect(['index']); 
    }
    }

    /**
     * Displays a single ReporteTiempoExtraGeneral model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);
        $empleado = Empleado::findOne($model->empleado_id);
        $actividades = ActividadReporteTiempoExtraGeneral::find()->where(['reporte_tiempo_extra_id' => $model->id])->all();
    
        return $this->render('view', [
            'model' => $model,
            'empleado' => $empleado,
            'actividades' => $actividades, // Pasar actividades a la vista
        ]);
    }
    /**
     * Creates a new ReporteTiempoExtraGeneral model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($empleado_id = null)
    {
        $this->layout = "main-trabajador";
    
        $model = new ReporteTiempoExtraGeneral();
        $solicitudModel = new Solicitud();
        $actividadModel = new ActividadReporteTiempoExtraGeneral(); // Añadir esta línea
        $usuarioId = Yii::$app->user->identity->id;
    
        if ($empleado_id) {
            $empleado = Empleado::findOne($empleado_id);
        } else {
            $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();
        }
    
        if ($empleado) {
            $model->empleado_id = $empleado->id;
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado.');
            return $this->redirect(['index']);
        }
    
        if ($actividadModel->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $solicitudModel->empleado_id = $empleado->id;
                $solicitudModel->status = 'Nueva';
                $solicitudModel->comentario = '';
                $solicitudModel->fecha_aprobacion = null;
                $solicitudModel->fecha_creacion = date('Y-m-d H:i:s');
                $solicitudModel->nombre_formato = 'REPORTE DE TIEMPO EXTRA GENERAL';
    
                if ($solicitudModel->save()) {
                    $model->solicitud_id = $solicitudModel->id;
    
                    if ($model->save()) {
                        $empleados = Yii:: $app->request->post('ActividadReporteTiempoExtraGeneral'['empleado_id']);
                        $fechas = Yii::$app->request->post('ActividadReporteTiempoExtraGeneral')['fecha'];
                        $hora_inicio = Yii::$app->request->post('ActividadReporteTiempoExtraGeneral')['hora_inicio'];
                        $hora_fin = Yii::$app->request->post('ActividadReporteTiempoExtraGeneral')['hora_fin'];
                        $actividad = Yii::$app->request->post('ActividadReporteTiempoExtraGeneral')['actividad'];
                        $no_horas = Yii::$app->request->post('ActividadReporteTiempoExtraGeneral')['no_horas'];
    
                        // Calcular la suma total de no_horas
                        $totalHoras = 0;
                        foreach ($no_horas as $horas) {
                            $totalHoras += intval($horas); // Asegurarse de que sea un número entero
                        }
    
                        // Asignar el total_horas al modelo y guardar
                        $model->total_horas = $totalHoras;
    
                        if ($model->save()) {
                            // Guardar actividades
                            foreach ($fechas as $index => $fecha) {
                                $actividadModel = new ActividadReporteTiempoExtraGeneral();
                                $actividadModel->reporte_tiempo_extra_id = $model->id;
                                $actividadModel->fecha = $fecha;
                                $actividadModel->empleado_id = $empleados[$index];

                                $actividadModel->hora_inicio = $hora_inicio[$index];
                                $actividadModel->hora_fin = $hora_fin[$index];
                                $actividadModel->actividad = $actividad[$index];
                                $actividadModel->no_horas = $no_horas[$index]; // Guardar el valor de no_horas
    
                                if (!$actividadModel->save()) {
                                    throw new \Exception('Error al guardar la actividad: ' . implode(', ', $actividadModel->firstErrors));
                                }
                            }
    
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Reporte de tiempo extra creado exitosamente.');
                            return $this->redirect(['view', 'id' => $model->id]);
                        } else {
                            throw new \Exception('Error al guardar el reporte de tiempo extra: ' . implode(', ', $model->firstErrors));
                        }
                    } else {
                        throw new \Exception('Error al guardar el reporte de tiempo extra: ' . implode(', ', $model->firstErrors));
                    }
                } else {
                    throw new \Exception('Error al guardar la solicitud: ' . implode(', ', $solicitudModel->firstErrors));
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Error al crear el reporte de tiempo extra: ' . $e->getMessage());
            }
        }
    
        return $this->render('create', [
            'model' => $model,
            'solicitudModel' => $solicitudModel,
            'actividadModel' => $actividadModel, // Añadir esta línea
            'empleado' => $empleado,
        ]);
    }
    

    /**
     * Updates an existing ReporteTiempoExtraGeneral model.
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
     * Deletes an existing ReporteTiempoExtraGeneral model.
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
     * Finds the ReporteTiempoExtraGeneral model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ReporteTiempoExtraGeneral the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReporteTiempoExtraGeneral::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
