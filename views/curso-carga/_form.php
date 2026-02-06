<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CursoCarga $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="curso-carga-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'curso_cursoId')->textInput() ?>

    <?= $form->field($model, 'cursoDescricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cargahoraria')->textInput() ?>

    <?= $form->field($model, 'interna')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
