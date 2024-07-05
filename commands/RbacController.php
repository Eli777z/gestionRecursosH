<?php 
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\FileHelper;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Eliminar todos los datos anteriores
        $auth->removeAll();

        // Definir permisos
        $verDocumentos = $auth->createPermission('verDocumentos');
        $verDocumentos->description = 'Ver Documentos';
        $auth->add($verDocumentos);

        $manageEmployees = $auth->createPermission('manageEmployees');
        $manageEmployees->description = 'Manage Employees';
        $auth->add($manageEmployees);

        // Definir roles y asignar permisos
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $verDocumentos);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $manageEmployees);
        $auth->addChild($admin, $user);

        // Asignar roles a usuarios
        $auth->assign($admin, 1); // Usuario con ID 1 es administrador
        $auth->assign($user, 2); // Usuario con ID 2 es usuario normal
    }

    public function actionRegisterRoutes()
    {
        $auth = Yii::$app->authManager;

        $controllers = FileHelper::findFiles(Yii::getAlias('@app/controllers'), [
            'only' => ['*Controller.php'],
        ]);

        foreach ($controllers as $controller) {
            $controllerName = basename($controller, 'Controller.php');
            $controllerName = strtolower($controllerName);
            $controllerClass = 'app\controllers\\' . ucfirst($controllerName) . 'Controller';

            if (class_exists($controllerClass)) {
                $methods = get_class_methods($controllerClass);

                foreach ($methods as $method) {
                    if (strpos($method, 'action') === 0) {
                        $actionName = strtolower(str_replace('action', '', $method));
                        $route = $controllerName . '/' . $actionName;
                        
                        // Imprime la ruta para verificar
                        echo "Registrando ruta: $route\n";

                        // Aquí se podría almacenar $route en una tabla de base de datos o enviarlo a la interfaz admin/route de alguna forma
                        // No es necesario agregar permisos aquí si no lo deseas

                        // Por ejemplo, puedes almacenar las rutas en una tabla de base de datos específica para tu interfaz admin/route
                        // O simplemente imprimir las rutas para visualizarlas en algún lugar dentro de la aplicación admin
                    }
                }
            }
        }

        echo "Registro de rutas completado.\n";
    }

    public function actionIndex()
    {
        $auth = Yii::$app->authManager;

        // Eliminar todos los permisos
        $auth->removeAllPermissions();

        echo "Todos los permisos han sido eliminados.\n";
    }
}
?>



