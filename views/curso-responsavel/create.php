<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CursoResponsavel $model */

$this->title = 'Create Curso Responsavel';
$this->params['breadcrumbs'][] = ['label' => 'Curso Responsavels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curso-responsavel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
