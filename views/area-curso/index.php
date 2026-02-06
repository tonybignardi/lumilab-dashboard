<?php

use app\models\AreaCurso;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AreaCursoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Area Cursos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-curso-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Area Curso', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'areaCursoId',
            'areaCursoDescricao',
            'areaCursoDataCriacao',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, AreaCurso $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'areaCursoId' => $model->areaCursoId]);
                 }
            ],
        ],
    ]); ?>


</div>
