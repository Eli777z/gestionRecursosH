<?php

namespace app\controllers;

use Yii;
use app\models\Usuario;
use app\models\UsuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsuarioController implements the CRUD actions for Usuario model.
 */
class UsuarioController extends Controller
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
     * Lists all Usuario models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsuarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usuario model.
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
     * Creates a new Usuario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Usuario();
        $model->scenario = Usuario:: SCENARIO_CREATE;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            $hash =  Yii::$app->security->generatePasswordHash($model->password);
            // echo "hash:" . $hash;
            $model->password = $hash;                                               
            if ($model->save()) {  
                $auth = \Yii::$app->authManager;
                $authorRole = $auth->getRole('administrador');
                $auth->assign($authorRole, $model->id);
                //echo "<br>Se ha creado el permiso";
            } else {
                die('Error al guardar');
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing Usuario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

       // $previous_photo = $model->photo;   
        if ($this->request->isPost && $model->load($this->request->post())) {
          //  $upload = UploadedFile::getInstance($model, 'photo');   
          //  if(is_object($upload)){
            //    $upload_filename = 'uploads/user_profile/' . $upload->baseName . '.' . $upload->extension;
              //  $upload->saveAs($upload_filename);
               // $model->photo = $upload_filename;   
           // }else{
             //   $model->photo = $previous_photo;                    
            //}
            
            if (!empty($model->password)) {
                $hash = Yii::$app->security->generatePasswordHash($model->password);
                $model->password = $hash;
            } else {
               
                $model->password = $model->getOldAttribute('password');
            }
    
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
     $model->password='';
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Usuario model.
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
     * Finds the Usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuario::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
