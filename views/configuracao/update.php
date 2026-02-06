<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Configuracao $model */

$this->title = 'Update Configuracao: ' . $model->configuracaoId;
$this->params['breadcrumbs'][] = ['label' => 'Configuracaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->configuracaoId, 'url' => ['view', 'configuracaoId' => $model->configuracaoId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="configuracao-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
