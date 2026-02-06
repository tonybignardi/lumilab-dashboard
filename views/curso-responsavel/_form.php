<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CursoResponsavel $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="curso-responsavel-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'curso_cursoId')->textInput() ?>

    <?= $form->field($model, 'usuario_usuarioId')->textInput() ?>

    <?= $form->field($model, 'cursoResponsavelPapel')->textInput() ?>

    <?= $form->field($model, 'nomePapel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'interna')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
