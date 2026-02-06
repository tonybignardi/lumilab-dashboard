<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Usuario $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="usuario-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'usuarioId')->textInput() ?>

    <?= $form->field($model, 'usuarioNome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'usuarioEmail')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'usuarioSenha')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'usuarioDataCriacao')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
