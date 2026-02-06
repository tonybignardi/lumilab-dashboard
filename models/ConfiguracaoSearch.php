<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Configuracao;

/**
 * ConfiguracaoSearch represents the model behind the search form of `app\models\Configuracao`.
 */
class ConfiguracaoSearch extends Configuracao
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['configuracaoId'], 'integer'],
            [['configuracaoDescricao', 'configuracaoValor', 'configuracaoInfo'], 'safe'],
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
        $query = Configuracao::find();

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
            'configuracaoId' => $this->configuracaoId,
        ]);

        $query->andFilterWhere(['like', 'configuracaoDescricao', $this->configuracaoDescricao])
            ->andFilterWhere(['like', 'configuracaoValor', $this->configuracaoValor])
            ->andFilterWhere(['like', 'configuracaoInfo', $this->configuracaoInfo]);

        return $dataProvider;
    }
}
