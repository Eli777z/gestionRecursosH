<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "antecedente_no_patologico".
 *
 * @property int $id
 * @property int|null $cat_actividad_fisica_id
 * @property string|null $tipo_sangre
 * @property int|null $tabaquismo
 * @property int|null $alcoholismo
 * @property int|null $drogas
 * @property int|null $actividad_fisica
 *
 * @property CatActividadFisica $catActividadFisica
 */
class AntecedenteNoPatologico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'antecedente_no_patologico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_actividad_fisica_id', 'tabaquismo', 'alcoholismo', 'drogas', 'actividad_fisica'], 'integer'],
            [['tipo_sangre'], 'string', 'max' => 4],
            [['cat_actividad_fisica_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatActividadFisica::class, 'targetAttribute' => ['cat_actividad_fisica_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_actividad_fisica_id' => 'Cat Actividad Fisica ID',
            'tipo_sangre' => 'Tipo Sangre',
            'tabaquismo' => 'Tabaquismo',
            'alcoholismo' => 'Alcoholismo',
            'drogas' => 'Drogas',
            'actividad_fisica' => 'Actividad Fisica',
        ];
    }

    /**
     * Gets query for [[CatActividadFisica]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatActividadFisica()
    {
        return $this->hasOne(CatActividadFisica::class, ['id' => 'cat_actividad_fisica_id']);
    }
}
