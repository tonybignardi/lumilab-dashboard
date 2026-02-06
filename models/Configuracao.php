<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracao".
 *
 * @property int $configuracaoId
 * @property string $configuracaoDescricao
 * @property string $configuracaoValor
 * @property string $configuracaoInfo
 */
class Configuracao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['configuracaoDescricao', 'configuracaoValor', 'configuracaoInfo'], 'required'],
            [['configuracaoDescricao', 'configuracaoValor', 'configuracaoInfo'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'configuracaoId' => 'Configuracao ID',
            'configuracaoDescricao' => 'Configuracao Descricao',
            'configuracaoValor' => 'Configuracao Valor',
            'configuracaoInfo' => 'Configuracao Info',
        ];
    }
}
