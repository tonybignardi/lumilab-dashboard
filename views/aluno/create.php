<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Aluno $model */

$this->title = 'Novo Estudante';
$this->params['breadcrumbs'][] = ['label' => 'Alunos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="aluno-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
