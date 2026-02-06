<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Curso;

/**
 * CursoSearch represents the model behind the search form of `app\models\Curso`.
 */
class CursoSearch extends Curso
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cursoId', 'areaCurso_areaCursoId', 'cursoVisivel'], 'integer'],
            [['cursoDescricao', 'cursoCargaHoraria', 'cursoDataCriacao'], 'safe'],
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
        $query = Curso::find();

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
            'cursoId' => $this->cursoId,
            'cursoDataCriacao' => $this->cursoDataCriacao,
            'areaCurso_areaCursoId' => $this->areaCurso_areaCursoId,
            'cursoVisivel' => $this->cursoVisivel,
        ]);

        $query->andFilterWhere(['like', 'cursoDescricao', $this->cursoDescricao])
            ->andFilterWhere(['like', 'cursoCargaHoraria', $this->cursoCargaHoraria]);

        return $dataProvider;
    }
}
