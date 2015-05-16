<?php

use yii\db\Schema;
use yii\db\Migration;

class m150513_073340_createWordTable extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        
        $this->createTable('{{%word}}', [
            'id' => Schema::TYPE_PK,
            'eng' => Schema::TYPE_STRING . ' NOT NULL',
            'rus' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
        
        // Если нужно добавть ограничения на уникальность слова и его перевода.
        /*
        $this->execute(
            "CREATE TABLE IF NOT EXISTS {{%word}} (
                id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
                eng VARCHAR(255) NOT NULL,
                rus VARCHAR(255) NOT NULL,
                CONSTRAINT UNIQUE(eng),
                CONSTRAINT UNIQUE(rus)                
            ) CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB;"
        );
        */
        
        // Для реального словаря.
        //$this->createIndex('indexEng', '{{%word}}', 'eng');
        //$this->createIndex('indexRus', '{{%word}}', 'rus');
    }

    public function down()
    {
        $this->dropTable('{{%word}}');
    }

}
