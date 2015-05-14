<?php

use yii\db\Schema;
use yii\db\Migration;

class m150513_074632_createErrorTable extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
 
        $this->createTable('{{%error}}', [
            'id' => Schema::TYPE_PK,
            'word' => Schema::TYPE_STRING . ' NOT NULL',
            'answer' => Schema::TYPE_STRING . ' NOT NULL',
            'type' => Schema::TYPE_SMALLINT .' NOT NULL'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%error}}');
    }
    
}
