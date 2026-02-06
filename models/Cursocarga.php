<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cursocarga".
 *
 * @property int $cursoCargaId
 * @property int $curso_cursoId
 * @property string $cursoDescricao
 * @property int $cargahoraria
 * @property string $interna
 */
class CursoCarga extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cursocarga';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso_cursoId', 'cursoDescricao', 'cargahoraria'], 'required'],
            [['curso_cursoId', 'cargahoraria'], 'integer'],
            [['cursoDescricao'], 'string', 'max' => 350],
            [['interna'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cursoCargaId' => 'Curso Carga ID',
            'curso_cursoId' => 'Curso Curso ID',
            'cursoDescricao' => 'Curso Descricao',
            'cargahoraria' => 'Cargahoraria',
            'interna' => 'Interna',
        ];
    }
}
