<?php
/* @var $this yii\web\View */

// Зарегистрируем неебходимые скрипты.
$this->render('_dictionaryAppVars');

$this->title = 'Словарь';

?>
<div ng-app="dictionaryApp">
    <div ng-view></div>
</div>