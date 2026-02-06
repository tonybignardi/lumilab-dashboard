<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CursoResponsavel;

/**
 * CursoResponsavelSearch represents the model behind the search form of `app\models\CursoResponsavel`.
 */
class CursoResponsavelSearch extends CursoResponsavel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cursoResponsavelId', 'curso_cursoId', 'usuario_usuarioId', 'cursoResponsavelPapel'], 'integer'],
            [['nomePapel', 'interna'], 'safe'],
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
        $query = CursoResponsavel::find();

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
            'cursoResponsavelId' => $this->cursoResponsavelId,
            'curso_cursoId' => $this->curso_cursoId,
            'usuario_usuarioId' => $this->usuario_usuarioId,
            'cursoResponsavelPapel' => $this->cursoResponsavelPapel,
        ]);

        $query->andFilterWhere(['like', 'nomePapel', $this->nomePapel])
            ->andFilterWhere(['like', 'interna', $this->interna]);

        return $dataProvider;
    }
}
