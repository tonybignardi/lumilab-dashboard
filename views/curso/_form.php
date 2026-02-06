<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Curso $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="curso-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cursoId')->textInput() ?>

    <?= $form->field($model, 'cursoDescricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cursoCargaHoraria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cursoDataCriacao')->textInput() ?>

    <?= $form->field($model, 'areaCurso_areaCursoId')->textInput() ?>

    <?= $form->field($model, 'cursoVisivel')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
