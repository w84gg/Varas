<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use frontend\controllers\ProductionController;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
?>
<?php
$script = <<< JS
$(document).ready(function() {
    $('.item_buy').on('click', '.button', function(){
        var data = {
            product_id: $(this).parent().parent().find('.call').html(),
            price: $(this).parent().parent().find('.item_price span').html(),
            count: 1,
        };
        $.ajax({
            type: "POST",
            url: "/index.php?r=production/add-in-cart",
            data: data,
            dataType: 'json',
            success: function(response){
                $("#basket").text(function(){
                    return JSON.stringify(response.cartStatus);
                });
            }
        });
    });
});
JS;
$this->registerJs($script);
?>
<div class="production-index">
    <div id="wrapper">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?php
            if (\Yii::$app->user->can('author') or \Yii::$app->user->can('admin')) {
                echo Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']);
            }
            ?>
            <div id="basket">
            </div>

            <div id="items">
                <?php $arr = ProductionController::getAllProds();
                $data = ArrayHelper::toArray($arr, [
                    'common\modules\auth\models\Production' => [
                        'id',
                        'name',
                        'description',
                        'photo',
                        'price',
                    ],
                ]);

                foreach ($data as $item) { ?>

                    <div class="item">
                        <?php
                        echo '<div class="call">'.$item["id"].'</div>';
                        echo '<div class="item_img"><img style="width:200px;" src="'.'uploads/prod/'.$item["photo"].'"></div>';
                        echo '<div class="item_title">'.$item["name"].'</div>';
                        echo "<div class='item_description'>".$item["description"]."</div>";
                        echo "<div class='item_price'><span>".$item["price"]."</span> ₽</div>";
                        echo '<div class="item_buy"><a class="button">Купить</a></div>';

                        /*echo "<div id='item_more'><a href='view/id' id='more'>Подробнее</a></div>";*/
                        ?>
                    </div>
                    <?php  }
                    ?>
                </div>
            </p>
        </div>
    </div>
