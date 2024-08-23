<?php
//IMPORTACIONES
namespace app\controllers;

use Yii;
use app\models\NotificacionMedico;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\NotFoundHttpException;
use app\models\ExpedienteMedico;
use app\models\AntecedenteGinecologico;
use app\models\AntecedenteHereditario;
use app\models\AntecedenteNoPatologico;
use app\models\AntecedentePatologico;
use app\models\AntecedenteObstrectico;
use app\models\CatAntecedenteHereditario;
use app\models\ExploracionFisica;
use app\models\Alergia;
use app\models\AntecedentePerinatal;
use app\models\PeriodoVacacional;
use app\models\InterrogatorioMedico;
use app\models\Documento;
use app\models\PeriodoVacacionalHistorial;
//use app\models\User;
use app\models\PermisoFueraTrabajo;
use app\models\Usuario;
use app\models\Notificacion;
use app\models\Empleado;
use app\models\CambiarContrasenaForm;
use app\models\CatDepartamentoSearch;
use app\models\DocumentoMedico;
use yii\helpers\ArrayHelper;
use app\models\JuntaGobierno;
use app\models\ParametroFormatoSearch;
use app\models\CatPuestoSearch;
use yii\widgets\Breadcrumbs;
use app\models\Aviso;
class SiteController extends Controller

{
    /**
     * {@inheritdoc}
     */
    //CONTROL DE ACCESO
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'user', 'admin', 'medico'],
                'rules' => [
                    [
                       
                        'actions' => ['logout', 'admin', 'medico'],

                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Usuario::isUserAdmin(Yii::$app->user->identity->id);
                        },
                    ],
                    [
                       'actions' => ['logout', 'user'],
                       'allow' => true,
                      
                       'roles' => ['@'],
                      
                       'matchCallback' => function ($rule, $action) {

                          return Usuario::isUserSimple(Yii::$app->user->identity->id);
                      },
                   ],

                   [
                    'actions' => ['logout', 'medico'],
                    'allow' => true,
                   
                    'roles' => ['@'],
                   
                    'matchCallback' => function ($rule, $action) {

                       return Usuario::isUserMedico(Yii::$app->user->identity->id);
                   },
                ],
                ],
            ],
    
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */

  

    /**
     * Login action.
     *
     * @return Response|string
     */


//FUNCIÓN QUE SE ENCARGA DE RECIBIR LAS CREDENCIALES INGRESADAS
//POR LOS USUARIOS Y REDIRIGIRLOS A LAS VISTAS CORRESPONDIENTES A SU ROL
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->identity->id;
            $user = Usuario::findOne($userId);
           
            if ($user->nuevo == 4) {
                return $this->redirect(['usuario/cambiarcontrasena']);
            }
            elseif (Usuario::isUserAdmin($userId)) {
                return $this->redirect(["site/portalgestionrh"]);
            } elseif(Usuario:: isUserSimple($userId)) {
                return $this->redirect(["site/portalempleado"]);
            }else{
                return $this->redirect(["site/portal-medico"]);

            }
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $userId = Yii::$app->user->identity->id;
            $user = Usuario::findOne($userId);
            $userId = Yii::$app->user->identity->id;
            if  ($user->nuevo == 4) {
                return $this->redirect(["usuario/cambiarcontrasena"]);
            } else {
                if (Usuario::isUserAdmin($userId)) {
                    return $this->redirect(['site/portalgestionrh']);
                }elseif (Usuario::isUserSimple($userId)) {

                    return $this->redirect(["site/portalempleado"]);
                }
                else{

                    return $this->redirect(["site/portal-medico"]);

                }

               
            }
        } else {
            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


   
   
    

    //SE RENDERIZA LA VISTA DE CONFIGURACIONES DONDE SE CARGAN LOS REGISTROS DE LOS MODELOS
    //DE CAT_DEPARTAMENTO, PARAMETRO_FORMATO, CAT_PUESTO
    public function actionConfiguracion()
    {
        
        $parametroFormatoSearchModel = new ParametroFormatoSearch();
        $parametroFormatoDataProvider = $parametroFormatoSearchModel->search(Yii::$app->request->queryParams);
    
        
        $catPuestoSearchModel = new CatPuestoSearch();
        $catPuestoDataProvider = $catPuestoSearchModel->search(Yii::$app->request->queryParams);

    
        $catDepartamentoSearchModel = new CatDepartamentoSearch();
        $catDepartamentoDataProvider = $catDepartamentoSearchModel->search(Yii::$app->request->queryParams);
    
        return $this->render('configuracion', [
            'parametroFormatoSearchModel' => $parametroFormatoSearchModel,
            'parametroFormatoDataProvider' => $parametroFormatoDataProvider,
            'catPuestoSearchModel' => $catPuestoSearchModel,
            'catPuestoDataProvider' => $catPuestoDataProvider,
            'catDepartamentoSearchModel' => $catDepartamentoSearchModel,
            'catDepartamentoDataProvider' => $catDepartamentoDataProvider
        ]);
    }
    

    /**
     * Logout action.
     *
     * @return Response
     */
    //FUNCION QUE SE ENCARGA DE CERRAR LA SESIÓN DE LOS USUARIOS
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['site/login']);
    }
    

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
   
    /**
     * Displays about page.
     *
     * @return string
     */


   // FUNCION QUE SE ENCARGA DE RENDERIZAR EL INICIO DEL USUARIO MEDICO
   public function actionPortalgestionrh()
   {
       $avisos = \app\models\Aviso::find()->all();
$nuevasSolicitudesCount = \app\models\Solicitud::find()->where(['status' => 'Nueva'])->count();

       

       $solicitudesRecientes = \app\models\Solicitud::find()
           ->orderBy(['fecha_creacion' => SORT_DESC])
           ->limit(10) 
           ->all();
   
       return $this->render('portalgestionrh', [
           'solicitudesRecientes' => $solicitudesRecientes,
           'avisos' => $avisos,
           'nuevasSolicitudesCount'=>  $nuevasSolicitudesCount,

       ]);
   }
      // BUSCA LAS SOLICITUDES DE CITAS MEDICAS MÁS RECIENTES
        public function actionPortalMedico()
    {
        $solicitudesRecientes = \app\models\Solicitud::find()
            ->where(['nombre_formato' => 'CITA MEDICA'])
            ->orderBy(['fecha_creacion' => SORT_DESC])
            ->limit(10)
            ->all();

        $usuarioId = Yii::$app->user->identity->id;
        $notificacionesCount = NotificacionMedico::countUnread($usuarioId);

        return $this->render('portal-medico', [
            'solicitudesRecientes' => $solicitudesRecientes,
            'notificacionesCount' => $notificacionesCount,
        ]);
    }

    
    

   


    

    

   
// FUNCION QUE SE ENCARGA DE OBTENER LA FOTO DEL USUARIO
// LA OBTIENE DE LA CARPETA DEL EMPLEADO QUE SE ENCUENTRA
// EN EL DIRECTORIO DE RUNTIME
    public function actionGetEmpleadoFoto($filename)
    {
        $filePath = Yii::getAlias('@runtime') . '/empleados/' . $filename;
        if (file_exists($filePath)) {
            return Yii::$app->response->sendFile($filePath);
        } else {
            throw new \yii\web\NotFoundHttpException("File not found");
        }
    }

    // FUNCION QUE RENDERIZA EL INICIO DEL USUARIO EMPLEADO
    // SE CARGA LA INFORMACIÓN ESPECIFICA DEL EMPLEADO EN BASE A LOS MODELOS
    // PROPORCIONADOS
    public function actionPortalempleado()
    {

        $avisos = \app\models\Aviso::find()->all();

        

       
    
       
        $usuario = Yii::$app->user->identity;
        $modelEmpleado = $usuario->empleado;
        
        if (!$modelEmpleado) {
            throw new NotFoundHttpException('El empleado no existe.');
        }
    
        $documentos = $modelEmpleado->documentos;
        $documentoModel = new Documento();
        $documentoMedicoModel = new DocumentoMedico();
        $historial = PeriodoVacacionalHistorial::find()->where(['empleado_id' => $modelEmpleado->id])->all();
    
        $searchModel = new \app\models\SolicitudSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['empleado_id' => $modelEmpleado->id]);
    
        $searchModelConsultas = new \app\models\ConsultaMedicaSearch();
        $dataProviderConsultas = $searchModelConsultas->search(Yii::$app->request->queryParams);
        $dataProviderConsultas->query->andWhere(['expediente_medico_id' => $modelEmpleado->expedienteMedico->id]);
    
        $expedienteMedico = $modelEmpleado->expedienteMedico;
        $antecedentes = AntecedenteHereditario::find()->where(['expediente_medico_id' => $expedienteMedico->id])->all();
        $catAntecedentes = CatAntecedenteHereditario::find()->all();
    
        $antecedentePatologico = AntecedentePatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new AntecedentePatologico(['expediente_medico_id' => $expedienteMedico->id]);
        $antecedenteNoPatologico = AntecedenteNoPatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new AntecedenteNoPatologico(['expediente_medico_id' => $expedienteMedico->id]);
        $ExploracionFisica = ExploracionFisica::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new ExploracionFisica(['expediente_medico_id' => $expedienteMedico->id]);
        $InterrogatorioMedico = InterrogatorioMedico::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new InterrogatorioMedico(['expediente_medico_id' => $expedienteMedico->id]);
        $AntecedentePerinatal = AntecedentePerinatal::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new AntecedentePerinatal(['expediente_medico_id' => $expedienteMedico->id]);
        $AntecedenteGinecologico = AntecedenteGinecologico::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new AntecedenteGinecologico(['expediente_medico_id' => $expedienteMedico->id]);
        $AntecedenteObstrectico = AntecedenteObstrectico::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new AntecedenteObstrectico(['expediente_medico_id' => $expedienteMedico->id]);
        $Alergia = Alergia::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new Alergia(['expediente_medico_id' => $expedienteMedico->id]);
    
        return $this->render('portalempleado', [
            'model' => $modelEmpleado,
            'documentos' => $documentos,
            'documentoModel' => $documentoModel,
            'historial' => $historial,
            'searchModelConsultas' => $searchModelConsultas,
            'dataProviderConsultas' => $dataProviderConsultas,
            'searchModel' => $searchModel,
            'documentoMedicoModel' => $documentoMedicoModel,
            'dataProvider' => $dataProvider,
            'expedienteMedico' => $expedienteMedico,
            'antecedentes' => $antecedentes,
            'catAntecedentes' => $catAntecedentes,
            'antecedenteNoPatologico' => $antecedenteNoPatologico, 
            'ExploracionFisica' => $ExploracionFisica,
            'InterrogatorioMedico' => $InterrogatorioMedico,
            'AntecedentePerinatal' => $AntecedentePerinatal,
            'AntecedenteGinecologico' => $AntecedenteGinecologico,
            'AntecedenteObstrectico' => $AntecedenteObstrectico,
            'Alergia' => $Alergia,
            'antecedentePatologico' => $antecedentePatologico,
            'avisos' => $avisos,
        ]);
    }
    
    
    
}
