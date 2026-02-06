<?php

use app\models\Indicador;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Indicadors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="indicador-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Indicador', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'indicadorId',
            'indicadorRotulo',
            'indicadorDescricao',
            'indicadorTipo',
            'indicadorSQL:ntext',
            //'filtroCurso:ntext',
            //'filtroField:ntext',
            //'filtroData:ntext',
            //'filtroPlataforma:ntext',
            //'filtroGroup:ntext',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Indicador $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'indicadorId' => $model->indicadorId]);
                 }
            ],
        ],
    ]); ?>


</div>
