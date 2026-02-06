<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cursoresponsavel".
 *
 * @property int $cursoResponsavelId
 * @property int $curso_cursoId
 * @property int $usuario_usuarioId
 * @property int $cursoResponsavelPapel
 * @property string $nomePapel
 * @property string $interna
 */
class Cursoresponsavel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cursoresponsavel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso_cursoId', 'usuario_usuarioId'], 'required'],
            [['curso_cursoId', 'usuario_usuarioId', 'cursoResponsavelPapel'], 'integer'],
            [['nomePapel'], 'string', 'max' => 100],
            [['interna'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cursoResponsavelId' => 'Curso Responsavel ID',
            'curso_cursoId' => 'Curso Curso ID',
            'usuario_usuarioId' => 'Usuario Usuario ID',
            'cursoResponsavelPapel' => 'Curso Responsavel Papel',
            'nomePapel' => 'Nome Papel',
            'interna' => 'Interna',
        ];
    }
}
