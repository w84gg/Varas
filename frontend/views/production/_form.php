<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Types;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Production */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$types = Types::find()->all();
$items = ArrayHelper::map($types,'id', 'title');
$params = ['prompt' => '- Выберите категорию'];
$item = ['1' => 'Активный', '0' => 'Нет в наличии'];
$param = ['options' =>[ '1' => ['Selected' => true]]];
?>
<div class="production-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'c_id')->dropDownList($items, $params) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'photo')->fileInput() ?>
    
    <?php if ($model->photo != null) {
        echo '<img src="uploads/prod/'.$model->photo.'" width="180px">&nbsp;&nbsp;&nbsp;';
        echo Html::a('Удалить фото', ['production/delphoto', 'id'=>$model->id], ['class'=>'btn btn-danger', 'style'=>'display:block;width:180px;']).'<p>';
    }
    ?>

    <?= $form->field($model, 'status')->dropDownList($item, $param) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'createdBy')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
