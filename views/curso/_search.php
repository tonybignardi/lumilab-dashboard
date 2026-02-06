<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CursoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="curso-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cursoId') ?>

    <?= $form->field($model, 'cursoDescricao') ?>

    <?= $form->field($model, 'cursoCargaHoraria') ?>

    <?= $form->field($model, 'cursoDataCriacao') ?>

    <?= $form->field($model, 'areaCurso_areaCursoId') ?>

    <?php // echo $form->field($model, 'cursoVisivel') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
