<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AreaCurso $model */

$this->title = 'Create Area Curso';
$this->params['breadcrumbs'][] = ['label' => 'Area Cursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-curso-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
