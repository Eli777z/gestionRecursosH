<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ArchivoController extends Controller
{
    public function actionVer($ruta)
{
    // Normalizar la ruta para usar solo barras inclinadas
    $ruta = str_replace('\\', '/', $ruta);

    // Construir la ruta completa del archivo
    $rutaCompleta = Yii::getAlias('@runtime/' . $ruta);

    // Verificar si el archivo existe
    if (file_exists($rutaCompleta)) {
        // Enviar el archivo como respuesta
        Yii::$app->response->sendFile($rutaCompleta)->send();
    } else {
        throw new NotFoundHttpException('El archivo solicitado no se encontró.');
    }
}

    
}
