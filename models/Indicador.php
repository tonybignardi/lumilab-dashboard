<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "indicador".
 *
 * @property int $indicadorId
 * @property string $indicadorRotulo
 * @property string $indicadorDescricao
 * @property string $indicadorTipo linha,barra,texto
 * @property string $indicadorSQL
 * @property string $filtroCurso
 * @property string $filtroUsuario
 * @property string $filtroData
 * @property string $filtroPlataforma
 */
class Indicador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'indicador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['indicadorRotulo', 'indicadorDescricao', 'indicadorTipo', 'indicadorSQL', 'filtroCurso', 'filtroUsuario', 'filtroData', 'filtroPlataforma'], 'required'],
            [['indicadorSQL', 'filtroCurso', 'filtroUsuario', 'filtroData', 'filtroPlataforma'], 'string'],
            [['indicadorRotulo', 'indicadorDescricao'], 'string', 'max' => 200],
            [['indicadorTipo'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'indicadorId' => 'Indicador ID',
            'indicadorRotulo' => 'Indicador Rotulo',
            'indicadorDescricao' => 'Indicador Descricao',
            'indicadorTipo' => 'Indicador Tipo',
            'indicadorSQL' => 'Indicador Sql',
            'filtroCurso' => 'Filtro Curso',
            'filtroUsuario' => 'Filtro Usuario',
            'filtroData' => 'Filtro Data',
            'filtroPlataforma' => 'Filtro Plataforma',
        ];
    }
}
