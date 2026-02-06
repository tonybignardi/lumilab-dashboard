<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ConfiguracaoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="configuracao-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'configuracaoId') ?>

    <?= $form->field($model, 'configuracaoDescricao') ?>

    <?= $form->field($model, 'configuracaoValor') ?>

    <?= $form->field($model, 'configuracaoInfo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
