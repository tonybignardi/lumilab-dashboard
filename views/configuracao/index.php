<?php

use app\models\Configuracao;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ConfiguracaoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Configuracaos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracao-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Configuracao', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'configuracaoId',
            'configuracaoDescricao',
            'configuracaoValor',
            'configuracaoInfo',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Configuracao $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'configuracaoId' => $model->configuracaoId]);
                 }
            ],
        ],
    ]); ?>


</div>
