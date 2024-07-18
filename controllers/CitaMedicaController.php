<?php

namespace app\controllers;

use Yii;
use app\models\CitaMedica;
use app\models\CitaMedicaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Solicitud;
use app\models\Empleado;
/**
 * CitaMedicaController implements the CRUD actions for CitaMedica model.
 */
class CitaMedicaController extends Controller
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
     * Lists all CitaMedica models.
     * @return mixed
     */
    public function actionIndex()
    {
        
    $usuarioId = Yii::$app->user->identity->id;

    $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

    if ($empleado !== null) {
        $searchModel = new CitaMedicaSearch();
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
     * Displays a single CitaMedica model.
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

    $searchModel = new CitaMedicaSearch();
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
     * Creates a new CitaMedica model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($empleado_id = null)
{
    $model = new CitaMedica();
    $solicitudModel = new Solicitud();
    

    // Obtener el empleado según el contexto
    if ($empleado_id) {
        // Caso 2: Si se proporciona $empleado_id, es probablemente desde el médico
        $empleado = Empleado::findOne($empleado_id);
    } else {
        // Caso 1: Si no se proporciona $empleado_id, obtener el empleado del usuario logueado
        $usuarioId = Yii::$app->user->identity->id;
        $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();
    }

    if (!$empleado) {
        Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual o al empleado específico.');
        return $this->redirect(['index']);
    }

    $model->empleado_id = $empleado->id;

    if ($model->load(Yii::$app->request->post())) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Guardar la solicitud primero
            $solicitudModel->empleado_id = $empleado->id;
            $solicitudModel->status = 'En Proceso';
            $solicitudModel->comentario = '';
            $solicitudModel->fecha_aprobacion = null;
            $solicitudModel->fecha_creacion = date('Y-m-d H:i:s');
            $solicitudModel->nombre_formato = 'CITA MEDICA';

            if ($solicitudModel->save()) {
                $model->solicitud_id = $solicitudModel->id;

                if ($model->save()) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'La cita médica y la solicitud han sido creadas exitosamente.');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Hubo un error al guardar la cita médica.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al guardar la solicitud.');
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
        'empleado' => $empleado, // Pasar empleado a la vista

    ]);
}

    /**
     * Updates an existing CitaMedica model.
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
     * Deletes an existing CitaMedica model.
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
     * Finds the CitaMedica model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CitaMedica the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CitaMedica::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
