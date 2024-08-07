<?php

namespace app\controllers;

use Yii;
use app\models\ReporteTiempoExtra;
use app\models\ReporteTiempoExtraSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Solicitud;
use app\models\Empleado;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\web\Response;
use app\models\ParametroFormato;
use app\models\JuntaGobierno;
use app\models\CatDireccion;
use app\models\Notificacion;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use app\models\ActividadReporteTiempoExtra;
use yii\helpers\Url;


/**
 * ReporteTiempoExtraController implements the CRUD actions for ReporteTiempoExtra model.
 */
class ReporteTiempoExtraController extends Controller
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
     * Lists all ReporteTiempoExtra models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioId = Yii::$app->user->identity->id;

    $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

    if ($empleado !== null) {
        $searchModel = new ReporteTiempoExtraSearch();
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
     * Displays a single ReporteTiempoExtra model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);
        $empleado = Empleado::findOne($model->empleado_id);
        $actividades = ActividadReporteTiempoExtra::find()->where(['reporte_tiempo_extra_id' => $model->id])->all();
    
        return $this->render('view', [
            'model' => $model,
            'empleado' => $empleado,
            'actividades' => $actividades, // Pasar actividades a la vista
        ]);
    }

    
    
    public function actionHistorial($empleado_id = null)
    {
        if ($empleado_id === null) {
            throw new NotFoundHttpException('El empleado seleccionado no existe.');
        }
    
        $empleado = Empleado::findOne($empleado_id);
        if ($empleado === null) {
            throw new NotFoundHttpException('El empleado seleccionado no existe.');
        }
    
        $searchModel = new ReporteTiempoExtraSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['empleado_id' => $empleado->id]);
    
        $actividades = [];
        foreach ($dataProvider->getModels() as $reporte) {
            $reporteActividades = ActividadReporteTiempoExtra::find()->where(['reporte_tiempo_extra_id' => $reporte->id])->all();
            $actividades = array_merge($actividades, $reporteActividades);
        }
    
        $this->layout = "main-trabajador";
    
        return $this->render('historial', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'empleado' => $empleado,
            'actividades' => $actividades, // Pasar actividades a la vista
        ]);
    }
    


    /**
     * Creates a new ReporteTiempoExtra model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($empleado_id = null)
    {
        $this->layout = "main-trabajador";
    
        $model = new ReporteTiempoExtra();
        $solicitudModel = new Solicitud();
        $actividadModel = new ActividadReporteTiempoExtra(); // Añadir esta línea
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
                $solicitudModel->nombre_formato = 'REPORTE DE TIEMPO EXTRA';
    
                if ($solicitudModel->save()) {
                    $model->solicitud_id = $solicitudModel->id;
    
                    if ($model->save()) {
                        $fechas = Yii::$app->request->post('ActividadReporteTiempoExtra')['fecha'];
                        $hora_inicio = Yii::$app->request->post('ActividadReporteTiempoExtra')['hora_inicio'];
                        $hora_fin = Yii::$app->request->post('ActividadReporteTiempoExtra')['hora_fin'];
                        $actividad = Yii::$app->request->post('ActividadReporteTiempoExtra')['actividad'];
                        $no_horas = Yii::$app->request->post('ActividadReporteTiempoExtra')['no_horas'];
    
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
                                $actividadModel = new ActividadReporteTiempoExtra();
                                $actividadModel->reporte_tiempo_extra_id = $model->id;
                                $actividadModel->fecha = $fecha;
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
     * Updates an existing ReporteTiempoExtra model.
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
     * Deletes an existing ReporteTiempoExtra model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
{
    $model = $this->findModel($id);
    $empleado = Empleado::findOne($model->empleado_id);
    if ($model === null) {
        Yii::$app->session->setFlash('error', 'El registro de permiso no fue encontrado.');
        return $this->redirect(['index']);
    }
    
    $transaction = Yii::$app->db->beginTransaction();
    try {
        // Obtener el ID de la solicitud asociada antes de eliminar el permiso
        $solicitudId = $model->solicitud_id;
        
        // Eliminar los registros asociados en la tabla ActividadReporteTiempoExtra
        ActividadReporteTiempoExtra::deleteAll(['reporte_tiempo_extra_id' => $model->id]);

        // Eliminar el registro de PermisoFueraTrabajo
        if ($model->delete()) {
            // Buscar y eliminar el registro asociado en la tabla Solicitud
            $solicitudModel = Solicitud::findOne($solicitudId);
            if ($solicitudModel !== null) {
                $solicitudModel->delete();
            }
            $transaction->commit();
            
            Yii::$app->session->setFlash('success', 'El registro de permiso y la solicitud asociada han sido eliminados correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al eliminar el registro de permiso.');
        }
    } catch (\Exception $e) {
        $transaction->rollBack();
        Yii::$app->session->setFlash('error', 'Hubo un error al eliminar el registro: ' . $e->getMessage());
    } catch (\Throwable $e) {
        $transaction->rollBack();
        Yii::$app->session->setFlash('error', 'Hubo un error al eliminar el registro: ' . $e->getMessage());
    }
    
    if (Yii::$app->user->can('gestor-rh')) {
        Yii::$app->session->setFlash('success', 'El registro se ha eliminado exitosamente.');
        $url = Url::to(['historial', 'empleado_id' => $empleado->id]) ;
        return $this->redirect($url);
    } else {
        return $this->redirect(['index']);
    }
}

    /**
     * Finds the ReporteTiempoExtra model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ReporteTiempoExtra the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReporteTiempoExtra::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }




    public function actionExportHtml($id)
    {
        $this->layout = false;
    
        $model = ReporteTiempoExtra::findOne($id);
    
        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }
        setlocale(LC_TIME, 'es_419.UTF-8'); 
    
        $templatePath = Yii::getAlias('@app/templates/tiempo_extra.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
    
        $sheet->setCellValue('I9', $model->empleado->numero_empleado);
    
        $nombre = mb_strtoupper($model->empleado->nombre, 'UTF-8');
        $apellido = mb_strtoupper($model->empleado->apellido, 'UTF-8');
        $nombreCompleto = $apellido . ' ' . $nombre;
        $sheet->setCellValue('E10', $nombreCompleto);
        $sheet->setCellValue('A38', $nombreCompleto);
    
        $sheet->setCellValue('G28', $model->total_horas);
    
        $nombrePuesto = $model->empleado->informacionLaboral->catPuesto->nombre_puesto;
        $sheet->setCellValue('E11', $nombrePuesto);
    
        $nombreCargo = $model->empleado->informacionLaboral->catDptoCargo->nombre_dpto;
        $sheet->setCellValue('I11', $nombreCargo);
    
        $nombreDireccion = $model->empleado->informacionLaboral->catDireccion->nombre_direccion;
        $sheet->setCellValue('F6', $nombreDireccion);
    
        $sheet->setCellValue('A39', $nombrePuesto);
    
        $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);
    
        if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL') {
            $nombreJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->nombre, 'UTF-8');
            $apellidoJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->apellido, 'UTF-8');
            $profesionJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->profesion, 'UTF-8');
            $nombreCompletoJefe = $profesionJefe . ' ' . $apellidoJefe . ' ' . $nombreJefe;
    
            $sheet->setCellValue('E38', $nombreCompletoJefe);
        } else {
            $sheet->setCellValue('E38', null);
        }
    
        $juntaDirectorDireccion = JuntaGobierno::find()
            ->where(['cat_direccion_id' => $model->empleado->informacionLaboral->cat_direccion_id])
            ->andWhere(['or', ['nivel_jerarquico' => 'Director'], ['nivel_jerarquico' => 'Jefe de unidad']])
            ->one();
    
        if ($juntaDirectorDireccion) {
            $nombreDirector = mb_strtoupper($juntaDirectorDireccion->empleado->nombre, 'UTF-8');
            $apellidoDirector = mb_strtoupper($juntaDirectorDireccion->empleado->apellido, 'UTF-8');
            $profesionDirector = mb_strtoupper($juntaDirectorDireccion->empleado->profesion, 'UTF-8');
            $nombreCompletoDirector = $profesionDirector . ' ' . $apellidoDirector . ' ' . $nombreDirector;
    
            $sheet->setCellValue('H38', $nombreCompletoDirector);
    
            $nombreDireccion = $juntaDirectorDireccion->catDireccion->nombre_direccion;
            switch ($nombreDireccion) {
                case '1.- GENERAL':
                    if ($model->empleado->informacionLaboral->juntaGobierno->nivel_jerarquico == 'Jefe de unidad') {
                        $nombreJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->nombre, 'UTF-8');
                        $apellidoJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->apellido, 'UTF-8');
                        $profesionJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->profesion, 'UTF-8');
                        $nombreCompletoJefe = $profesionJefe . ' ' . $apellidoJefe . ' ' . $nombreJefe;
    
                        $sheet->setCellValue('H38', $nombreCompletoJefe);
                        $tituloDireccion = 'JEFE DE ' . $model->empleado->informacionLaboral->juntaGobierno->empleado->informacionLaboral->catDepartamento->nombre_departamento;
                    } else {
                        $tituloDireccion = 'DIRECTOR GENERAL prueba';
                    }
                    break;
                case '2.- ADMINISTRACIÓN':
                    $tituloDireccion = 'DIRECTOR DE ADMINISTRACIÓN';
                    break;
                case '4.- OPERACIONES':
                    $tituloDireccion = 'DIRECTOR DE OPERACIONES';
                    break;
                case '3.- COMERCIAL':
                    $tituloDireccion = 'DIRECTOR COMERCIAL';
                    break;
                case '5.- PLANEACION':
                    $tituloDireccion = 'DIRECTOR DE PLANEACION';
                    break;
                default:
                    $tituloDireccion = '';
            }
    
            $sheet->setCellValue('H39', $tituloDireccion);
        } else {
            $sheet->setCellValue('H38', null);
            $sheet->setCellValue('H39', null);
        }
    
        // Añadir actividades
        $actividades = ActividadReporteTiempoExtra::find()->where(['reporte_tiempo_extra_id' => $id])->all();
    
        $startRow = 16; // Fila inicial para las actividades
        foreach ($actividades as $index => $actividad) {
            $row = $startRow + $index;
        
            // Convertir la fecha a un timestamp
            $timestamp = strtotime($actividad->fecha);
        
            // Formatear la fecha
            $fechaFormateada = strftime('%A %d de %B de %Y', $timestamp);
        
            // Asignar la fecha formateada a la celda
            $sheet->setCellValue('B' . $row, $fechaFormateada);
            $sheet->setCellValue('E' . $row, $actividad->hora_inicio);
            $sheet->setCellValue('F' . $row, $actividad->hora_fin);
            $sheet->setCellValue('G' . $row, $actividad->no_horas);
            $sheet->setCellValue('H' . $row, $actividad->actividad);
        }
        
        $htmlWriter = new Html($spreadsheet);
        $htmlWriter->setSheetIndex(0);
        $htmlWriter->setPreCalculateFormulas(false);
    
        $fullHtmlContent = $htmlWriter->generateHtmlAll();
        $fullHtmlContent = str_replace('&nbsp;', ' ', $fullHtmlContent);
    
        return $this->render('excel-html', ['htmlContent' => $fullHtmlContent, 'model' => $model]);
    }
    
}
