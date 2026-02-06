<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\CursoCarga $model */

$this->title = $model->cursoCargaId;
$this->params['breadcrumbs'][] = ['label' => 'Curso Cargas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="curso-carga-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'cursoCargaId' => $model->cursoCargaId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'cursoCargaId' => $model->cursoCargaId], [
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
            'cursoCargaId',
            'curso_cursoId',
            'cursoDescricao',
            'cargahoraria',
            'interna',
        ],
    ]) ?>

</div>
