<?php
namespace app\controllers;
use Yii;
use app\models\ComisionEspecial;
use app\models\ComisionEspecialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\JuntaGobierno;
use app\models\CatDireccion;
use app\models\Notificacion;
use app\models\Solicitud;
use app\models\Empleado;
use app\models\MotivoFechaPermiso;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Mpdf\Mpdf;
use yii\helpers\Url;

use app\models\ParametroFormato;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Writer\Html;

/**
 * ComisionEspecialController implements the CRUD actions for ComisionEspecial model.
 */
class ComisionEspecialController extends Controller
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
     * Lists all ComisionEspecial models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioId = Yii::$app->user->identity->id;
        $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();
        if ($empleado !== null) {
            $searchModel = new ComisionEspecialSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->andFilterWhere(['empleado_id' => $empleado->id]);
            $this->layout = "main-trabajador";
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
     * Displays a single ComisionEspecial model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $empleado = Empleado::findOne($model->empleado_id);
    
        return $this->render('view', [
            'model' => $model,
            'empleado' => $empleado, // Pasar empleado a la vista
        ]);
    }

    public function actionHistorial($empleado_id= null)
{
    $empleado = Empleado::findOne($empleado_id);

    if ($empleado === null) {
        throw new NotFoundHttpException('El empleado seleccionado no existe.');
    }

    $searchModel = new ComisionEspecialSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->query->andFilterWhere(['empleado_id' => $empleado->id]);

    $this->layout = "main-trabajador";

    $year = date('Y');
    $tipoContratoId = $empleado->informacionLaboral->cat_tipo_contrato_id;

    $parametroFormato = ParametroFormato::find()
        ->where(['tipo_permiso' => 'COMISION ESPECIAL', 'cat_tipo_contrato_id' => $tipoContratoId])
        ->one();
    
    if (!$parametroFormato) {
        Yii::$app->session->setFlash('error', 'No se pudo encontrar el parámetro de formato para tu tipo de contrato.');
        return $this->redirect(['empleado/index']);
    }

    $totalPermisosAnuales = $parametroFormato->limite_anual;

    // Contar los permisos usados en el año actual basados en la fecha del permiso de la relación motivo_fecha_permiso
    $permisosUsados = ComisionEspecial::find()
    ->joinWith(['motivoFechaPermiso', 'solicitud']) // Asegurar unión con ambas relaciones
    ->where(['comision_especial.empleado_id' => $empleado->id])
    ->andWhere(['between', 'motivo_fecha_permiso.fecha_permiso', "$year-01-01", "$year-12-31"])
    ->andWhere(['solicitud.activa' => 1]) // Solo contar las solicitudes activas
    ->count();

$permisosDisponibles = $totalPermisosAnuales - $permisosUsados;

// Verificar si se alcanzó el límite de permisos
if ($permisosDisponibles <= 0) {
    Yii::$app->session->setFlash('error', 'Has alcanzado el límite anual de permisos.');
    // return $this->redirect(['index']);
}

    return $this->render('historial', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'empleado' => $empleado,
        'permisosUsados' => $permisosUsados,
        'permisosDisponibles' => $permisosDisponibles,
    ]);
}


public function actionGuardarComentario()
{
    $comentarios = Yii::$app->request->post('comentario', []);
    $id = Yii::$app->request->post('id');

    if (!empty($comentarios[$id])) {
        $model = ComisionEspecial::findOne($id);
        if ($model !== null) {
            $model->comentario = $comentarios[$id];
            if ($model->save(false)) {
                // Encuentra al empleado asociado
                $empleado = Empleado::findOne($model->empleado_id);

                if ($empleado !== null) {
                    Yii::$app->session->setFlash('success', 'El comentario se ha guardado exitosamente.');
                    $url = Url::to(['historial', 'empleado_id' => $empleado->id]); // Redirige a la vista del empleado
                    return $this->redirect($url);
                } else {
                    Yii::$app->session->setFlash('error', 'El empleado asociado no fue encontrado.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al guardar el comentario.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'El registro de permiso no fue encontrado.');
        }
    } else {
        Yii::$app->session->setFlash('error', 'No se proporcionó comentario para guardar.');
    }

    return $this->redirect(['index']); // Redirige a la vista de índice si ocurre algún error
}



    /**
     * Creates a new ComisionEspecial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($empleado_id = null)
    {
        $this->layout = "main-trabajador";
    
        $model = new ComisionEspecial();
        $motivoFechaPermisoModel = new MotivoFechaPermiso();
        $solicitudModel = new Solicitud();
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
         // Lógica para verificar si se han creado solicitudes muy recientes
         $nombreFormato = 'COMISION ESPECIAL';
         $tiempoLimite = new \DateTime('-24 hours');
         
         $solicitudReciente = Solicitud::find()
             ->where(['empleado_id' => $empleado->id, 'nombre_formato' => $nombreFormato])
             ->andWhere(['>', 'created_at', $tiempoLimite->format('Y-m-d H:i:s')])
             ->orderBy(['created_at' => SORT_DESC])
             ->one();
         
         if ($solicitudReciente) {
             $fechaUltimaSolicitud = new \DateTime($solicitudReciente->created_at);
             $intervalo = $fechaUltimaSolicitud->diff(new \DateTime());
             
             // Convertir el intervalo a un formato amigable (días, horas, minutos)
             $tiempoTranscurrido = '';
             if ($intervalo->d > 0) {
                 $tiempoTranscurrido .= $intervalo->d . ' día(s) ';
             }
             if ($intervalo->h > 0) {
                 $tiempoTranscurrido .= $intervalo->h . ' hora(s) ';
             }
             if ($intervalo->i > 0) {
                 $tiempoTranscurrido .= $intervalo->i . ' minuto(s)';
             }
         
             Yii::$app->session->setFlash('warning', '<i class="fas fa-bell"></i> Ya has creado una solicitud recientemente. Última solicitud hace: ' . $tiempoTranscurrido);
         }
    
        $year = date('Y');
        $tipoContratoId = $empleado->informacionLaboral->cat_tipo_contrato_id;
    
        $parametroFormato = ParametroFormato::find()
            ->where(['tipo_permiso' => 'COMISION ESPECIAL', 'cat_tipo_contrato_id' => $tipoContratoId])
            ->one();
        
        if (!$parametroFormato) {
            Yii::$app->session->setFlash('error', 'No se pudo encontrar el parámetro de formato para tu tipo de contrato.');
            return $this->redirect(['empleado/index']);
        }
    
        $totalPermisosAnuales = $parametroFormato->limite_anual;
    
        // Contar los permisos usados en el año actual basados en la fecha del permiso de la relación motivo_fecha_permiso
        $permisosUsados = ComisionEspecial::find()
        ->joinWith(['motivoFechaPermiso', 'solicitud']) // Asegurar unión con ambas relaciones
        ->where(['comision_especial.empleado_id' => $empleado->id])
        ->andWhere(['between', 'motivo_fecha_permiso.fecha_permiso', "$year-01-01", "$year-12-31"])
        ->andWhere(['solicitud.activa' => 1]) // Solo contar las solicitudes activas
        ->count();
    
    $permisosDisponibles = $totalPermisosAnuales - $permisosUsados;
    
    // Verificar si se alcanzó el límite de permisos
    if ($permisosDisponibles <= 0) {
        Yii::$app->session->setFlash('error', 'Has alcanzado el límite anual de permisos.');
        // return $this->redirect(['index']);
    }
    
        if ($motivoFechaPermisoModel->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($motivoFechaPermisoModel->save()) {
                    $model->motivo_fecha_permiso_id = $motivoFechaPermisoModel->id;
        
                    $solicitudModel->empleado_id = $empleado->id;
                    $solicitudModel->status = 'Nueva';
                    $solicitudModel->comentario = '';
                    $solicitudModel->fecha_aprobacion = null;
                    $solicitudModel->fecha_creacion = date('Y-m-d H:i:s');
                    $solicitudModel->nombre_formato = 'COMISION ESPECIAL';
                    $solicitudModel->activa = 1;
        
                    if ($solicitudModel->save()) {
                        $model->solicitud_id = $solicitudModel->id;
        
                        $model->load(Yii::$app->request->post());
        
                        // Aquí establecemos el valor inicial de 'status' a 1
                        $model->status = 1;
        
                        if ($model->jefe_departamento_id) {
                            $jefeDepartamento = JuntaGobierno::findOne($model->jefe_departamento_id);
                            $model->nombre_jefe_departamento = $jefeDepartamento ? $jefeDepartamento->empleado->profesion . ' ' . $jefeDepartamento->empleado->nombre . ' ' . $jefeDepartamento->empleado->apellido : null;
                        }
        
                        if ($model->save()) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Su solicitud ha sido generada exitosamente.');
        
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
            'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
            'solicitudModel' => $solicitudModel,
            'empleado' => $empleado, // Pasar empleado a la vista
            'permisosUsados' => $permisosUsados,
            'permisosDisponibles' => $permisosDisponibles,
        ]);
    }
    
    /**
     * Updates an existing ComisionEspecial model.
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
     * Deletes an existing ComisionEspecial model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id); // Encuentra el modelo de ComisionEspecial
        $empleado = Empleado::findOne($model->empleado_id); // Encuentra al empleado asociado
    
        if ($model === null) {
            Yii::$app->session->setFlash('error', 'El registro de permiso no fue encontrado.');
            return $this->redirect(['index']);
        }
        
        $transaction = Yii::$app->db->beginTransaction(); // Inicia la transacción
        try {
            // Obtener el ID de la solicitud asociada
            $solicitudId = $model->solicitud_id;
    
            // Cambiar el atributo 'status' del modelo ComisionEspecial a 0
            $model->status = 0;
            if ($model->save(false)) { // Guardar el modelo sin validación
                // Buscar el registro asociado en la tabla Solicitud y actualizar su estado
                $solicitudModel = Solicitud::findOne($solicitudId);
                if ($solicitudModel !== null) {
                    $solicitudModel->activa = 0; // Cambiar el atributo 'activa' a 0
                    $solicitudModel->save(false); // Guardar el modelo sin validación
                }
                $transaction->commit(); // Confirmar la transacción
    
                Yii::$app->session->setFlash('success', 'El registro de permiso y la solicitud asociada han sido cancelados correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al cancelar el registro de permiso.');
            }
        } catch (\Exception $e) {
            $transaction->rollBack(); // Revertir la transacción en caso de error
            Yii::$app->session->setFlash('error', 'Hubo un error al cancelar el registro: ' . $e->getMessage());
        } catch (\Throwable $e) {
            $transaction->rollBack(); // Revertir la transacción en caso de error
            Yii::$app->session->setFlash('error', 'Hubo un error al cancelar el registro: ' . $e->getMessage());
        }
    
        if (Yii::$app->user->can('gestor-rh')) {
            Yii::$app->session->setFlash('success', 'El registro se ha cancelado exitosamente.');
            $url = Url::to(['historial', 'empleado_id' => $empleado->id]);
            return $this->redirect($url);
        } else {
            return $this->redirect(['index']);
        }
    }



    public function actionRestore($id)
{
    $model = $this->findModel($id); // Encuentra el modelo de ComisionEspecial
    $empleado = Empleado::findOne($model->empleado_id); // Encuentra al empleado asociado
    
    if ($model === null) {
        Yii::$app->session->setFlash('error', 'El registro de permiso no fue encontrado.');
        return $this->redirect(['index']);
    }

    $transaction = Yii::$app->db->beginTransaction(); // Inicia la transacción
    try {
        // Obtener el ID de la solicitud asociada
        $solicitudId = $model->solicitud_id;

        // Cambiar el atributo 'status' del modelo ComisionEspecial a 1 (activo/restaurado)
        $model->status = 1;
        if ($model->save(false)) { // Guardar el modelo sin validación
            // Buscar el registro asociado en la tabla Solicitud y actualizar su estado
            $solicitudModel = Solicitud::findOne($solicitudId);
            if ($solicitudModel !== null) {
                $solicitudModel->activa = 1; // Cambiar el atributo 'activa' a 1 (restaurado)
                $solicitudModel->save(false); // Guardar el modelo sin validación
            }
            $transaction->commit(); // Confirmar la transacción

            Yii::$app->session->setFlash('success', 'El registro de permiso y la solicitud asociada han sido restaurados correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al restaurar el registro de permiso.');
        }
    } catch (\Exception $e) {
        $transaction->rollBack(); // Revertir la transacción en caso de error
        Yii::$app->session->setFlash('error', 'Hubo un error al restaurar el registro: ' . $e->getMessage());
    } catch (\Throwable $e) {
        $transaction->rollBack(); // Revertir la transacción en caso de error
        Yii::$app->session->setFlash('error', 'Hubo un error al restaurar el registro: ' . $e->getMessage());
    }

    if (Yii::$app->user->can('gestor-rh')) {
        Yii::$app->session->setFlash('success', 'El registro se ha restaurado exitosamente.');
        $url = Url::to(['historial', 'empleado_id' => $empleado->id]);
        return $this->redirect($url);
    } else {
        return $this->redirect(['index']);
    }
}

    
    /**
     * Finds the ComisionEspecial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ComisionEspecial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ComisionEspecial::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExportHtml($id)
{
    $this->layout = false;
    $model = ComisionEspecial::findOne($id);

    if (!$model) {
        throw new NotFoundHttpException('El registro no existe.');
    }

    $templatePath = Yii::getAlias('@app/templates/comision_especial.xlsx');
    $spreadsheet = IOFactory::load($templatePath);
    $sheet = $spreadsheet->getActiveSheet();

    // Set cell values
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

    // Define styles for different contract types
 
    // Apply conditional formatting based on the contract type
    switch ($nombreTipoContrato) {
        case 'Confianza':
            $style = $sheet->getStyle('H12');
            $style->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $style->getFill()->getStartColor()->setARGB('FFc7efce'); // Background color #c7efce
            $style->getFont()->getColor()->setARGB('FF217346'); // Font color #217346
            break;
        case 'Sindicalizado':
            $style = $sheet->getStyle('H12');
            $style->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $style->getFill()->getStartColor()->setARGB('FFfeeb9d'); // Background color #c7efce
            $style->getFont()->getColor()->setARGB('FFa7720f'); // Font color #217346
            break;
    }

    $fecha_permiso = strftime('%A, %B %d, %Y', strtotime($model->motivoFechaPermiso->fecha_permiso));
    $sheet->setCellValue('H14', $fecha_permiso);

    // Clean and set the motivo text
    $motivoTextoPlano = strip_tags($model->motivoFechaPermiso->motivo);
    $motivoTextoPlano = html_entity_decode($motivoTextoPlano, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $sheet->setCellValue('H15', $motivoTextoPlano);

    $sheet->setCellValue('A23', $nombreCompleto);
    $sheet->setCellValue('A24', $nombrePuesto);

    $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

    if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL') {
        $nombreJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->nombre, 'UTF-8');
        $apellidoJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->apellido, 'UTF-8');
        $profesionJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->profesion, 'UTF-8');
        $nombreCompletoJefe = $profesionJefe . ' ' . $apellidoJefe . ' ' . $nombreJefe;
     $sheet->setCellValue('H23', $nombreCompletoJefe);
    } else {
        $sheet->setCellValue('H23', null);
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

        $sheet->setCellValue('N23', $nombreCompletoDirector);

        $nombreDireccion = $juntaDirectorDireccion->catDireccion->nombre_direccion;
        switch ($nombreDireccion) {
            case '1.- GENERAL':
                if ($model->empleado->informacionLaboral->juntaGobierno->nivel_jerarquico == 'Jefe de unidad') {
                    $nombreJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->nombre, 'UTF-8');
        $apellidoJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->apellido, 'UTF-8');
        $profesionJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->profesion, 'UTF-8');
        $nombreCompletoJefe = $profesionJefe . ' ' . $apellidoJefe . ' ' . $nombreJefe;
        $sheet->setCellValue('N23', $nombreCompletoJefe);
  
        $tituloDireccion = 'JEFE DE ' . $model->empleado->informacionLaboral->juntaGobierno->empleado->informacionLaboral->catDepartamento->nombre_departamento;
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
                $tituloDireccion = '';
        }

        $sheet->setCellValue('N24', $tituloDireccion);
    } else {
        $sheet->setCellValue('N23', null);
        $sheet->setCellValue('N24', null);
    }

    // Establecer el área de impresión
    $htmlWriter = new Html($spreadsheet);
    $htmlWriter->setSheetIndex(0); 
    $htmlWriter->setPreCalculateFormulas(false);

    $fullHtmlContent = $htmlWriter->generateHtmlAll();

    // Clean up HTML content to ensure no &nbsp; and other unwanted characters
    $fullHtmlContent = str_replace('&nbsp;', ' ', $fullHtmlContent);

    return $this->render('excel-html', ['htmlContent' => $fullHtmlContent, 'model' => $model]);
}


   


    public function actionExportHtmlSegundoCaso($id)
    {
        $this->layout = false;

        $model = ComisionEspecial::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }

        $templatePath = Yii::getAlias('@app/templates/comision_especial.xlsx');


        $spreadsheet = IOFactory::load($templatePath);

        $sheet = $spreadsheet->getActiveSheet();
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

        switch ($nombreTipoContrato) {
            case 'Confianza':
                $style = $sheet->getStyle('H12');
                $style->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $style->getFill()->getStartColor()->setARGB('FFc7efce'); // Background color #c7efce
                $style->getFont()->getColor()->setARGB('FF217346'); // Font color #217346
                break;
            case 'Sindicalizado':
                $style = $sheet->getStyle('H12');
                $style->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $style->getFill()->getStartColor()->setARGB('FFfeeb9d'); // Background color #c7efce
                $style->getFont()->getColor()->setARGB('FFa7720f'); // Font color #217346
                break;
        }

        $fecha_permiso = strftime('%A, %B %d, %Y', strtotime($model->motivoFechaPermiso->fecha_permiso));
        $sheet->setCellValue('H14', $fecha_permiso);


         // Clean and set the motivo text
    $motivoTextoPlano = strip_tags($model->motivoFechaPermiso->motivo);
    $motivoTextoPlano = html_entity_decode($motivoTextoPlano, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $sheet->setCellValue('H15', $motivoTextoPlano);


        $sheet->setCellValue('A23', $nombreCompleto);
        $sheet->setCellValue('A24', $nombrePuesto);


        
//        $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

       // if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
         //   $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
           // $sheet->setCellValue('H23', $nombreCompletoJefe);
        //} else {
          //  $sheet->setCellValue('H23', null);
        //}
       
        $nombre = mb_strtoupper($model->empleado->nombre, 'UTF-8');
$apellido = mb_strtoupper($model->empleado->apellido, 'UTF-8');
$profesion = mb_strtoupper($model->empleado->profesion, 'UTF-8');
$nombreCompleto = $profesion.''.$apellido . ' ' . $nombre;
$sheet->setCellValue('H23', $nombreCompleto);
      
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
            $sheet->setCellValue('N23', $nombreCompleto);
        } else {
          
        }
        
        $sheet->setCellValue('N24', 'DIRECTOR GENERAL');
        
        

        $htmlWriter = new Html($spreadsheet);
    $htmlWriter->setSheetIndex(0); 
    $htmlWriter->setPreCalculateFormulas(false);

    $fullHtmlContent = $htmlWriter->generateHtmlAll();

    // Clean up HTML content to ensure no &nbsp; and other unwanted characters
    $fullHtmlContent = str_replace('&nbsp;', ' ', $fullHtmlContent);

    return $this->render('excel-html', ['htmlContent' => $fullHtmlContent, 'model' => $model]);
    }
   













   


    
}
