<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%error}}".
 *
 * @property integer $id
 * @property string $word
 * @property string $answer
 * @property integer $type
 */
class Error extends \yii\db\ActiveRecord
{
    // Тип ошибки - возможные значения для поля $type.
    const ENG_TYPE = 0; // Ошибка при переводе с английского на русский. 
    const RUS_TYPE = 1; // Ошибка при переводе с русского на английский.
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%error}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['word', 'answer', 'type'], 'required'],
            [['type'], 'integer'],
            [['word', 'answer'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'word' => 'Слово',
            'answer' => 'Ответ пользователя',
            'type' => 'Тип ошибки',
        ];
    }
}
