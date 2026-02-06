<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\AreaCurso $model */

$this->title = $model->areaCursoId;
$this->params['breadcrumbs'][] = ['label' => 'Area Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="area-curso-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'areaCursoId' => $model->areaCursoId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'areaCursoId' => $model->areaCursoId], [
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
            'areaCursoId',
            'areaCursoDescricao',
            'areaCursoDataCriacao',
        ],
    ]) ?>

</div>
