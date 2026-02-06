<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\CursoResponsavel $model */

$this->title = $model->cursoResponsavelId;
$this->params['breadcrumbs'][] = ['label' => 'Curso Responsavels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="curso-responsavel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'cursoResponsavelId' => $model->cursoResponsavelId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'cursoResponsavelId' => $model->cursoResponsavelId], [
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
            'cursoResponsavelId',
            'curso_cursoId',
            'usuario_usuarioId',
            'cursoResponsavelPapel',
            'nomePapel',
            'interna',
        ],
    ]) ?>

</div>
