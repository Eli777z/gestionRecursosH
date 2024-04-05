<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
//use app\models\User;
use app\models\Usuario;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'user', 'admin'],
                'rules' => [
                    [
                        //El administrador tiene permisos sobre las siguientes acciones
                        'actions' => ['logout', 'admin'],
                        //Esta propiedad establece que tiene permisos
                        'allow' => true,
                        //Usuarios autenticados, el signo ? es para invitados
                        'roles' => ['@'],
                        //Este método nos permite crear un filtro sobre la identidad del usuario
                        //y así establecer si tiene permisos o no
                        'matchCallback' => function ($rule, $action) {
                            //Llamada al método que comprueba si es un administrador
                            return Usuario::isUserAdmin(Yii::$app->user->identity->id);
                        },
                    ],
                    [
                       //Los usuarios simples tienen permisos sobre las siguientes acciones
                       'actions' => ['logout', 'user'],
                       //Esta propiedad establece que tiene permisos
                       'allow' => true,
                       //Usuarios autenticados, el signo ? es para invitados
                       'roles' => ['@'],
                       //Este método nos permite crear un filtro sobre la identidad del usuario
                       //y así establecer si tiene permisos o no
                       'matchCallback' => function ($rule, $action) {
                          //Llamada al método que comprueba si es un usuario simple
                          return Usuario::isUserSimple(Yii::$app->user->identity->id);
                      },
                   ],
                ],
            ],
     //Controla el modo en que se accede a las acciones, en este ejemplo a la acción logout
     //sólo se puede acceder a través del método post
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
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            // Si el usuario ya está autenticado, redirige según su rol
            $userId = Yii::$app->user->identity->id;
            $user = Usuario::findOne($userId);
            // Verifica si es el primer inicio de sesión
            if ($user->nuevo == 4) {
                // Redirige al usuario a la acción de cambio de contraseña
                return $this->redirect(['usuario/cambiarcontrasena']);
            }
            if (Usuario::isUserAdmin($userId)) {
                return $this->redirect(["site/portalgestionrh"]);
            } else {
                return $this->redirect(["site/portaltrabajador"]);
            }
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $userId = Yii::$app->user->identity->id;
            $user = Usuario::findOne($userId);
            // Si el inicio de sesión es exitoso, redirige según el rol
            $userId = Yii::$app->user->identity->id;
            if (Usuario::isUserAdmin($userId)) {
                return $this->redirect(["site/portalgestionrh"]);
            } else {
                if ($user->nuevo == 4) {
                    // Redirige al usuario a la acción de cambio de contraseña
                    return $this->redirect(['usuario/cambiarcontrasena']);
                }else{

                    return $this->redirect(["site/portaltrabajador"]);
                }

               
            }
        } else {
            // Si hay errores en el inicio de sesión, muestra la vista de login
            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

   
    
    

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionPortalgestionrh()
    {
        return $this->render('portalgestionrh');
    }
    
    public function actionPortaltrabajador()
    {
        return $this->render('portaltrabajador');
    }
    

    public function actionCambiarcontrasena()
    {
        return $this->render('usuario/cambiarcontrasena');
    }

}
