<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exploracion_fisica".
 *
 * @property int $id
 * @property int|null $expediente_medico_id
 * @property string|null $desc_habitus_exterior
 * @property string|null $desc_cabeza
 * @property string|null $desc_ojos
 * @property string|null $desc_otorrinolaringologia
 * @property string|null $desc_cuello
 * @property string|null $desc_torax
 * @property string|null $desc_abdomen
 * @property string|null $desc_exploración_ginecologica
 * @property string|null $desc_genitales
 * @property string|null $desc_columna_vertebral
 * @property string|null $desc_extremidades
 * @property string|null $desc_exploracion_neurologica
 *
 * @property ExpedienteMedico $expedienteMedico
 * @property ExpedienteMedico[] $expedienteMedicos
 */
class ExploracionFisica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exploracion_fisica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expediente_medico_id'], 'integer'],
            [['desc_habitus_exterior', 'desc_cabeza', 'desc_ojos', 'desc_otorrinolaringologia', 'desc_cuello', 'desc_torax', 'desc_abdomen', 'desc_exploración_ginecologica', 'desc_genitales', 'desc_columna_vertebral', 'desc_extremidades', 'desc_exploracion_neurologica'], 'string'],
            [['expediente_medico_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpedienteMedico::class, 'targetAttribute' => ['expediente_medico_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'expediente_medico_id' => 'Expediente Medico ID',
            'desc_habitus_exterior' => 'Desc Habitus Exterior',
            'desc_cabeza' => 'Desc Cabeza',
            'desc_ojos' => 'Desc Ojos',
            'desc_otorrinolaringologia' => 'Desc Otorrinolaringologia',
            'desc_cuello' => 'Desc Cuello',
            'desc_torax' => 'Desc Torax',
            'desc_abdomen' => 'Desc Abdomen',
            'desc_exploración_ginecologica' => 'Desc Exploración Ginecologica',
            'desc_genitales' => 'Desc Genitales',
            'desc_columna_vertebral' => 'Desc Columna Vertebral',
            'desc_extremidades' => 'Desc Extremidades',
            'desc_exploracion_neurologica' => 'Desc Exploracion Neurologica',
        ];
    }

    /**
     * Gets query for [[ExpedienteMedico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpedienteMedico()
    {
        return $this->hasOne(ExpedienteMedico::class, ['id' => 'expediente_medico_id']);
    }

    /**
     * Gets query for [[ExpedienteMedicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpedienteMedicos()
    {
        return $this->hasMany(ExpedienteMedico::class, ['exploracion_fisica_id' => 'id']);
    }
}
