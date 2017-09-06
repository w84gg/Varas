<?php

namespace common\models;

use Yii;
use yii\base\Model;

class OrderForm extends Model {

    public $fname;
    public $lname;
    public $email;
    public $phone;
    public $address;

    public function rules() {
        return [
            [['fname', 'lname', 'email', 'phone', 'address'], 'required'],
            [['email'], 'email'],
        ]
    }

}

?>
