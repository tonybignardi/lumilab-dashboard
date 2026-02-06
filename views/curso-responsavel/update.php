<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CursoResponsavel $model */

$this->title = 'Update Curso Responsavel: ' . $model->cursoResponsavelId;
$this->params['breadcrumbs'][] = ['label' => 'Curso Responsavels', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cursoResponsavelId, 'url' => ['view', 'cursoResponsavelId' => $model->cursoResponsavelId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="curso-responsavel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
