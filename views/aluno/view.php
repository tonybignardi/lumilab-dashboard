<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Aluno $model */

$this->title = $model->alunoId;
$this->params['breadcrumbs'][] = ['label' => 'Estudantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="aluno-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'alunoId' => $model->alunoId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'alunoId' => $model->alunoId], [
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
            'alunoId',
            'alunoDescricao',
            'alunoDataCriacao',
        ],
    ]) ?>

</div>
