<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Configuracao $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="configuracao-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'configuracaoDescricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'configuracaoValor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'configuracaoInfo')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
