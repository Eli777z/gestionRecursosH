<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trabajador".
 *
 * @property int $id
 * @property string $nombre
 * @property string $apellido
 * @property string $fecha_nacimiento
 * @property int $codigo_postal
 * @property string $calle
 * @property int $numero_casa
 * @property string $colonia
 * @property string $foto
 * @property int $idusuario
 *
 * @property Usuario $idusuario0
 */
class Trabajador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trabajador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'apellido', 'fecha_nacimiento', 'codigo_postal', 'calle', 'numero_casa', 'colonia', 'idusuario'], 'required'],
            [['fecha_nacimiento'], 'safe'],
            [['codigo_postal', 'numero_casa', 'idusuario'], 'integer'],
            [['nombre'], 'string', 'max' => 30],
            [['apellido'], 'string', 'max' => 60],
            [['calle'], 'string', 'max' => 85],
            [['colonia'], 'string', 'max' => 35],
            [['foto'], 'string', 'max' => 100],
            [['foto'], 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg'],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['idusuario' => 'id']],
           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'codigo_postal' => 'Codigo Postal',
            'calle' => 'Calle',
            'numero_casa' => 'Numero Casa',
            'colonia' => 'Colonia',
            'foto' => 'Foto',
            'idusuario' => 'Idusuario',
        ];
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Usuario::class, ['id' => 'idusuario']);
    }
}
