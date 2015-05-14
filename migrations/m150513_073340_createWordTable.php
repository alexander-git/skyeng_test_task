<?php

use yii\db\Schema;
use yii\db\Migration;

class m150513_073340_createWordTable extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
 
        $this->createTable('{{%word}}', [
            'id' => Schema::TYPE_PK,
            'eng' => Schema::TYPE_STRING . ' NOT NULL',
            'rus' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
 
        $this->createIndex('indexEng', '{{%word}}', 'eng');
        $this->createIndex('indexRus', '{{%word}}', 'rus');
        //TODO# Добавить ограничения на уникальность английского и русского слова.
        //Делается с помощью $this->createCommand->execute().
    }

    public function down()
    {
        $this->dropTable('{{%word}}');
    }

}
