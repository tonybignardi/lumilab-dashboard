<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Curso $model */

$this->title = 'Update Curso: ' . $model->cursoId;
$this->params['breadcrumbs'][] = ['label' => 'Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cursoId, 'url' => ['view', 'cursoId' => $model->cursoId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="curso-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
