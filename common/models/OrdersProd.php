<?php

namespace common\models;
use Yii;

/**
* This is the model class for table "orders_prod".
*
* @property string id
* @property string $prod_id
* @property string $order_id
* @property string $count
* @property string $price
*/
class OrdersProd extends \yii\db\ActiveRecord
{
    /**
    * @inheritdoc
    */
    public static function tableName()
    {
        return 'orders_prod';
    }

    /**
    * @inheritdoc
    */
    public function rules()
    {
        return [
            [['prod_id', 'order_id', 'price', 'count'], 'required'],
            [['prod_id', 'order_id', 'count', 'id'], 'integer'],
            [['price'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
        ];
    }

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prod_id' => 'Prod ID',
            'order_id' => 'Order ID',
            'count' => 'Count',
            'price' => 'Price',
        ];
    }
}
