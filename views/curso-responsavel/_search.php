<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CursoResponsavelSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="curso-responsavel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cursoResponsavelId') ?>

    <?= $form->field($model, 'curso_cursoId') ?>

    <?= $form->field($model, 'usuario_usuarioId') ?>

    <?= $form->field($model, 'cursoResponsavelPapel') ?>

    <?= $form->field($model, 'nomePapel') ?>

    <?php // echo $form->field($model, 'interna') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
