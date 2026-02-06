<?php

use app\models\Aluno;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AlunoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Estudantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aluno-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Novo Estudante', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'alunoId',
            'alunoDescricao',
            'alunoDataCriacao',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Aluno $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'alunoId' => $model->alunoId]);
                 }
            ],
        ],
    ]); ?>


</div>
