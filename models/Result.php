<?php

namespace app\models;

/**
 * This is the model class for table "{{%result}}".
 *
 * @property integer $id
 * @property string $username
 * @property integer $points
 */
class Result extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%result}}';
    }

    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'string', 'min' => 3, 'max' => 30],
            
            [['points'], 'required'],
            [['points'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'username' => 'Имя пользователя',
            'points' => 'Баллы',
        ];
    }
}
