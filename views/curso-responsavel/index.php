<?php

use app\models\CursoResponsavel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\CursoResponsavelSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Curso Responsavels';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curso-responsavel-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Curso Responsavel', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cursoResponsavelId',
            'curso_cursoId',
            'usuario_usuarioId',
            'cursoResponsavelPapel',
            'nomePapel',
            //'interna',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, CursoResponsavel $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'cursoResponsavelId' => $model->cursoResponsavelId]);
                 }
            ],
        ],
    ]); ?>


</div>
