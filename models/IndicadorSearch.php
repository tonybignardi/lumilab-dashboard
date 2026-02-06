<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Indicador;

/**
 * IndicadorSearch represents the model behind the search form of `app\models\Indicador`.
 */
class IndicadorSearch extends Indicador
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['indicadorId'], 'integer'],
            [['indicadorRotulo', 'indicadorDescricao', 'indicadorTipo', 'indicadorSQL', 'filtroCurso', 'filtroUsuario', 'filtroData', 'filtroPlataforma'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Indicador::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'indicadorId' => $this->indicadorId,
        ]);

        $query->andFilterWhere(['like', 'indicadorRotulo', $this->indicadorRotulo])
            ->andFilterWhere(['like', 'indicadorDescricao', $this->indicadorDescricao])
            ->andFilterWhere(['like', 'indicadorTipo', $this->indicadorTipo])
            ->andFilterWhere(['like', 'indicadorSQL', $this->indicadorSQL])
            ->andFilterWhere(['like', 'filtroCurso', $this->filtroCurso])
            ->andFilterWhere(['like', 'filtroUsuario', $this->filtroUsuario])
            ->andFilterWhere(['like', 'filtroData', $this->filtroData])
            ->andFilterWhere(['like', 'filtroPlataforma', $this->filtroPlataforma]);

        return $dataProvider;
    }
}
