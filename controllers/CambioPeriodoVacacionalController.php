<?php

namespace app\controllers;

use Yii;
use app\models\CambioPeriodoVacacional;
use app\models\CambioPeriodoVacacionalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Empleado;
use app\models\MotivoFechaPermiso;
use app\models\Solicitud;
use app\models\JuntaGobierno;
use app\models\Notificacion;
use app\models\CatDireccion;
use PhpOffice\PhpSpreadsheet\IOFactory;
use DateTime;
use app\models\PeriodoVacacionalHistorial;
/**
 * CambioPeriodoVacacionalController implements the CRUD actions for CambioPeriodoVacacional model.
 */
class CambioPeriodoVacacionalController extends Controller
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
     * Lists all CambioPeriodoVacacional models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioId = Yii::$app->user->identity->id;

        // Buscar el modelo de Empleado asociado al usuario actual
        $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

        // Verificar si se encontró el empleado
        if ($empleado !== null) {
            // Si se encontró el empleado, utilizar su ID para filtrar los registros
            $searchModel = new CambioPeriodoVacacionalSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            // Filtrar los registros por el ID del empleado
            $dataProvider->query->andFilterWhere(['empleado_id' => $empleado->id]);

            $this->layout = "main-trabajador";

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            // Si no se encontró el empleado, mostrar un mensaje de error o redireccionar
            Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
            return $this->redirect(['index']); // Redirecciona a la página de índice u otra página apropiada
        }
    }

    /**
     * Displays a single CambioPeriodoVacacional model.
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
     * Creates a new CambioPeriodoVacacional model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CambioPeriodoVacacional();
        $this->layout = "main-trabajador";
    
        $solicitudModel = new Solicitud();
       
    
        $usuarioId = Yii::$app->user->identity->id;
        $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();
    
        if ($empleado) {
            $model->empleado_id = $empleado->id;
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
            return $this->redirect(['index']);
        }
        $solicitudModel->empleado_id = $empleado->id;
        $solicitudModel->status = 'En Proceso';
        $solicitudModel->comentario = '';
        $solicitudModel->fecha_aprobacion = null;
        $solicitudModel->fecha_creacion = date('Y-m-d H:i:s');
        $solicitudModel->nombre_formato = 'CAMBIO PERIODO VACACIONAL';
    
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($solicitudModel->save()) {
                    $model->solicitud_id = $solicitudModel->id;
    
                    if ($model->jefe_departamento_id) {
                        $jefeDepartamento = JuntaGobierno::findOne($model->jefe_departamento_id);
                        $model->nombre_jefe_departamento = $jefeDepartamento ? $jefeDepartamento->profesion . ' ' . $jefeDepartamento->empleado->nombre . ' ' . $jefeDepartamento->empleado->apellido : null;
                    }
    
                    if ($model->save()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Su solicitud ha sido generada exitosamente.');
    
                        // Crear notificación
                        $notificacion = new Notificacion();
                        $notificacion->usuario_id = $model->empleado->usuario_id;
                        $notificacion->mensaje = 'Tienes una nueva solicitud pendiente de revisión.';
                        $notificacion->created_at = date('Y-m-d H:i:s');
                        $notificacion->leido = 0;
                        if ($notificacion->save()) {
                            return $this->redirect(['view', 'id' => $model->id]);
                        } else {
                            Yii::$app->session->setFlash('error', 'Hubo un error al guardar la notificación.');
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'Hubo un error al guardar la solicitud.');
                    }
                }
                $transaction->rollBack();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro: ' . $e->getMessage());
            } catch (\Throwable $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro: ' . $e->getMessage());
            }
        }
    
        return $this->render('create', [
            'model' => $model,
            'solicitudModel' => $solicitudModel,
        ]);
    }
    

    /**
     * Updates an existing CambioPeriodoVacacional model.
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
     * Deletes an existing CambioPeriodoVacacional model.
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
     * Finds the CambioPeriodoVacacional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CambioPeriodoVacacional the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CambioPeriodoVacacional::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionExport($id)
    {
        // Encuentra el modelo PermisoFueraTrabajo según el ID pasado como parámetro
        $model = CambioPeriodoVacacional::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }

        // Ruta a tu plantilla de Excel
        $templatePath = Yii::getAlias('@app/templates/cambio_periodo_vacacional.xlsx');

        // Cargar la plantilla de Excel
        $spreadsheet = IOFactory::load($templatePath);

        // Modificar la plantilla con los datos del modelo
        $sheet = $spreadsheet->getActiveSheet();

        setlocale(LC_TIME, 'es_419.UTF-8');

        if ($model->numero_periodo === '1ero') {
            $sheet->setCellValue('G14', 'X');
            $sheet->setCellValue('P14', '');
            $sheet->setCellValue('I14', $model->año);
    
            // Buscar el periodo original en el historial
            $periodoOriginal = PeriodoVacacionalHistorial::find()
                ->where(['empleado_id' => $model->empleado_id, 'periodo' => 'primer periodo', 'original' => 'Si'])
                ->orderBy(['created_at' => SORT_DESC])
                ->one();
    
            $periodoVacacional = $model->empleado->informacionLaboral->vacaciones->periodoVacacional;
    
            $fechaInicioOriginal = strftime('%d de %B del %Y', strtotime($periodoOriginal->fecha_inicio));
            $fechaFinOriginal = strftime('%d de %B del %Y', strtotime($periodoOriginal->fecha_final));
            $fechaInicioNuevo = strftime('%d de %B del %Y', strtotime($model->fecha_inicio_periodo));
            $fechaFinNuevo = strftime('%d de %B del %Y', strtotime($model->fecha_fin_periodo));
    
            // Colocar las fechas en las celdas correspondientes
            $sheet->setCellValue('G16', "$fechaInicioOriginal al $fechaFinOriginal");
            $sheet->setCellValue('G17', "$fechaInicioNuevo al $fechaFinNuevo");
            $sheet->setCellValue('G18', $periodoVacacional->dias_vacaciones_periodo);
            $sheet->setCellValue('G19', $periodoVacacional->dias_disponibles);
    
            if ($model->primera_vez === 'Sí') {
                $sheet->setCellValue('P23', 'X');
                $sheet->setCellValue('S23', '');
            } elseif ($model->primera_vez === 'No') {
                $sheet->setCellValue('P23', '');
                $sheet->setCellValue('S23', 'X');
            }
    
        } elseif ($model->numero_periodo === '2do') {
            $sheet->setCellValue('G14', '');
            $sheet->setCellValue('P14', 'X');
            $sheet->setCellValue('S14', $model->año);
    
            // Buscar el periodo original en el historial
            $periodoOriginal = PeriodoVacacionalHistorial::find()
                ->where(['empleado_id' => $model->empleado_id, 'periodo' => 'segundo periodo', 'original' => 'Si'])
                ->orderBy(['created_at' => SORT_DESC])
                ->one();
    
            $periodoVacacional = $model->empleado->informacionLaboral->vacaciones->segundoPeriodoVacacional;
    
            $fechaInicioOriginal = strftime('%d de %B del %Y', strtotime($periodoOriginal->fecha_inicio));
            $fechaFinOriginal = strftime('%d de %B del %Y', strtotime($periodoOriginal->fecha_final));
            $fechaInicioNuevo = strftime('%d de %B del %Y', strtotime($model->fecha_inicio_periodo));
            $fechaFinNuevo = strftime('%d de %B del %Y', strtotime($model->fecha_fin_periodo));
    
            // Colocar las fechas en las celdas correspondientes
            $sheet->setCellValue('G16', "$fechaInicioOriginal al $fechaFinOriginal");
            $sheet->setCellValue('G17', "$fechaInicioNuevo al $fechaFinNuevo");
            $sheet->setCellValue('G18', $periodoVacacional->dias_vacaciones_periodo);
            $sheet->setCellValue('G19', $periodoVacacional->dias_disponibles);
    
            if ($model->primera_vez === 'Sí') {
                $sheet->setCellValue('P23', 'X');
                $sheet->setCellValue('S23', '');
            } elseif ($model->primera_vez === 'No') {
                $sheet->setCellValue('P23', '');
                $sheet->setCellValue('S23', 'X');
            }
        }





        $sheet->setCellValue('F6', $model->empleado->numero_empleado);


        $nombre = mb_strtoupper($model->empleado->nombre, 'UTF-8');
        $apellido = mb_strtoupper($model->empleado->apellido, 'UTF-8');
        $nombreCompleto = $apellido . ' ' . $nombre;
        $sheet->setCellValue('H7', $nombreCompleto);


        setlocale(LC_TIME, 'es_419.UTF-8');
        $fechaHOY = strftime('%A, %B %d, %Y');
        $sheet->setCellValue('N6', $fechaHOY);

        $nombrePuesto = $model->empleado->informacionLaboral->catPuesto->nombre_puesto;
        $sheet->setCellValue('H8', $nombrePuesto);

        $nombreCargo = $model->empleado->informacionLaboral->catDptoCargo->nombre_dpto;
        $sheet->setCellValue('H9', $nombreCargo);

        $nombreDireccion = $model->empleado->informacionLaboral->catDireccion->nombre_direccion;
        $sheet->setCellValue('H10', $nombreDireccion);

        $nombreDepartamento = $model->empleado->informacionLaboral->catDepartamento->nombre_departamento;
        $sheet->setCellValue('H11', $nombreDepartamento);

        $nombreTipoContrato = $model->empleado->informacionLaboral->catTipoContrato->nombre_tipo;
        $sheet->setCellValue('H12', $nombreTipoContrato);


















        $sheet->setCellValue('G20', $model->motivo);






        $sheet->setCellValue('B28', $nombreCompleto);


        $sheet->setCellValue('B29', $nombrePuesto);

        // Obtener la dirección asociada al empleado
        $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

        // Verificar si la dirección no es '1.- GENERAL' y si se ha ingresado un nombre de Jefe de Departamento
        if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
            $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
            $sheet->setCellValue('I28', $nombreCompletoJefe);
        } else {
            $sheet->setCellValue('I28', null);
        }




        $juntaDirectorDireccion = JuntaGobierno::find()
            ->where(['cat_direccion_id' => $model->empleado->informacionLaboral->cat_direccion_id])
            ->andWhere(['or', ['nivel_jerarquico' => 'Director'], ['nivel_jerarquico' => 'Jefe de unidad']])
            ->one();

        if ($juntaDirectorDireccion) {
            $nombreDirector = mb_strtoupper($juntaDirectorDireccion->empleado->nombre, 'UTF-8');
            $apellidoDirector = mb_strtoupper($juntaDirectorDireccion->empleado->apellido, 'UTF-8');
            $profesionDirector = mb_strtoupper($juntaDirectorDireccion->profesion, 'UTF-8');
            $nombreCompletoDirector = $profesionDirector . ' ' . $apellidoDirector . ' ' . $nombreDirector;

            $sheet->setCellValue('O28', $nombreCompletoDirector);

            $nombreDireccion = $juntaDirectorDireccion->catDireccion->nombre_direccion;
            switch ($nombreDireccion) {
                case '1.- GENERAL':
                    if ($juntaDirectorDireccion->nivel_jerarquico == 'Jefe de unidad') {
                        $tituloDireccion = 'JEFE DE ' . $juntaDirectorDireccion->catDepartamento->nombre_departamento;
                    } else {
                        $tituloDireccion = 'DIRECTOR GENERAL';
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
                    $tituloDireccion = ''; // Otra dirección no especificada
            }

            $sheet->setCellValue('O29', $tituloDireccion);
        } else {
            $sheet->setCellValue('O28', null);
            $sheet->setCellValue('O29', null);
        }

        $tempFileName = Yii::getAlias('@app/runtime/archivos_temporales/cambio_periodo_vacacional.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFileName);

        // Luego, proporciona un enlace para que el usuario descargue el archivo
        // Puedes redirigir a una acción que presente el enlace o generar directamente el enlace aquí mismo
        return $this->redirect(['download', 'filename' => basename($tempFileName)]);
    }


    public function actionDownload($filename)
    {
        $filePath = Yii::getAlias("@app/runtime/archivos_temporales/$filename");
        if (file_exists($filePath)) {
            return Yii::$app->response->sendFile($filePath);
        } else {
            throw new NotFoundHttpException('El archivo solicitado no existe.');
        }
    }


    public function actionExportSegundoCaso($id)
    {
        // Encuentra el modelo PermisoFueraTrabajo según el ID pasado como parámetro
        $model = CambioPeriodoVacacional::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }

        // Ruta a tu plantilla de Excel
        $templatePath = Yii::getAlias('@app/templates/cambio_periodo_vacacional.xlsx');

        // Cargar la plantilla de Excel
        $spreadsheet = IOFactory::load($templatePath);

        // Modificar la plantilla con los datos del modelo
        $sheet = $spreadsheet->getActiveSheet();

        setlocale(LC_TIME, 'es_419.UTF-8');

        if ($model->numero_periodo === '1ero') {
            $sheet->setCellValue('G14', 'X');
            $sheet->setCellValue('P14', '');
            $sheet->setCellValue('I14', $model->año);
    
            // Buscar el periodo original en el historial
            $periodoOriginal = PeriodoVacacionalHistorial::find()
                ->where(['empleado_id' => $model->empleado_id, 'periodo' => 'primer periodo', 'original' => 'Si'])
                ->orderBy(['created_at' => SORT_DESC])
                ->one();
    
            $periodoVacacional = $model->empleado->informacionLaboral->vacaciones->periodoVacacional;
    
            $fechaInicioOriginal = strftime('%d de %B del %Y', strtotime($periodoOriginal->fecha_inicio));
            $fechaFinOriginal = strftime('%d de %B del %Y', strtotime($periodoOriginal->fecha_final));
            $fechaInicioNuevo = strftime('%d de %B del %Y', strtotime($model->fecha_inicio_periodo));
            $fechaFinNuevo = strftime('%d de %B del %Y', strtotime($model->fecha_fin_periodo));
    
            // Colocar las fechas en las celdas correspondientes
            $sheet->setCellValue('G16', "$fechaInicioOriginal al $fechaFinOriginal");
            $sheet->setCellValue('G17', "$fechaInicioNuevo al $fechaFinNuevo");
            $sheet->setCellValue('G18', $periodoVacacional->dias_vacaciones_periodo);
            $sheet->setCellValue('G19', $periodoVacacional->dias_disponibles);
    
            if ($model->primera_vez === 'Sí') {
                $sheet->setCellValue('P23', 'X');
                $sheet->setCellValue('S23', '');
            } elseif ($model->primera_vez === 'No') {
                $sheet->setCellValue('P23', '');
                $sheet->setCellValue('S23', 'X');
            }
    
        } elseif ($model->numero_periodo === '2do') {
            $sheet->setCellValue('G14', '');
            $sheet->setCellValue('P14', 'X');
            $sheet->setCellValue('S14', $model->año);
    
            // Buscar el periodo original en el historial
            $periodoOriginal = PeriodoVacacionalHistorial::find()
                ->where(['empleado_id' => $model->empleado_id, 'periodo' => 'segundo periodo', 'original' => 'Si'])
                ->orderBy(['created_at' => SORT_DESC])
                ->one();
    
            $periodoVacacional = $model->empleado->informacionLaboral->vacaciones->segundoPeriodoVacacional;
    
            $fechaInicioOriginal = strftime('%d de %B del %Y', strtotime($periodoOriginal->fecha_inicio));
            $fechaFinOriginal = strftime('%d de %B del %Y', strtotime($periodoOriginal->fecha_final));
            $fechaInicioNuevo = strftime('%d de %B del %Y', strtotime($model->fecha_inicio_periodo));
            $fechaFinNuevo = strftime('%d de %B del %Y', strtotime($model->fecha_fin_periodo));
    
            // Colocar las fechas en las celdas correspondientes
            $sheet->setCellValue('G16', "$fechaInicioOriginal al $fechaFinOriginal");
            $sheet->setCellValue('G17', "$fechaInicioNuevo al $fechaFinNuevo");
            $sheet->setCellValue('G18', $periodoVacacional->dias_vacaciones_periodo);
            $sheet->setCellValue('G19', $periodoVacacional->dias_disponibles);
    
            if ($model->primera_vez === 'Sí') {
                $sheet->setCellValue('P23', 'X');
                $sheet->setCellValue('S23', '');
            } elseif ($model->primera_vez === 'No') {
                $sheet->setCellValue('P23', '');
                $sheet->setCellValue('S23', 'X');
            }
        }





        $sheet->setCellValue('F6', $model->empleado->numero_empleado);


        $nombre = mb_strtoupper($model->empleado->nombre, 'UTF-8');
        $apellido = mb_strtoupper($model->empleado->apellido, 'UTF-8');
        $nombreCompleto = $apellido . ' ' . $nombre;
        $sheet->setCellValue('H7', $nombreCompleto);


        setlocale(LC_TIME, 'es_419.UTF-8');
        $fechaHOY = strftime('%A, %B %d, %Y');
        $sheet->setCellValue('N6', $fechaHOY);

        $nombrePuesto = $model->empleado->informacionLaboral->catPuesto->nombre_puesto;
        $sheet->setCellValue('H8', $nombrePuesto);

        $nombreCargo = $model->empleado->informacionLaboral->catDptoCargo->nombre_dpto;
        $sheet->setCellValue('H9', $nombreCargo);

        $nombreDireccion = $model->empleado->informacionLaboral->catDireccion->nombre_direccion;
        $sheet->setCellValue('H10', $nombreDireccion);

        $nombreDepartamento = $model->empleado->informacionLaboral->catDepartamento->nombre_departamento;
        $sheet->setCellValue('H11', $nombreDepartamento);

        $nombreTipoContrato = $model->empleado->informacionLaboral->catTipoContrato->nombre_tipo;
        $sheet->setCellValue('H12', $nombreTipoContrato);


















        $sheet->setCellValue('G20', $model->motivo);






        $sheet->setCellValue('B28', $nombreCompleto);


        $sheet->setCellValue('B29', $nombrePuesto);

        // Obtener la dirección asociada al empleado
//        $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);
//
  ///      // Verificar si la dirección no es '1.- GENERAL' y si se ha ingresado un nombre de Jefe de Departamento
     //   if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
       //     $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
        //    $sheet->setCellValue('I28', $nombreCompletoJefe);
       // } else {
         //   $sheet->setCellValue('I28', null);
       // }



       $juntaGobierno = JuntaGobierno::find()
       ->where(['nivel_jerarquico' => 'Director'])
       ->all();
       
       $directorGeneral = null;
       
       // Recorrer todos los registros de junta_gobierno encontrados
       foreach ($juntaGobierno as $junta) {
       $empleado = Empleado::findOne($junta->empleado_id);
       
       if ($empleado && $empleado->informacionLaboral->catPuesto->nombre_puesto === 'DIRECTOR GENERAL') {
           $directorGeneral = $empleado;
           break;
       }
       }
       
       // Establecer el valor en la celda N23 si se encontró un Director General
       if ($directorGeneral) {
       $nombre = mb_strtoupper($directorGeneral->nombre, 'UTF-8');
       $apellido = mb_strtoupper($directorGeneral->apellido, 'UTF-8');
       $nombreCompleto = $apellido . ' ' . $nombre;
       $sheet->setCellValue('O28', $nombreCompleto);
       } else {
       
       }
       
       $sheet->setCellValue('O29', 'DIRECTOR GENERAL');
       
       
        $tempFileName = Yii::getAlias('@app/runtime/archivos_temporales/cambio_periodo_vacacional.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFileName);

        // Luego, proporciona un enlace para que el usuario descargue el archivo
        // Puedes redirigir a una acción que presente el enlace o generar directamente el enlace aquí mismo
        return $this->redirect(['download', 'filename' => basename($tempFileName)]);
    }



}
