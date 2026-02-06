<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CursoCargaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="curso-carga-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cursoCargaId') ?>

    <?= $form->field($model, 'curso_cursoId') ?>

    <?= $form->field($model, 'cursoDescricao') ?>

    <?= $form->field($model, 'cargahoraria') ?>

    <?= $form->field($model, 'interna') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
