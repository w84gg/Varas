<?php
namespace frontend\components;

use common\models\Orders;
use common\models\OrdersProd;
use yii\base\Component;
use Yii;

/**
* Class Cart
* @package frontend\components
* @property Orders $order
* @property string $status
*/
class Cart extends Component
{
    const SESSION_KEY = 'order_id';

    private $_order;

    public function add($productId, $price, $count)
    {
        if (!Yii::$app->cart->getOrder())
         {
            Yii::$app->cart->createOrder();
        }
        $link = OrdersProd::findOne(['prod_id' => $productId, 'order_id' => $this->order->id]);
        if (!$link) {
            $link = new OrdersProd();
        }
        $link->prod_id = $productId;
        $link->order_id = $this->order->id;
        $link->price = $price;
        $link->count += $count;
        if ($link->save()) {
            return true;
        }
    }

    public function getOrder()
    {
        if ($this->_order == null) {
            $this->_order = Orders::findOne(['id' => $this->getOrderId()]);
        }
        return $this->_order;
    }

    public function createOrder()
    {
        $order = new Orders();
        $order->status = 1;
        $order->save();
        $this->_order = $order;
        if ($order->save()) {
            return true;
        }
    }

    private function getOrderId()
    {
        if (!Yii::$app->session->has(self::SESSION_KEY)) {
            if ($this->createOrder()) {
                Yii::$app->session->set(self::SESSION_KEY, $this->_order->id);
            }
        }
        return Yii::$app->session->get(self::SESSION_KEY);
    }

    public function delete($productId)
    {
        $link = OrdersProd::findOne(['product_id' => $productId, 'order_id' => $this->getOrderId()]);
        if (!$link) {
            return false;
        }
        return $link->delete();
    }

    public function setCount($productId, $count)
    {
        $link = OrdersProd::findOne(['product_id' => $productId, 'order_id' => $this->getOrderId()]);
        if (!$link) {
            return false;
        }
        $link->count = $count;
        return $link->save();
    }

    public function getStatus()
    {
        if ($this->isEmpty()) {
            return Yii::t('app', 'Корзина пуста');
        }
        return Yii::t('app', 'В корзине {productsCount, number} {productsCount, plural, one{товар} few{товара} many{товаров} other{товара}} на сумму {amount} руб.', [
            'productsCount' => $this->order->productsCount,
            'amount' => $this->order->amount
        ]);
    }

    public function isEmpty()
    {
        if (!Yii::$app->session->has(self::SESSION_KEY)) {
            return true;
        }
        return $this->order->productsCount ? false : true;
    }

    public function clean()
    {
        Yii::$app->session->remove(self::SESSION_KEY);
    }
}
