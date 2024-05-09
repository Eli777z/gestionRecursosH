<?php

namespace app\controllers;

use Yii;
use app\models\Empleado;
use app\models\EmpleadoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Usuario;
use yii\web\UploadedFile;
use app\models\InformacionLaboral;
use app\models\CatDepartamento;
use yii\helpers\FileHelper;
use yii\db\Exception;



/**
 * EmpleadoController implements the CRUD actions for Empleado model.
 */
class EmpleadoController extends Controller
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
     * Lists all Empleado models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmpleadoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Empleado model.
     * @param int $id ID
     * @param int $usuario_id Usuario ID
     * @param int $informacion_laboral_id Informacion Laboral ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $modelEmpleado = $this->findModel2($id);
        
        return $this->render('view', [
            'model' => $modelEmpleado,
        ]);
    }

    /**
     * Creates a new Empleado model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Empleado();
        $usuario = new Usuario();
        $informacion_laboral = new InformacionLaboral;
        // $departamento = new Departamento();
        $usuario->scenario = Usuario::SCENARIO_CREATE;
        if ($model->load(Yii::$app->request->post()) && $usuario->load(Yii::$app->request->post()) && $informacion_laboral->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $usuario->username = $model->nombre . $model->apellido;
            $nombres = explode(" ", $model->nombre);
            $apellidos = explode(" ", $model->apellido);
            $usernameBase = strtolower($nombres[0][0] . $apellidos[0] . (isset($apellidos[1]) ? $apellidos[1][0] : ''));
            $usuario->username = $usernameBase;
            $counter = 1;
            while (Usuario::find()->where(['username' => $usuario->username])->exists()) {
                $usuario->username = $usernameBase . $counter;
                $counter++;
            }
            $usuario->password = 'contrasena';
            $usuario->status = 10;
            $usuario->nuevo = 4;
            $hash = Yii::$app->security->generatePasswordHash($usuario->password);
            $usuario->password = $hash;
            try {


                //$informacion_laboral->iddepartamento = $departamento->nombre;
                if (!$informacion_laboral->save()) {
                    throw new \yii\db\Exception('Error al guardar Informacion_laboral');
                }
                $model->informacion_laboral_id = $informacion_laboral->id;
                if ($usuario->save()) {
                    $model->usuario_id = $usuario->id;
                    $rol = ($usuario->rol == 1) ? 'empleado' : 'administrador';

                    // Obtener el objeto de autorización
                    $auth = Yii::$app->authManager;

                    // Obtener el rol correspondiente según el valor de $rol
                    $authorRole = $auth->getRole($rol);

                    // Asignar el rol al usuario
                    $auth->assign($authorRole, $usuario->id);
                    $upload = UploadedFile::getInstance($model, 'foto');

                    if (is_object($upload)) {

                        $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $model->nombre . '_' . $model->apellido;
                        if (!is_dir($nombreCarpetaTrabajador)) {
                            mkdir($nombreCarpetaTrabajador, 0775, true);
                        }
                        $nombreCarpetaUsuarioProfile = $nombreCarpetaTrabajador . '/foto_empleado';
                        if (!is_dir($nombreCarpetaUsuarioProfile)) {
                            mkdir($nombreCarpetaUsuarioProfile, 0775, true);
                        }
                        $upload_filename = $nombreCarpetaUsuarioProfile . '/' . $upload->baseName . '.' . $upload->extension;
                        $upload->saveAs($upload_filename);
                        $model->foto = $upload_filename;
                    }
                    if ($model->save()) {

                        Yii::$app->mailer->compose()
                            ->setFrom('elitaev7@gmail.com')
                            ->setTo($model->email)
                            ->setSubject('Datos de acceso al sistema')
                            ->setTextBody("Nos comunicamos con usted, {$model->nombre}.\nAquí están tus datos de acceso:\nNombre de Usuario: {$usuario->username}\nContraseña: contrasena") // Reemplaza 'contrasena' con la contraseña generada si es diferente
                            ->send();
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', "Trabajador y usuario creados con éxito.");
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        $transaction->rollBack();
                    }
                    //
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return $this->render('create', [
            'model' => $model,
            'usuario' => $usuario,
            'informacion_laboral' => $informacion_laboral,
            //'departamento' => $departamento,
            // 'departamentosArray' => $departamentosArray,
        ]);
    
    }

    /**
     * Updates an existing Empleado model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @param int $usuario_id Usuario ID
     * @param int $informacion_laboral_id Informacion Laboral ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $usuario_id, $informacion_laboral_id)
    {
        $model = $this->findModel($id, $usuario_id, $informacion_laboral_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'usuario_id' => $model->usuario_id, 'informacion_laboral_id' => $model->informacion_laboral_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Empleado model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @param int $usuario_id Usuario ID
     * @param int $informacion_laboral_id Informacion Laboral ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $usuario_id)
    {
        $empleado = $this->findModel3($id, $usuario_id);
    
        $transaction = Yii::$app->db->beginTransaction();
    
        try {
            // Eliminar los documentos asociados al empleado y su carpeta
            foreach ($empleado->documentos as $documento) {
                $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $empleado->nombre . '_' . $empleado->apellido;
                if (is_dir($nombreCarpetaTrabajador)) {
                    FileHelper::removeDirectory($nombreCarpetaTrabajador);
                }
                $documento->delete();
            }
    
           
    
            // Eliminar al usuario asociado al empleado si existe
            if ($empleado->usuario) {
                $empleado->usuario->delete();
            }
    
            // Eliminar al empleado
            $empleado->delete();
    
            $transaction->commit();
    
            Yii::$app->session->setFlash('success', 'Trabajador, expedientes, carpeta asociada y usuario eliminados exitosamente.');
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error al eliminar el trabajador, sus expedientes, la carpeta asociada y el usuario.');
        }
    
        return $this->redirect(['index']);
    }
    


    /**
     * Finds the Empleado model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param int $usuario_id Usuario ID
     * @param int $informacion_laboral_id Informacion Laboral ID
     * @return Empleado the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $usuario_id, $informacion_laboral_id)
    {
        if (($model = Empleado::findOne(['id' => $id, 'usuario_id' => $usuario_id, 'informacion_laboral_id' => $informacion_laboral_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModel2($id)
    {
        if (($model = Empleado::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModel3($id,$usuario_id)
    {
        if (($model = Empleado::findOne(['id' => $id, 'usuario_id' => $usuario_id,])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }




    public function actionFotoEmpleado($id)
    {
        $model = $this->findModel2($id);

        if (file_exists($model->foto) && @getimagesize($model->foto)) {
            return Yii::$app->response->sendFile($model->foto);
        } else {
            throw new \yii\web\NotFoundHttpException('La imagen no existe.');
        }
    }

    
    public function actionDesactivarUsuario($id)
    {
        $empleado = Empleado::findOne($id);

        if (!$empleado) {
            throw new NotFoundHttpException('El trabajador no existe.');
        }

        $usuario_id = $empleado->usuario_id;

        $usuario = Usuario::findOne($usuario_id);

        if (!$usuario) {
            throw new NotFoundHttpException('El usuario asociado al trabajador no existe.');
        }

        $usuario->status = 0;

        if ($usuario->save()) {
            Yii::$app->session->setFlash('success', 'El usuario ha sido desactivado correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al desactivar el usuario.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionToggleActivation($id)
    {
        $model = $this->findModel2($id);

        // Cambia el estado del usuario
        if ($model->usuario->status == 10) {
            $model->usuario->status = 0; // Inactiva el usuario
        } else {
            $model->usuario->status = 10; // Activa el usuario
        }

        if ($model->usuario->save()) {
            Yii::$app->session->setFlash('success', 'El estado del usuario se ha cambiado correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Se produjo un error al cambiar el estado del usuario.');
        }

        return $this->redirect(['index']);
    }

    public function actionSaveEditable($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel2($id);
            $attribute = Yii::$app->request->post('name');
            $value = Yii::$app->request->post('value');

            $model->$attribute = $value;

            if ($model->save()) {
                return json_encode(['status' => 'success']);
            } else {
                return json_encode(['status' => 'error', 'msg' => 'Error al guardar']);
            }
        }
    }


    public function actionCambio($id)
    {
        Yii::info('Entrando en actionCambioFoto');

        $model = $this->findModel2($id);
        $previous_photo = $model->foto;

        if (Yii::$app->request->isPost) {
            Yii::info('Solicitud POST recibida');
            $upload = UploadedFile::getInstance($model, 'foto');
            Yii::info('Archivo de imagen recibido: ' . print_r($upload, true));

            if (is_object($upload)) {
                $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $model->nombre . '_' . $model->apellido;
                if (!is_dir($nombreCarpetaTrabajador)) {
                    mkdir($nombreCarpetaTrabajador, 0775, true);
                }
                $nombreCarpetaUserProfile = $nombreCarpetaTrabajador . '/foto_Trabajador';
                if (!is_dir($nombreCarpetaUserProfile)) {
                    mkdir($nombreCarpetaUserProfile, 0775, true);
                }
                $upload_filename = $nombreCarpetaUserProfile . '/' . $upload->baseName . '.' . $upload->extension;
                if ($upload->saveAs($upload_filename)) {
                    // Eliminar la foto anterior solo si se proporciona una nueva imagen
                    if ($previous_photo && $previous_photo !== $upload_filename && file_exists($previous_photo)) {
                        @unlink($previous_photo);
                    }
                    $model->foto = $upload_filename;
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'La foto del trabajador se actualizó correctamente.');
                    } else {
                        Yii::$app->session->setFlash('error', 'Hubo un error al guardar la nueva foto del trabajador.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Hubo un error al guardar la nueva foto del trabajador.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'No se ha proporcionado ninguna imagen para subir.');
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionActualizarInformacion($id)
    {
        $model = $this->findModel2($id);
    
        if ($model->load(Yii::$app->request->post())) {
            // Calcula la edad en base a la fecha de nacimiento
            $fechaNacimiento = new \DateTime($model->fecha_nacimiento);
            $hoy = new \DateTime();
            $diferencia = $hoy->diff($fechaNacimiento);
            $model->edad = $diferencia->y; // Asigna la edad al modelo
    
            if ($model->save()) {
                // Redirige a la vista de detalles o a otra página
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al guardar la información del trabajador.');
            }
        }
    
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    

    public function actionActualizarInformacionContacto($id)
    {
        $model = $this->findModel2($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Yii::$app->session->setFlash('success', 'La información del trabajador ha sido actualizada correctamente.');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al actualizar la información del trabajador.');
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionActualizarInformacionLaboral($id)
    {
        $model = $this->findModel2($id);
        $informacion_laboral = InformacionLaboral::findOne($model->informacion_laboral_id);


        if ($model->informacionLaboral->load(Yii::$app->request->post()) && $model->informacionLaboral->save()) {
            // Yii::$app->session->setFlash('success', 'La información laboral del trabajador ha sido actualizada correctamente.');
            $informacion_laboral->cat_departamento_id = Yii::$app->request->post('InformacionLaboral')['cat_departamento_id'];

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al actualizar la información laboral del trabajador.');
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

}

