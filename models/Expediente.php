<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expediente".
 *
 * @property int $id
 * @property string $nombre
 * @property string $ruta
 * @property string $tipo
 * @property string $fechasubida
 * @property int $idtrabajador
 *
 * @property Trabajador $idtrabajador0
 */
class Expediente extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $archivoSubido;

    public static function tableName()
    {
        return 'expediente';
    }

    public function rules()
    {
        return [
            [['nombre', 'tipo', 'fechasubida', 'idtrabajador'], 'required'],
            [['fechasubida'], 'safe'],
            [['idtrabajador'], 'integer'],
            [['nombre'], 'string', 'max' => 85],
            [['tipo'], 'string', 'max' => 10],
            [['ruta'], 'string', 'max' => 255],
            [['archivoSubido'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf, doc, docx'],
            [['idtrabajador'], 'exist', 'skipOnError' => true, 'targetClass' => Trabajador::class, 'targetAttribute' => ['idtrabajador' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'ruta' => 'Ruta',
            'tipo' => 'Tipo',
            'fechasubida' => 'Fecha de Subida',
            'idtrabajador' => 'ID del Trabajador',
            'archivoSubido' => 'Archivo a Subir',
        ];
    }

    public function getIdtrabajador0()
    {
        return $this->hasOne(Trabajador::class, ['id' => 'idtrabajador']);
    }

    public function upload()
    {
        if ($this->validate()) {
            $trabajador = Trabajador::findOne($this->idtrabajador);
            $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/trabajadores/' . $trabajador->nombre . '_' . $trabajador->apellido;
            $nombreCarpetaExpedientes = $nombreCarpetaTrabajador . '/expedientes';
            $rutaArchivo = $nombreCarpetaExpedientes . '/' . $this->archivoSubido->baseName . '.' . $this->archivoSubido->extension;
            $this->archivoSubido->saveAs($rutaArchivo);
            $this->ruta = $rutaArchivo; // Guarda la ruta del archivo en la base de datos
            return true;
        } else {
            return false;
        }
    }
}
