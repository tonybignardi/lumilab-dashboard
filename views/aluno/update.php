<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Aluno $model */

$this->title = 'Atualizando Estudante: ' . $model->alunoId;
$this->params['breadcrumbs'][] = ['label' => 'Alunos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->alunoId, 'url' => ['view', 'alunoId' => $model->alunoId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="aluno-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
