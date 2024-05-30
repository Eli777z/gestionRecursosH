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
}
