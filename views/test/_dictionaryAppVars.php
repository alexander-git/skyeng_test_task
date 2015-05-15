<?php

use yii\helpers\Url;

$userUrl = Url::to(['test/get-user']);
$startTestUrl = Url::to(['test/start-test']);
$logoutUrl = Url::to(['test/logout'] );
$testDataUrl = Url::to(['test/get-test-data']);
$answerUrl = Url::to(['test/answer']);
$restartTestUrl = Url::to(['test/restart-test']);
$resultsUrl = Url::to(['result/get-results']);
$errorsUrl = Url::to(['error/get-errors']);

$this->registerJs(
    "
        var dictionaryAppUrls = {
            'user' : '$userUrl',
            'startTest' : '$startTestUrl',
            'logout' : '$logoutUrl',
            'testData' : '$testDataUrl',
            'answer' : '$answerUrl',
            'restartTest' : '$restartTestUrl',
            'results' : '$resultsUrl',
            'errors' : '$errorsUrl'
        };
    ",
    \yii\web\View::POS_HEAD,
    'dictionaryAppVars'
);

