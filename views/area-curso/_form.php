<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AreaCurso $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="area-curso-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'areaCursoId')->textInput() ?>

    <?= $form->field($model, 'areaCursoDescricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'areaCursoDataCriacao')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
