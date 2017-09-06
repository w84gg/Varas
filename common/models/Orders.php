<?php

namespace common\models;
use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property integer $phone
 * @property string $address
 * @property integer $status
 * @property string $date
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['phone', 'status'], 'integer'],
            [['date'], 'safe'],
            [['first_name', 'last_name', 'email', 'address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'status' => 'Status',
            'date' => 'Date',
        ];
    }

    public function getProductsCount() {
        $count = OrdersProd::find()->where('order_id=:id',[':id'=>$this->id])->count();
        return $count;
    }

    public function getAmount() {
        $amount = OrdersProd::findBySql('SELECT SUM(price*count) FROM orders_prod WHERE order_id="'.$this->id.'"')->scalar();
        return $amount;
    }
}
