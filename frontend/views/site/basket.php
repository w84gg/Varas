<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = 'Корзина';

Modal::begin(['id' => 'modal',
'header' => '<h2>Корзина</h2>']);

Html::encode($this->title);

Modal::end();
