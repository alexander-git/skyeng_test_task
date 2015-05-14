<?php

use yii\db\Schema;
use yii\db\Migration;

class m150513_075159_installDictionary extends Migration
{
    public function up()
    {
        $this->batchInsert('{{%word}}', 
            ['eng', 'rus'],
            [
                ['pear', 'груша'],
                ['orange', 'апельсин'],
                ['grape', 'виноград'],
                ['lemon', 'лимон'],
                ['pineapple', 'ананас'],
                ['watermelon', 'арбуз'],
                ['coconut', 'кокос'],
                ['banana', 'банан'],
                ['pomelo', 'помело'],
                ['strawberry', 'клубника'],
                ['raspberry', 'малина'],
                ['melon', 'дыня'],
                ['peach', 'персик'],
                ['apricot', 'абрикос'],
                ['mango', 'манго'],
                ['plum', 'слива'], 
                ['pomegranate', 'гранат'],
                ['cherry', 'вишня']
            ]
        );
    }

    public function down()
    {
        $this->delete('{{%word}}');
    }
    
}
