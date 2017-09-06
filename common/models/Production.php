<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "production".
 *
 * @property integer $id
 * @property integer $c_id
 * @property string $name
 * @property string $description
 * @property integer $price
 * @property string $photo
 * @property integer $status
 * @property string $date
 * @property integer $createdBy
 */
class Production extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'production';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_id', 'name', 'description', 'price', 'photo', 'status','createdBy'], 'required'],
            [['c_id', 'price', 'status', 'createdBy'], 'integer'],
            [['description'], 'string'],
            [['date', 'photo'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['photo'], 'file', 'extensions'=>'jpg, gif, png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'c_id' => 'Категория',
            'name' => 'Название',
            'description' => 'Описание',
            'price' => 'Цена',
            'photo' => 'Фото',
            'status' => 'Статус',
            'date' => 'Дата добавления',
            'createdBy' => 'User',
        ];
    }
}
