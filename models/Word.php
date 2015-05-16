<?php

namespace app\models;

/**
 * This is the model class for table "{{%word}}".
 *
 * @property integer $id
 * @property string $eng
 * @property string $rus
 */
class Word extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%word}}';
    }

    public function rules()
    {
        return [
            [['eng'], 'required'],
            [['eng'], 'string', 'max' => 255],
            [['eng'], 'unique'],

            [['rus'], 'required'],
            [['rus'], 'string', 'max' => 255],
            [['rus'], 'unique'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'eng' => 'Eng',
            'rus' => 'Rus',
        ];
    }
}
