<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property int $usuarioId
 * @property string|null $usuarioNome
 * @property string $usuarioEmail
 * @property string|null $usuarioSenha
 * @property string|null $usuarioDataCriacao
 *
 * @property Cursoresponsavel[] $cursoresponsavels
 */
class Usuario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuarioId', 'usuarioEmail'], 'required'],
            [['usuarioId'], 'integer'],
            [['usuarioDataCriacao'], 'safe'],
            [['usuarioNome', 'usuarioEmail'], 'string', 'max' => 200],
            [['usuarioSenha'], 'string', 'max' => 100],
            [['usuarioId'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usuarioId' => 'Usuario ID',
            'usuarioNome' => 'Usuario Nome',
            'usuarioEmail' => 'Usuario Email',
            'usuarioSenha' => 'Usuario Senha',
            'usuarioDataCriacao' => 'Usuario Data Criacao',
        ];
    }

    /**
     * Gets query for [[Cursoresponsavels]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCursoresponsavels()
    {
        return $this->hasMany(Cursoresponsavel::class, ['usuario_usuarioId' => 'usuarioId']);
    }
}
