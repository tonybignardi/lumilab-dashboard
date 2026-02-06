<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\IndicadorSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="indicador-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'indicadorId') ?>

    <?= $form->field($model, 'indicadorRotulo') ?>

    <?= $form->field($model, 'indicadorDescricao') ?>

    <?= $form->field($model, 'indicadorTipo') ?>

    <?= $form->field($model, 'indicadorSQL') ?>

    <?php // echo $form->field($model, 'filtroCurso') ?>

    <?php // echo $form->field($model, 'filtroUsuario') ?>

    <?php // echo $form->field($model, 'filtroData') ?>

    <?php // echo $form->field($model, 'filtroPlataforma') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
