<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%word}}".
 *
 * @property integer $id
 * @property string $eng
 * @property string $rus
 */
class Word extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%word}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eng', 'rus'], 'required'],
            ['eng', 'unique'],
            ['rus', 'unique'],
            [['eng', 'rus'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eng' => 'Eng',
            'rus' => 'Rus',
        ];
    }
}
