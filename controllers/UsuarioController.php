<?php

namespace app\controllers;

use Yii;
use app\models\Usuario;
use app\models\UsuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CambiarContrasenaForm;

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
        $model->scenario = Usuario::SCENARIO_CREATE;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $hash =  Yii::$app->security->generatePasswordHash($model->password);
            // echo "hash:" . $hash;
            $model->password = $hash;
            if ($model->save()) {
                $auth = \Yii::$app->authManager;
                $authorRole = $auth->getRole('trabajador');
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
        $model->password = '';
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





    public function actionCambiarcontrasena()
    {
        $userId = Yii::$app->user->identity->id;
        $user = Usuario::findOne($userId);

        // Verifica si el usuario es un administrador
        if (Usuario::isUserAdmin($userId)) {
            $redirectUrl = ['site/portalgestionrh'];
        } else {
            $redirectUrl = ['site/portaltrabajador'];
        }

        // Si el usuario no es nuevo y no es administrador, redirígelo a portaltrabajador
        if ($user->nuevo != 4 && !Usuario::isUserAdmin($userId)) {
            return $this->redirect(['site/portaltrabajador']);
        }

        $model = new CambiarContrasenaForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->password = Yii::$app->security->generatePasswordHash($model->newPassword);
            $user->nuevo = 0;
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Tu contraseña ha sido cambiada.');
                return $this->redirect($redirectUrl);
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al cambiar tu contraseña.');
            }
        }

        return $this->render('cambiarcontrasena', [
            'model' => $model,
        ]);
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
