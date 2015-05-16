<?php

namespace app\models;

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
    
    
    public static function tableName()
    {
        return '{{%error}}';
    }

    public function rules()
    {
        return [
            [['word'], 'required'],
            [['word'], 'string', 'max' => 255],
            
            [['answer'], 'required'],
            [['answer'], 'string', 'max' => 255],
            
            [['type'], 'required'],
            [['type'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'word' => 'Слово',
            'answer' => 'Ответ пользователя',
            'type' => 'Тип ошибки',
        ];
    }
}
