<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Indicador $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="indicador-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'indicadorRotulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'indicadorDescricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'indicadorTipo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'indicadorSQL')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'filtroCurso')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'filtroData')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'filtroPlataforma')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
