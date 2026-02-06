<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\CursoCarga $model */

$this->title = 'Create Curso Carga';
$this->params['breadcrumbs'][] = ['label' => 'Curso Cargas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curso-carga-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
