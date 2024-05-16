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
    // Obtener el ID del usuario que ha iniciado sesión
    $usuarioId = Yii::$app->user->identity->id;

    // Buscar el modelo de Empleado asociado al usuario actual
    $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

    // Verificar si se encontró el empleado
    if ($empleado !== null) {
        // Si se encontró el empleado, utilizar su ID para filtrar los registros
        $searchModel = new PermisoFueraTrabajoSearch();
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
     * Displays a single PermisoFueraTrabajo model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = "main-trabajador";

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PermisoFueraTrabajo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   

    public function actionCreate()
{
    $model = new PermisoFueraTrabajo();
    $motivoFechaPermisoModel = new MotivoFechaPermiso();
    $solicitudModel = new Solicitud();

    // Obtener el ID del usuario que inició sesión
    $usuarioId = Yii::$app->user->identity->id;

    // Buscar el modelo de Empleado asociado al usuario
    $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

    if ($empleado) {
        // Si se encontró el empleado, establecer su ID en el modelo PermisoFueraTrabajo
        $model->empleado_id = $empleado->id;
    } else {
        // Si no se encuentra el empleado, manejar el escenario apropiado (por ejemplo, redireccionar o mostrar un mensaje de error)
        Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
        return $this->redirect(['index']); // Redirecciona a la página de índice u otra página apropiada
    }

    if ($model->load(Yii::$app->request->post()) && $motivoFechaPermisoModel->load(Yii::$app->request->post())) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($motivoFechaPermisoModel->save()) {
                $model->motivo_fecha_permiso_id = $motivoFechaPermisoModel->id;
                $model->hora_salida = date('H:i:s', strtotime($model->hora_salida));
                $model->hora_regreso = date('H:i:s', strtotime($model->hora_regreso));

                $model->horario_fecha_a_reponer = date('H:i:s', strtotime($model->horario_fecha_a_reponer));

                // Crear registro en la tabla de Solicitud
                $solicitudModel->save(false); // Utiliza save(false) para omitir la validación

                // Asignar el ID de la solicitud recién creada al modelo PermisoFueraTrabajo
                $model->solicitud_id = $solicitudModel->id;

                if ($model->save()) {
                    $transaction->commit();

                    // Llama a la acción actionExport para generar el archivo Excel
                    return $this->actionExport($model->id);
                }
            }
            $transaction->rollBack();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    return $this->render('create', [
        'model' => $model,
        'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
        'solicitudModel' => $solicitudModel,
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

    
    
    public function actionExport($id)
    {
        // Encuentra el modelo PermisoFueraTrabajo según el ID pasado como parámetro
        $model = PermisoFueraTrabajo::findOne($id);
    
        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }
    
        // Ruta a tu plantilla de Excel
        $templatePath = Yii::getAlias('@app/templates/permiso_fuera_trabajo.xlsx');
    
        // Cargar la plantilla de Excel
        $spreadsheet = IOFactory::load($templatePath);
    
        // Modificar la plantilla con los datos del modelo
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



// Convertir la fecha del modelo al formato deseado
$fecha_permiso = strftime('%A, %B %d, %Y', strtotime($model->motivoFechaPermiso->fecha_permiso));
$sheet->setCellValue('H14', $fecha_permiso);


$horaSalida = date("g:i A", strtotime($model->hora_salida));
$horaRegreso = date("g:i A", strtotime($model->hora_regreso));
$horaAReponer = date("g:i A", strtotime($model->horario_fecha_a_reponer));

$sheet->setCellValue('H15', $horaSalida);
$sheet->setCellValue('H16', $horaRegreso);


$sheet->setCellValue('H18', $model->motivoFechaPermiso->motivo);
$fecha_a_reponer = strftime('%A, %B %d, %Y', strtotime($model->fecha_a_reponer));
$sheet->setCellValue('H20', $fecha_a_reponer);

$sheet->setCellValue('P20', $horaAReponer);
$sheet->setCellValue('A32', $nombreCompleto);

$nombreJefe = $model->empleado->informacionLaboral->juntaGobierno->empleado->nombre;
$sheet->setCellValue('H32', $nombreJefe);

$sheet->setCellValue('A33', $nombrePuesto);







        // $sheet->setCellValue('G6', $model->otra_propiedad); // Cambia 'G6' y 'otra_propiedad' según tu plantilla y modelo
    
        // Preparar el archivo para la descarga
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    
        // Enviar el archivo al navegador para su descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="permiso_fuera_trabajo.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
        Yii::$app->end();
    }
    
    
}
