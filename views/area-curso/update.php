<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AreaCurso $model */

$this->title = 'Update Area Curso: ' . $model->areaCursoId;
$this->params['breadcrumbs'][] = ['label' => 'Area Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->areaCursoId, 'url' => ['view', 'areaCursoId' => $model->areaCursoId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="area-curso-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
