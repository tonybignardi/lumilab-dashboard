<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Indicador $model */

$this->title = 'Update Indicador: ' . $model->indicadorId;
$this->params['breadcrumbs'][] = ['label' => 'Indicadors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->indicadorId, 'url' => ['view', 'indicadorId' => $model->indicadorId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="indicador-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
