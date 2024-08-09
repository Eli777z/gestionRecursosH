<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Aviso;

/**
 * AvisoSearch represents the model behind the search form of `app\models\Aviso`.
 */


class AvisoSearch extends Aviso
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['titulo', 'mensaje', 'imagen'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Aviso::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 9, // Número de registros por página
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'titulo', $this->titulo])
              ->andFilterWhere(['like', 'mensaje', $this->mensaje])
              ->andFilterWhere(['like', 'imagen', $this->imagen]);

        return $dataProvider;
    }
}
