<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "curso".
 *
 * @property int $cursoId
 * @property string|null $cursoDescricao
 * @property string|null $cursoCargaHoraria
 * @property string|null $cursoDataCriacao
 * @property int $areaCurso_areaCursoId
 * @property int $cursoVisivel
 *
 * @property Acessoconteudo[] $acessoconteudos
 * @property Alunoinscricao[] $alunoinscricaos
 * @property Areacurso $areaCursoAreaCurso
 * @property Conteudo[] $conteudos
 * @property Cursoresponsavel[] $cursoresponsavels
 */
class Curso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cursoId', 'areaCurso_areaCursoId', 'cursoVisivel'], 'required'],
            [['cursoId', 'areaCurso_areaCursoId', 'cursoVisivel'], 'integer'],
            [['cursoDataCriacao'], 'safe'],
            [['cursoDescricao'], 'string', 'max' => 200],
            [['cursoCargaHoraria'], 'string', 'max' => 45],
            [['cursoId'], 'unique'],
            [['areaCurso_areaCursoId'], 'exist', 'skipOnError' => true, 'targetClass' => Areacurso::class, 'targetAttribute' => ['areaCurso_areaCursoId' => 'areaCursoId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cursoId' => 'Curso ID',
            'cursoDescricao' => 'Curso Descricao',
            'cursoCargaHoraria' => 'Curso Carga Horaria',
            'cursoDataCriacao' => 'Curso Data Criacao',
            'areaCurso_areaCursoId' => 'Area Curso Area Curso ID',
            'cursoVisivel' => 'Curso Visivel',
        ];
    }

    /**
     * Gets query for [[Acessoconteudos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcessoconteudos()
    {
        return $this->hasMany(Acessoconteudo::class, ['curso_cursoId' => 'cursoId']);
    }

    /**
     * Gets query for [[Alunoinscricaos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAlunoinscricaos()
    {
        return $this->hasMany(Alunoinscricao::class, ['curso_cursoId' => 'cursoId']);
    }

    /**
     * Gets query for [[AreaCursoAreaCurso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaCursoAreaCurso()
    {
        return $this->hasOne(Areacurso::class, ['areaCursoId' => 'areaCurso_areaCursoId']);
    }

    /**
     * Gets query for [[Conteudos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConteudos()
    {
        return $this->hasMany(Conteudo::class, ['curso_cursoId' => 'cursoId']);
    }

    /**
     * Gets query for [[Cursoresponsavels]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCursoresponsavels()
    {
        return $this->hasMany(Cursoresponsavel::class, ['curso_cursoId' => 'cursoId']);
    }
}
