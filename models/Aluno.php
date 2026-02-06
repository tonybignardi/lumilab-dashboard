<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "aluno".
 *
 * @property int $alunoId
 * @property string|null $alunoDescricao
 * @property string|null $alunoDataCriacao
 *
 * @property Acessoconteudo[] $acessoconteudos
 * @property Alunoinfo[] $alunoinfos
 * @property Alunoinscricao[] $alunoinscricaos
 */
class Aluno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'aluno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alunoId'], 'required'],
            [['alunoId'], 'integer'],
            [['alunoDataCriacao'], 'safe'],
            [['alunoDescricao'], 'string', 'max' => 100],
            [['alunoId'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'alunoId' => 'Aluno ID',
            'alunoDescricao' => 'Aluno Descricao',
            'alunoDataCriacao' => 'Aluno Data Criacao',
        ];
    }

    /**
     * Gets query for [[Acessoconteudos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcessoconteudos()
    {
        return $this->hasMany(Acessoconteudo::class, ['aluno_alunoId' => 'alunoId']);
    }

    /**
     * Gets query for [[Alunoinfos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlunoinfos()
    {
        return $this->hasMany(Alunoinfo::class, ['aluno_alunoId' => 'alunoId']);
    }

    /**
     * Gets query for [[Alunoinscricaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlunoinscricaos()
    {
        return $this->hasMany(Alunoinscricao::class, ['aluno_alunoId' => 'alunoId']);
    }
}
