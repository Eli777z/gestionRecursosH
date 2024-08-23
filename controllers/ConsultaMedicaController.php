<?php

namespace app\controllers;

use Yii;
use app\models\ConsultaMedica;
use app\models\ConsultaMedicaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ExpedienteMedico;
use app\models\Empleado;
/**
 * ConsultaMedicaController implements the CRUD actions for ConsultaMedica model.
 */
class ConsultaMedicaController extends Controller
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
     * Lists all ConsultaMedica models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ConsultaMedicaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConsultaMedica model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $empleado = Empleado::findOne($model->expedienteMedico->empleado_id);

        $empleadoId = $model->expedienteMedico->empleado_id;
        return $this->render('view', [
            'model' => $model,
            //'formato' => $formato,
            'empleadoId' => $empleadoId,
            'empleado' => $empleado, // Pasar empleado a la vista


        ]);
    }

    

    /**
     * Creates a new ConsultaMedica model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($expediente_medico_id = null)
    {
        $model = new ConsultaMedica();
    
        if ($expediente_medico_id !== null) {
            $model->expediente_medico_id = $expediente_medico_id;
    
            // Suponiendo que puedes obtener el expediente médico y de allí el empleado
            $expedienteMedico = ExpedienteMedico::findOne($expediente_medico_id);
            $empleadoId = $expedienteMedico ? $expedienteMedico->empleado_id : null;
            $empleadoNombre = $expedienteMedico ? $expedienteMedico->empleado->nombre. ' '. $expedienteMedico->empleado->apellido : null; // Suponiendo que el modelo Empleado tiene el atributo nombre_completo
        } else {
            $empleadoId = null;
            $empleadoNombre = null;
        }
    
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->created_at = date('Y-m-d');
    
            return $this->redirect(['view', 'id' => $model->id]);
        }
    
        return $this->render('create', [
            'model' => $model,
            'empleadoId' => $empleadoId,
            'empleadoNombre' => $empleadoNombre,
        ]);
    }
    


    /**
     * Updates an existing ConsultaMedica model.
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
     * Deletes an existing ConsultaMedica model.
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
     * Finds the ConsultaMedica model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ConsultaMedica the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConsultaMedica::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
