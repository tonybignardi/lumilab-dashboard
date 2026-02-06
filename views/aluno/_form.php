<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Aluno $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="aluno-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'alunoId')->textInput() ?>

    <?= $form->field($model, 'alunoDescricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alunoDataCriacao')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
