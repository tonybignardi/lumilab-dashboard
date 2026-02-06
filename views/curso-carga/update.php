<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CursoCarga $model */

$this->title = 'Update Curso Carga: ' . $model->cursoCargaId;
$this->params['breadcrumbs'][] = ['label' => 'Curso Cargas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cursoCargaId, 'url' => ['view', 'cursoCargaId' => $model->cursoCargaId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="curso-carga-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
