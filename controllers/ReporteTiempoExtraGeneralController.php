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
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\web\Response;
use app\models\ParametroFormato;
use app\models\JuntaGobierno;
use app\models\CatDireccion;
use PhpOffice\PhpSpreadsheet\Writer\Html;

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
        $empleado = Empleado::findOne($model->empleado_id); // Obtener el empleado basado en el ID
        $actividades = ActividadReporteTiempoExtraGeneral::find()
            ->where(['reporte_tiempo_extra_general_id' => $model->id])
            ->all();
    
        return $this->render('view', [
            'model' => $model,
            'empleado' => $empleado, // Pasar el empleado a la vista
            'actividades' => $actividades,
        ]);
    }

    //CARGAR HISTORIAL DE SOLICITUDES DE REPORTES DE TIEMPO EXTRA
    public function actionHistorial($empleado_id = null)
    {
        if ($empleado_id === null) {
            throw new NotFoundHttpException('El empleado seleccionado no existe.');
        }
    //SE BUSCA EL EMPLEADO SELECCIONADO
        $empleado = Empleado::findOne($empleado_id);
        if ($empleado === null) {
            throw new NotFoundHttpException('El empleado seleccionado no existe.');
        }
    //SE CARGA LOS REGISTROS DEL EMPLEADO SELECCIONADO
        $searchModel = new ReporteTiempoExtraGeneralSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['empleado_id' => $empleado->id]);
    //SE BUSCAN LOS REGISTROS DE ACTIVIDADES QUE ESTA RELACIONADOS AL REGISTRO DE REPORTE DE TIEMPO EXTRA
        $actividades = [];
        foreach ($dataProvider->getModels() as $reporte) {
            $reporteActividades = ActividadReporteTiempoExtraGeneral::find()->where(['reporte_tiempo_extra_general_id' => $reporte->id])->all();
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
     * Creates a new ReporteTiempoExtraGeneral model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
     public function actionCreate($empleado_id = null)
{
    $this->layout = "main-trabajador";
    
    $model = new ReporteTiempoExtraGeneral();
    $solicitudModel = new Solicitud();
    $actividadModel = new ActividadReporteTiempoExtraGeneral(); 
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
    
    // Obtener la lista de empleados
    $empleadosList = Empleado::find()
        ->select(['numero_empleado', 'id'])
        ->indexBy('id')
        ->column();

    if ($actividadModel->load(Yii::$app->request->post())) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $solicitudModel->empleado_id = $empleado->id;
            $solicitudModel->status = 'Nueva';
            $solicitudModel->comentario = '';
            $solicitudModel->aprobacion = 'PENDIENTE';
            $solicitudModel->fecha_aprobacion = null;
            $solicitudModel->fecha_creacion = date('Y-m-d H:i:s');
            $solicitudModel->nombre_formato = 'REPORTE DE TIEMPO EXTRA GENERAL';

            if ($solicitudModel->save()) {
                $model->solicitud_id = $solicitudModel->id;

                if ($model->save()) {
                    $fechas = Yii::$app->request->post('ActividadReporteTiempoExtraGeneral')['fecha'];
                    $hora_inicio = Yii::$app->request->post('ActividadReporteTiempoExtraGeneral')['hora_inicio'];
                    $hora_fin = Yii::$app->request->post('ActividadReporteTiempoExtraGeneral')['hora_fin'];
                    $actividad = Yii::$app->request->post('ActividadReporteTiempoExtraGeneral')['actividad'];
                    $no_horas = Yii::$app->request->post('ActividadReporteTiempoExtraGeneral')['no_horas'];
                    $numero_empleado = Yii::$app->request->post('ActividadReporteTiempoExtraGeneral')['numero_empleado']; // Agregar esto
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
                            $actividadModel->reporte_tiempo_extra_general_id = $model->id;
                            $actividadModel->fecha = $fecha;
                            $actividadModel->hora_inicio = $hora_inicio[$index];
                            $actividadModel->hora_fin = $hora_fin[$index];
                            $actividadModel->actividad = $actividad[$index];
                            $actividadModel->no_horas = $no_horas[$index]; // Guardar el valor de no_horas
                            $actividadModel->numero_empleado = $numero_empleado[$index]; // Guardar el número de empleado

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
        'actividadModel' => $actividadModel,
        'empleado' => $empleado,
        'empleadosList' => $empleadosList, // Pasar la lista de empleados a la vista
    ]);
}

    
    public function actionEmpleadoList($q = null)
    {
        $query = new \yii\db\Query;
        $query->select(['id', 'text' => 'numero_empleado'])
            ->from('empleado')
            ->where(['like', 'numero_empleado', $q])
            ->limit(20);
        $command = $query->createCommand();
        $data = $command->queryAll();
        return json_encode($data);
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

    public function actionExportHtml($id)
    {
        $this->layout = false;
    
        $model = ReporteTiempoExtraGeneral::findOne($id);
    
        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }
        setlocale(LC_TIME, 'es_419.UTF-8'); 
    
        $templatePath = Yii::getAlias('@app/templates/tiempo_extra_general.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $nombre = mb_strtoupper($model->empleado->nombre, 'UTF-8');
        $apellido = mb_strtoupper($model->empleado->apellido, 'UTF-8');
        $nombreCompleto = $apellido . ' ' . $nombre;
        $sheet->setCellValue('B38', $nombreCompleto);
        $nombreDireccion = $model->empleado->informacionLaboral->catDireccion->nombre_direccion;
        $sheet->setCellValue('D6', $nombreDireccion);
        // Añadir actividades


        
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
    
            $sheet->setCellValue('J38', $nombreCompletoDirector);
    
            $nombreDireccion = $juntaDirectorDireccion->catDireccion->nombre_direccion;
            switch ($nombreDireccion) {
                case '1.- GENERAL':
                    if ($model->empleado->informacionLaboral->juntaGobierno->nivel_jerarquico == 'Jefe de unidad') {
                        $nombreJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->nombre, 'UTF-8');
                        $apellidoJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->apellido, 'UTF-8');
                        $profesionJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->profesion, 'UTF-8');
                        $nombreCompletoJefe = $profesionJefe . ' ' . $apellidoJefe . ' ' . $nombreJefe;
    
                        $sheet->setCellValue('J38', $nombreCompletoJefe);
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
    
            $sheet->setCellValue('J39', $tituloDireccion);
        } else {
            $sheet->setCellValue('J38', null);
            $sheet->setCellValue('J39', null);
        }
    
    
        $actividades = ActividadReporteTiempoExtraGeneral::find()->where(['reporte_tiempo_extra_general_id' => $id])->all();
    
        $startRow = 10; // Fila inicial para las actividades
        foreach ($actividades as $index => $actividad) {
            $row = $startRow + $index;
    
            // Convertir la fecha a un timestamp
            $timestamp = strtotime($actividad->fecha);
    
            // Formatear la fecha
            $fechaFormateada = strftime('%A %d de %B de %Y', $timestamp);
    
            // Buscar el empleado basado en el numero_empleado
            $empleado = Empleado::findOne(['numero_empleado' => $actividad->numero_empleado]);
    
            // Verificar si el empleado fue encontrado
            if ($empleado) {
                $informacionLaboral = $empleado->informacionLaboral; // Asegúrate de que esta relación esté correctamente definida
                $catPuesto = $informacionLaboral ? $informacionLaboral->catPuesto : null;
                $catDptoCargo = $informacionLaboral ? $informacionLaboral->catDptoCargo : null;
                $catTipoContrato = $informacionLaboral ? $informacionLaboral->catTipoContrato : null;
                $sheet->setCellValue('K33', $model->total_horas);

                // Asignar valores a las celdas
                $sheet->setCellValue('B' . $row, $empleado->numero_empleado);
                $sheet->setCellValue('C' . $row, $empleado->nombre.' '.$empleado->apellido);
                $sheet->setCellValue('D' . $row, $catPuesto ? $catPuesto->nombre_puesto : 'Desconocido');
                $sheet->setCellValue('E' . $row, $catDptoCargo ? $catDptoCargo->nombre_dpto : 'Desconocido');
                $sheet->setCellValue('F' . $row, $catTipoContrato ? $catTipoContrato->nombre_tipo : 'Desconocido');
            } else {
                // Si no se encuentra el empleado, asignar valores por defecto o dejar en blanco
                $sheet->setCellValue('B' . $row, $actividad->numero_empleado);
                $sheet->setCellValue('C' . $row, 'Empleado no encontrado');
                $sheet->setCellValue('D' . $row, 'Desconocido');
                $sheet->setCellValue('E' . $row, 'Desconocido');
                $sheet->setCellValue('F' . $row, 'Desconocido');
            }
    
            // Asignar otros valores a las celdas
            $sheet->setCellValue('G' . $row, $fechaFormateada);
            $sheet->setCellValue('H' . $row, $actividad->hora_inicio);
            $sheet->setCellValue('I' . $row, $actividad->hora_fin);
            $sheet->setCellValue('J' . $row, $actividad->no_horas);
            $sheet->setCellValue('K' . $row, $actividad->no_horas);

            $sheet->setCellValue('L' . $row, $actividad->actividad);
        }
    
        $htmlWriter = new Html($spreadsheet);
        $htmlWriter->setSheetIndex(0);
        $htmlWriter->setPreCalculateFormulas(false);
    
        $fullHtmlContent = $htmlWriter->generateHtmlAll();
        $fullHtmlContent = str_replace('&nbsp;', ' ', $fullHtmlContent);
    
        return $this->render('excel-html', ['htmlContent' => $fullHtmlContent, 'model' => $model]);
    }
    

    public function actionExportHtmlSegundoCaso($id)
    {
        $this->layout = false;
    
        $model = ReporteTiempoExtraGeneral::findOne($id);
    
        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }
        setlocale(LC_TIME, 'es_419.UTF-8'); 
    
        $templatePath = Yii::getAlias('@app/templates/tiempo_extra_general.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $nombre = mb_strtoupper($model->empleado->nombre, 'UTF-8');
        $apellido = mb_strtoupper($model->empleado->apellido, 'UTF-8');
        $nombreCompleto = $apellido . ' ' . $nombre;
        $sheet->setCellValue('B38', $nombreCompleto);
        $nombreDireccion = $model->empleado->informacionLaboral->catDireccion->nombre_direccion;
        $sheet->setCellValue('D6', $nombreDireccion);
        // Añadir actividades




  $nombre = mb_strtoupper($model->empleado->nombre, 'UTF-8');
$apellido = mb_strtoupper($model->empleado->apellido, 'UTF-8');
$profesion = mb_strtoupper($model->empleado->profesion, 'UTF-8');
$nombreCompleto = $profesion.''.$apellido . ' ' . $nombre;
$sheet->setCellValue('E38', $nombreCompleto);

        $juntaGobierno = JuntaGobierno::find()
        ->where(['nivel_jerarquico' => 'Director'])
        ->all();
        
        $directorGeneral = null;
        
        foreach ($juntaGobierno as $junta) {
        $empleado = Empleado::findOne($junta->empleado_id);
        
        if ($empleado && $empleado->informacionLaboral->catPuesto->nombre_puesto === 'DIRECTOR GENERAL') {
            $directorGeneral = $empleado;
            break;
        }
        }
        
        if ($directorGeneral) {
            $nombre = mb_strtoupper($directorGeneral->nombre, 'UTF-8');
            $apellido = mb_strtoupper($directorGeneral->apellido, 'UTF-8');
            $profesion = mb_strtoupper($directorGeneral->profesion, 'UTF-8');
            $nombreCompleto =  $profesion.''.$apellido . ' ' . $nombre;
        $sheet->setCellValue('J38', $nombreCompleto);
        } else {
        
        }
        
        $sheet->setCellValue('J39', 'DIRECTOR GENERAL');
        
        
        
        
        
        
       
    
        $actividades = ActividadReporteTiempoExtraGeneral::find()->where(['reporte_tiempo_extra_general_id' => $id])->all();
    
        $startRow = 10; // Fila inicial para las actividades
        foreach ($actividades as $index => $actividad) {
            $row = $startRow + $index;
    
            // Convertir la fecha a un timestamp
            $timestamp = strtotime($actividad->fecha);
    
            // Formatear la fecha
            $fechaFormateada = strftime('%A %d de %B de %Y', $timestamp);
    
            // Buscar el empleado basado en el numero_empleado
            $empleado = Empleado::findOne(['numero_empleado' => $actividad->numero_empleado]);
    
            // Verificar si el empleado fue encontrado
            if ($empleado) {
                $informacionLaboral = $empleado->informacionLaboral; // Asegúrate de que esta relación esté correctamente definida
                $catPuesto = $informacionLaboral ? $informacionLaboral->catPuesto : null;
                $catDptoCargo = $informacionLaboral ? $informacionLaboral->catDptoCargo : null;
                $catTipoContrato = $informacionLaboral ? $informacionLaboral->catTipoContrato : null;
                $sheet->setCellValue('K33', $model->total_horas);

                // Asignar valores a las celdas
                $sheet->setCellValue('B' . $row, $empleado->numero_empleado);
                $sheet->setCellValue('C' . $row, $empleado->nombre.' '.$empleado->apellido);
                $sheet->setCellValue('D' . $row, $catPuesto ? $catPuesto->nombre_puesto : 'Desconocido');
                $sheet->setCellValue('E' . $row, $catDptoCargo ? $catDptoCargo->nombre_dpto : 'Desconocido');
                $sheet->setCellValue('F' . $row, $catTipoContrato ? $catTipoContrato->nombre_tipo : 'Desconocido');
            } else {
                // Si no se encuentra el empleado, asignar valores por defecto o dejar en blanco
                $sheet->setCellValue('B' . $row, $actividad->numero_empleado);
                $sheet->setCellValue('C' . $row, 'Empleado no encontrado');
                $sheet->setCellValue('D' . $row, 'Desconocido');
                $sheet->setCellValue('E' . $row, 'Desconocido');
                $sheet->setCellValue('F' . $row, 'Desconocido');
            }
    
            // Asignar otros valores a las celdas
            $sheet->setCellValue('G' . $row, $fechaFormateada);
            $sheet->setCellValue('H' . $row, $actividad->hora_inicio);
            $sheet->setCellValue('I' . $row, $actividad->hora_fin);
            $sheet->setCellValue('J' . $row, $actividad->no_horas);
            $sheet->setCellValue('K' . $row, $actividad->no_horas);

            $sheet->setCellValue('L' . $row, $actividad->actividad);
        }
    
        $htmlWriter = new Html($spreadsheet);
        $htmlWriter->setSheetIndex(0);
        $htmlWriter->setPreCalculateFormulas(false);
    
        $fullHtmlContent = $htmlWriter->generateHtmlAll();
        $fullHtmlContent = str_replace('&nbsp;', ' ', $fullHtmlContent);
    
        return $this->render('excel-html', ['htmlContent' => $fullHtmlContent, 'model' => $model]);
    }

    
}
