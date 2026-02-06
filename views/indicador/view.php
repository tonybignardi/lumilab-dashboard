<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Indicador $model */

$this->title = $model->indicadorId;
$this->params['breadcrumbs'][] = ['label' => 'Indicadors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="indicador-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'indicadorId' => $model->indicadorId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'indicadorId' => $model->indicadorId], [
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
            'indicadorId',
            'indicadorRotulo',
            'indicadorDescricao',
            'indicadorTipo',
            'indicadorSQL:ntext',
            'filtroCurso:ntext',
            'filtroField:ntext',
            'filtroData:ntext',
            'filtroPlataforma:ntext',
            'filtroGroup:ntext',
        ],
    ]) ?>

</div>
