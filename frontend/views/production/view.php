<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Production */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Productions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="production-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'c_id',
            'name',
            'description:ntext',
            'price',
            'photo:ntext',
            'status',
            'date',
        ],
    ]) ?>

</div>
