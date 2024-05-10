<?php

namespace app\controllers;

use Yii;
use app\models\JuntaGobierno;
use app\models\JuntaGobiernoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JuntaGobiernoController implements the CRUD actions for JuntaGobierno model.
 */
class JuntaGobiernoController extends Controller
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
     * Lists all JuntaGobierno models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JuntaGobiernoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JuntaGobierno model.
     * @param int $id ID
     * @param int $cat_direccion_id Cat Direccion ID
     * @param int $cat_departamento_id Cat Departamento ID
     * @param int $empleado_id Empleado ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $cat_direccion_id, $cat_departamento_id, $empleado_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $cat_direccion_id, $cat_departamento_id, $empleado_id),
        ]);
    }

    /**
     * Creates a new JuntaGobierno model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new JuntaGobierno();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['empleado/index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing JuntaGobierno model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @param int $cat_direccion_id Cat Direccion ID
     * @param int $cat_departamento_id Cat Departamento ID
     * @param int $empleado_id Empleado ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $cat_direccion_id, $cat_departamento_id)
    {
        $model = $this->findModel2($id, $cat_direccion_id, $cat_departamento_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'cat_direccion_id' => $model->cat_direccion_id, 'cat_departamento_id' => $model->cat_departamento_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing JuntaGobierno model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @param int $cat_direccion_id Cat Direccion ID
     * @param int $cat_departamento_id Cat Departamento ID
     * @param int $empleado_id Empleado ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $cat_direccion_id, $cat_departamento_id, $empleado_id)
    {
        $this->findModel($id, $cat_direccion_id, $cat_departamento_id, $empleado_id)->delete();

        return $this->redirect(['empleado/index']);
    }

    /**
     * Finds the JuntaGobierno model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param int $cat_direccion_id Cat Direccion ID
     * @param int $cat_departamento_id Cat Departamento ID
     * @param int $empleado_id Empleado ID
     * @return JuntaGobierno the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $cat_direccion_id, $cat_departamento_id, $empleado_id)
    {
        if (($model = JuntaGobierno::findOne(['id' => $id, 'cat_direccion_id' => $cat_direccion_id, 'cat_departamento_id' => $cat_departamento_id, 'empleado_id' => $empleado_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModel2($id, $cat_direccion_id, $cat_departamento_id)
    {
        if (($model = JuntaGobierno::findOne(['id' => $id, 'cat_direccion_id' => $cat_direccion_id, 'cat_departamento_id' => $cat_departamento_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    
}
