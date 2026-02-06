<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Configuracao $model */

$this->title = 'Create Configuracao';
$this->params['breadcrumbs'][] = ['label' => 'Configuracaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuracao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
