<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "areacurso".
 *
 * @property int $areaCursoId
 * @property string|null $areaCursoDescricao
 * @property string|null $areaCursoDataCriacao
 *
 * @property Curso[] $cursos
 */
class AreaCurso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'areacurso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['areaCursoId'], 'required'],
            [['areaCursoId'], 'integer'],
            [['areaCursoDataCriacao'], 'safe'],
            [['areaCursoDescricao'], 'string', 'max' => 100],
            [['areaCursoId'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'areaCursoId' => 'Area Curso ID',
            'areaCursoDescricao' => 'Area Curso Descricao',
            'areaCursoDataCriacao' => 'Area Curso Data Criacao',
        ];
    }

    /**
     * Gets query for [[Cursos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCursos()
    {
        return $this->hasMany(Curso::class, ['areaCurso_areaCursoId' => 'areaCursoId']);
    }
}
