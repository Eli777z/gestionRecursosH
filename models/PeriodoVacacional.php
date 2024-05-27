<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "periodo_vacacional".
 *
 * @property int $id
 * @property string|null $año
 * @property string|null $fecha_inicio
 * @property string|null $fecha_final
 * @property string|null $original
 *
 * @property Vacaciones[] $vacaciones
 */
class PeriodoVacacional extends \yii\db\ActiveRecord
{
    public $dateRange;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periodo_vacacional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dateRange'], 'safe'], // Añadir dateRange como seguro para asignación masiva


            [['fecha_inicio', 'fecha_final'], 'safe'],
            [['año'], 'string', 'max' => 8],
            [['original'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'año' => 'Año',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_final' => 'Fecha Final',
            'original' => 'Original',
        ];
    }

    /**
     * Gets query for [[Vacaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacaciones()
    {
        return $this->hasMany(Vacaciones::class, ['periodo_vacacional_id' => 'id']);
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->dateRange) {
                list($this->fecha_inicio, $this->fecha_final) = explode(' a ', $this->dateRange);
            }
            
            return true;
        } else {
            return false;
        }
    }
}
