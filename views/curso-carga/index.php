<?php

use app\models\CursoCarga;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CursoCargaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Curso Cargas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curso-carga-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Curso Carga', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cursoCargaId',
            'curso_cursoId',
            'cursoDescricao',
            'cargahoraria',
            'interna',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, CursoCarga $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'cursoCargaId' => $model->cursoCargaId]);
                 }
            ],
        ],
    ]); ?>


</div>
