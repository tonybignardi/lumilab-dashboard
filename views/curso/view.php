<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Curso $model */

$this->title = $model->cursoId;
$this->params['breadcrumbs'][] = ['label' => 'Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="curso-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'cursoId' => $model->cursoId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'cursoId' => $model->cursoId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cursoId',
            'cursoDescricao',
            'cursoCargaHoraria',
            'cursoDataCriacao',
            'areaCurso_areaCursoId',
            'cursoVisivel',
        ],
    ]) ?>

</div>
