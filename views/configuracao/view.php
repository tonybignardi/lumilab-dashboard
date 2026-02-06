<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Configuracao $model */

$this->title = $model->configuracaoId;
$this->params['breadcrumbs'][] = ['label' => 'Configuracaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="configuracao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'configuracaoId' => $model->configuracaoId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'configuracaoId' => $model->configuracaoId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'configuracaoId',
            'configuracaoDescricao',
            'configuracaoValor',
            'configuracaoInfo',
        ],
    ]) ?>

</div>
