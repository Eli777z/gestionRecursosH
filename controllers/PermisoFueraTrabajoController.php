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
        $searchModel = new PermisoFueraTrabajoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $this->layout = "main-trabajador";

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
                    $model->horario_fecha_a_reponer = date('H:i:s', strtotime($model->horario_fecha_a_reponer));

                    // Crear registro en la tabla de Solicitud
                    $solicitudModel->save(false); // Utiliza save(false) para omitir la validación
    
                    // Asignar el ID de la solicitud recién creada al modelo PermisoFueraTrabajo
                    $model->solicitud_id = $solicitudModel->id;
    
                    if ($model->save()) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
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
}
