<?php

namespace app\controllers;

use app\models\MotivoFechaPermiso;
use Yii;
use app\models\PermisoFueraTrabajo;
use app\models\PermisoFueraTrabajoSearch;
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
use yii\helpers\Url;
use Mpdf\Mpdf;
/**
 * PermisoFueraTrabajoController implements the CRUD actions for PermisoFueraTrabajo model.
 */
class PermisoFueraTrabajoController extends Controller
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
     * Lists all PermisoFueraTrabajo models.
     * @return mixed
     */
  public function actionIndex()
{
    $usuarioId = Yii::$app->user->identity->id;

    $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

    if ($empleado !== null) {
        $searchModel = new PermisoFueraTrabajoSearch();
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

public function actionHistorial($empleado_id= null)
{
    $empleado = Empleado::findOne($empleado_id);

    if ($empleado === null) {
        throw new NotFoundHttpException('El empleado seleccionado no existe.');
    }

    $searchModel = new PermisoFueraTrabajoSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->query->andFilterWhere(['empleado_id' => $empleado->id]);

    $this->layout = "main-trabajador";

    return $this->render('historial', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'empleado' => $empleado,
    ]);
}


    /**
     * Displays a single PermisoFueraTrabajo model.
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

    /**
     * Creates a new PermisoFueraTrabajo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   

 

     public function actionCreate($empleado_id = null)
     {
         $this->layout = "main-trabajador";
     
         $model = new PermisoFueraTrabajo();
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
     
         // Calcular permisos usados y disponibles
         $year = date('Y');
         $tipoContratoId = $empleado->informacionLaboral->cat_tipo_contrato_id;

         $parametroFormato = ParametroFormato::find()
             ->where(['tipo_permiso' => 'PERMISO FUERA DEL TRABAJO', 'cat_tipo_contrato_id' => $tipoContratoId])
             ->one();
         
         if (!$parametroFormato) {
             Yii::$app->session->setFlash('error', 'No se pudo encontrar el parámetro de formato para tu tipo de contrato.');
             return $this->redirect(['index']);
         }
         
     
         $totalPermisosAnuales = $parametroFormato->limite_anual;
     
         $permisosUsados = PermisoFueraTrabajo::find()
             ->where(['empleado_id' => $empleado->id])
             ->andWhere(['between', 'fecha_a_reponer', "$year-01-01", "$year-12-31"])
             ->count();
     
         $permisosDisponibles = $totalPermisosAnuales - $permisosUsados;
     
         // Verificar si se alcanzó el límite de permisos
         if ($permisosDisponibles <= 0) {
             Yii::$app->session->setFlash('error', 'Has alcanzado el límite anual de permisos.');
           //  return $this->redirect(['index']);
         }
     
         if ($model->load(Yii::$app->request->post()) && $motivoFechaPermisoModel->load(Yii::$app->request->post())) {
             $transaction = Yii::$app->db->beginTransaction();
             try {
                 if ($motivoFechaPermisoModel->save()) {
                     $model->motivo_fecha_permiso_id = $motivoFechaPermisoModel->id;
                     $model->hora_salida = date('H:i:s', strtotime($model->hora_salida));
                     $model->hora_regreso = date('H:i:s', strtotime($model->hora_regreso));
                     $model->horario_fecha_a_reponer = date('H:i:s', strtotime($model->horario_fecha_a_reponer));
     
                     $solicitudModel->empleado_id = $empleado->id;
                     $solicitudModel->status = 'Nueva';
                     $solicitudModel->comentario = '';
                     $solicitudModel->fecha_aprobacion = null;
                     $solicitudModel->fecha_creacion = date('Y-m-d H:i:s');
                     $solicitudModel->nombre_formato = 'PERMISO FUERA DEL TRABAJO';
                     if ($solicitudModel->save()) {
                         $model->solicitud_id = $solicitudModel->id;
     
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
             'empleado' => $empleado,
             'permisosUsados' => $permisosUsados,
             'permisosDisponibles' => $permisosDisponibles,
         ]);
     }
     
    
    

    /**
     * Updates an existing PermisoFueraTrabajo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $this->layout = "main-trabajador";

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            
        ]);
    }

    /**
     * Deletes an existing PermisoFueraTrabajo model.
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
     * Finds the PermisoFueraTrabajo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PermisoFueraTrabajo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PermisoFueraTrabajo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
 
    }

    

    
    
   

    public function actionExportHtmlSegundoCaso($id)
    {
        $this->layout = false;

        $model = PermisoFueraTrabajo::findOne($id);
    
        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }
    
        $templatePath = Yii::getAlias('@app/templates/permiso_fuera_trabajo.xlsx');
    
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


$horaSalida = date("g:i A", strtotime($model->hora_salida));
$horaRegreso = date("g:i A", strtotime($model->hora_regreso));
$horaAReponer = date("g:i A", strtotime($model->horario_fecha_a_reponer));

$sheet->setCellValue('H15', $horaSalida);
$sheet->setCellValue('H16', $horaRegreso);

// Clean and set the motivo text
$motivoTextoPlano = strip_tags($model->motivoFechaPermiso->motivo);
$motivoTextoPlano = html_entity_decode($motivoTextoPlano, ENT_QUOTES | ENT_HTML5, 'UTF-8');
$sheet->setCellValue('H18', $motivoTextoPlano);

$fecha_a_reponer = strftime('%A, %B %d, %Y', strtotime($model->fecha_a_reponer));
$sheet->setCellValue('H20', $fecha_a_reponer);

$sheet->setCellValue('P20', $horaAReponer);
$sheet->setCellValue('A32', $nombreCompleto);






$sheet->setCellValue('A33', $nombrePuesto);


//$direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

///if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
   // $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
    //$sheet->setCellValue('H32', $nombreCompletoJefe);
//} else {
  //  $sheet->setCellValue('H32', null);
//}

$nombre = mb_strtoupper($model->empleado->nombre, 'UTF-8');
$apellido = mb_strtoupper($model->empleado->apellido, 'UTF-8');
$profesion = mb_strtoupper($model->empleado->profesion, 'UTF-8');
$nombreCompleto = $profesion.''.$apellido . ' ' . $nombre;
$sheet->setCellValue('H32', $nombreCompleto);


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
$sheet->setCellValue('N32', $nombreCompleto);
} else {

}

$sheet->setCellValue('N33', 'DIRECTOR GENERAL');
 // Establecer el área de impresión
 $htmlWriter = new Html($spreadsheet);
 $htmlWriter->setSheetIndex(0); 
 $htmlWriter->setPreCalculateFormulas(false);

 $fullHtmlContent = $htmlWriter->generateHtmlAll();



 $fullHtmlContent = str_replace('&nbsp;', ' ', $fullHtmlContent);

 return $this->render('excel-html', ['htmlContent' => $fullHtmlContent, 'model' => $model]);
    }





public function actionExportHtml($id)
{
    $this->layout = false;

    $model = PermisoFueraTrabajo::findOne($id);

    if (!$model) {
        throw new NotFoundHttpException('El registro no existe.');
    }

    $templatePath = Yii::getAlias('@app/templates/permiso_fuera_trabajo.xlsx');

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


$horaSalida = date("g:i A", strtotime($model->hora_salida));
$horaRegreso = date("g:i A", strtotime($model->hora_regreso));
$horaAReponer = date("g:i A", strtotime($model->horario_fecha_a_reponer));

$sheet->setCellValue('H15', $horaSalida);
$sheet->setCellValue('H16', $horaRegreso);


 
// Clean and set the motivo text
$motivoTextoPlano = strip_tags($model->motivoFechaPermiso->motivo);
$motivoTextoPlano = html_entity_decode($motivoTextoPlano, ENT_QUOTES | ENT_HTML5, 'UTF-8');
$sheet->setCellValue('H18', $motivoTextoPlano);


$fecha_a_reponer = strftime('%A, %B %d, %Y', strtotime($model->fecha_a_reponer));
$sheet->setCellValue('H20', $fecha_a_reponer);

$sheet->setCellValue('P20', $horaAReponer);
$sheet->setCellValue('A32', $nombreCompleto);






$sheet->setCellValue('A33', $nombrePuesto);

$direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' ) {
    $nombreJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->nombre, 'UTF-8');
        $apellidoJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->apellido, 'UTF-8');
        $profesionJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->profesion, 'UTF-8');
        $nombreCompletoJefe = $profesionJefe . ' ' . $apellidoJefe . ' ' . $nombreJefe;
    
$sheet->setCellValue('H32', $nombreCompletoJefe);
} else {
$sheet->setCellValue('H32', null);
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

$sheet->setCellValue('N32', $nombreCompletoDirector);

$nombreDireccion = $juntaDirectorDireccion->catDireccion->nombre_direccion;
switch ($nombreDireccion) {
    case '1.- GENERAL':
        if ($model->empleado->informacionLaboral->juntaGobierno->nivel_jerarquico == 'Jefe de unidad') {
            $nombreJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->nombre, 'UTF-8');
            $apellidoJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->apellido, 'UTF-8');
            $profesionJefe = mb_strtoupper($model->empleado->informacionLaboral->juntaGobierno->empleado->profesion, 'UTF-8');
            $nombreCompletoJefe = $profesionJefe . ' ' . $apellidoJefe . ' ' . $nombreJefe;
    
$sheet->setCellValue('N32', $nombreCompletoJefe);
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

$sheet->setCellValue('N33', $tituloDireccion);
} else {
$sheet->setCellValue('N32', null);
$sheet->setCellValue('N33', null);
}
 // Establecer el área de impresión
 $htmlWriter = new Html($spreadsheet);
 $htmlWriter->setSheetIndex(0); 
 $htmlWriter->setPreCalculateFormulas(false);

 $fullHtmlContent = $htmlWriter->generateHtmlAll();



 $fullHtmlContent = str_replace('&nbsp;', ' ', $fullHtmlContent);

 return $this->render('excel-html', ['htmlContent' => $fullHtmlContent, 'model' => $model]);
}



}
