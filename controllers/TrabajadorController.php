<?php

namespace app\controllers;

use Yii;
use app\models\Trabajador;
use app\models\Usuario;
use app\models\TrabajadorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Exception;
use yii\web\UploadedFile;
/**
 * TrabajadorController implements the CRUD actions for Trabajador model.
 */
class TrabajadorController extends Controller
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
     * Lists all Trabajador models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrabajadorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Trabajador model.
     * @param int $id ID
     * @param int $idusuario Idusuario
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel2($id),
        ]);
    }

    /**
     * Creates a new Trabajador model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   
     public function actionCreate()
     {
         $model = new Trabajador();
         $user = new Usuario(); // Asumiendo que tienes un modelo Usuario
         $user->scenario = Usuario::SCENARIO_CREATE;
         if ($model->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
             $transaction = Yii::$app->db->beginTransaction(); // Iniciar transacción
             // Generar username y password
             $user->username = $model->nombre . $model->apellido;
             $nombres = explode(" ", $model->nombre);
        $apellidos = explode(" ", $model->apellido);
        $usernameBase = strtolower($nombres[0][0] . $apellidos[0] . (isset($apellidos[1]) ? $apellidos[1][0] : ''));
        $user->username = $usernameBase;
        // Verificar si el username ya existe y añadir un número incremental
        $counter = 1;
        while (Usuario::find()->where(['username' => $user->username])->exists()) {
            $user->username = $usernameBase . $counter;
            $counter++;
        }
        // Generar password
        $user->password = 'contrasena'; // 
             $user->status= 10;
             $hash = Yii::$app->security->generatePasswordHash($user->password);
             $user->password = $hash;
             try {
                 if ($user->save()) {
                     $model->idusuario = $user->id; // Asignar ID de usuario al trabajador
                     $auth = \Yii::$app->authManager;
                     $authorRole = $auth->getRole('trabajador');
                     $auth->assign($authorRole, $user->id);
                     // Proceso para guardar la foto, si existe
                     $upload = UploadedFile::getInstance($model, 'foto');                
                     if (is_object($upload)) {
                         $upload_filename = 'uploads/user_profile/' . $upload->baseName . '.' . $upload->extension;
                         $upload->saveAs($upload_filename);
                         $model->foto = $upload_filename;   
                     }
                     if ($model->save()) {
                         $transaction->commit(); // Si todo está bien, hacer commit
                         Yii::$app->session->setFlash('success', "Trabajador y usuario creados con éxito.");
                         return $this->redirect(['view', 'id' => $model->id]);
                     } else {
                         $transaction->rollBack(); // Si falla, hacer rollback
                     }
                 }
             } catch (\Exception $e) {
                 $transaction->rollBack(); // Si hay excepción, hacer rollback
                 throw $e;
             }
         }
         return $this->render('create', [
             'model' => $model,
             'user' => $user,
         ]);
     }
     
    

    

    /**
     * Updates an existing Trabajador model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @param int $idusuario Idusuario
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel2($id);
        $user = Usuario::findOne($model->idusuario); // Encuentra el usuario asociado
        $previous_photo = $model->foto; 
    
        if ($model->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction(); // Iniciar transacción
           
            try {
                if (!empty($user->password)) {
                    $hash = Yii::$app->security->generatePasswordHash($user->password);
                    $user->password = $hash;
                } else {
                   
                    $user->password = $user->getOldAttribute('password');
                }

                if ($user->save()) {
                    $upload = UploadedFile::getInstance($model, 'foto');   
                    if (is_object($upload)) {
                        $upload_filename = 'uploads/user_profile/' . $upload->baseName . '.' . $upload->extension;
                        $upload->saveAs($upload_filename);
                        $model->foto = $upload_filename;   
                    } else {
                        $model->foto = $previous_photo;                    
                    }
    
                    if ($model->save()) {
                        $transaction->commit(); // Si todo está bien, hacer commit
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        throw new \Exception('Error al guardar el modelo Trabajador');
                    }
                } else {
                    throw new \Exception('Error al guardar el modelo Usuario');
                }
            } catch (\Exception $e) {
                $transaction->rollBack(); // Si algo sale mal, hacer rollback
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        $user->password='';
        return $this->render('update', [
            'model' => $model,
            'user' => $user, // Pasar el modelo Usuario a la vista
        ]);
    }
    

    /**
     * Deletes an existing Trabajador model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @param int $idusuario Idusuario
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $idusuario)
    {
        $trabajador = $this->findModel($id, $idusuario);
    
    // Obtén el ID del usuario asociado al trabajador
    $idUsuario = $trabajador->idusuario;
    
    // Elimina tanto el trabajador como su usuario asociado
    $transaction = Yii::$app->db->beginTransaction();
    
    try {
        // Primero elimina el trabajador
        $trabajador->delete();
        
        // Luego elimina su usuario asociado
        Usuario::findOne($idUsuario)->delete();
        
        $transaction->commit();
        
        Yii::$app->session->setFlash('success', 'Trabajador eliminado exitosamente.');
    } catch (Exception $e) {
        $transaction->rollBack();
        
        Yii::$app->session->setFlash('error', 'Error al eliminar el trabajadory su usuario.');
    }
    
    return $this->redirect(['index']);
    }


    /**
     * Finds the Trabajador model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param int $idusuario Idusuario
     * @return Trabajador the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel2($id)
    {
        if (($model = Trabajador::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }




    protected function findModel($id, $idusuario)
    {
        if (($model = Trabajador::findOne(['id' => $id, 'idusuario' => $idusuario])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    //public function actionView($id, $idusuario)
    //{
      //  return $this->render('view', [
        //    'model' => $this->findModel($id, $idusuario),
        //]);
    //}
}
