<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%result}}".
 *
 * @property integer $id
 * @property string $username
 * @property integer $points
 */
class Result extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%result}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'points'], 'required'],
            [['points'], 'integer'],
            [['username'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'points' => 'Points',
        ];
    }
}
