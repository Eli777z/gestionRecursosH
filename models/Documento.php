<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documento".
 *
 * @property int $id
 * @property int $empleado_id
 * @property int|null $cat_tipo_documento_id
 * @property string $nombre
 * @property string $ruta
 * @property string|null $fecha_subida
 *
 * @property CatTipoDocumento $catTipoDocumento
 * @property Empleado $empleado
 */
class Documento extends \yii\db\ActiveRecord
{
    const SCENARIO_NOMBRE = 'nombre';

    /**
     * {@inheritdoc}
     */

     public $archivoSubido;

    public static function tableName()
    {
        return 'documento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empleado_id', 'ruta'], 'required'],
            [['empleado_id', 'cat_tipo_documento_id'], 'integer'],
            [['fecha_subida'], 'safe'],
            [['nombre'], 'string', 'max' => 85],
            [['ruta'], 'string', 'max' => 255],
            [['archivoSubido'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf, doc, docx'],//EXTENSIONES PERMITIDAS DE DOCUMENTOS
            [['observacion'], 'string'],

           
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['cat_tipo_documento_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatTipoDocumento::class, 'targetAttribute' => ['cat_tipo_documento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'empleado_id' => 'Empleado ID',
            'cat_tipo_documento_id' => 'Cat Tipo Documento ID',
            'nombre' => 'Nombre',
            'ruta' => 'Archivo',
            'fecha_subida' => 'Fecha Subida',
            'archivoSubido' => 'Archivo a Subir',
            'observacion' => 'Observaciones'

        ];
    }
//RELACIONES
    /**
     * Gets query for [[CatTipoDocumento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatTipoDocumento()
    {
        return $this->hasOne(CatTipoDocumento::class, ['id' => 'cat_tipo_documento_id']);
    }

    /**
     * Gets query for [[Empleado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleado()
    {
        return $this->hasOne(Empleado::class, ['id' => 'empleado_id']);
    }

//FUNCION QUE PERMITE LA SUBIDA DE ARCHIVOS EN EL DIRECTORIO DE RUNTIME, A SU VES SIGUE LA 
//LOGICA NECESARIA PARA GUARDAR EL ARCHIVO EN LA CARPETA DEL EMPLEADO
    public function upload()
    {
        if ($this->validate()) {
            $empleado = Empleado::findOne($this->empleado_id);
            $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $empleado->nombre . '_' . $empleado->apellido;
            $nombreCarpetaExpedientes = $nombreCarpetaTrabajador . '/documentos';
            $rutaArchivo = $nombreCarpetaExpedientes . '/' . $this->archivoSubido->baseName . '.' . $this->archivoSubido->extension;
            $this->archivoSubido->saveAs($rutaArchivo);
            $this->ruta = $rutaArchivo; 
            return true;
        } else {
            return false;
        }
    }

}
