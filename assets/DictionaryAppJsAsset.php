<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class DictionaryAppJsAsset extends AssetBundle
{
    public $basePath = '@webroot/js/dictionaryApp';
    public $baseUrl = '@web/js/dictionaryApp';
    
    public $js = [
        'url/url.js',
        'url/UrlService.js',
        'info/info.js',
        'info/InfoService.js',
        'backend/backend.js',
        'backend/BackendService.js',
        'main/main.js',
        'main/MainController.js',
        'pages/pages.js',
        'pages/StartFormContorller.js',
        'pages/TestContorller.js',
        'pages/ResultContorller.js',
        'pages/ErrorContorller.js',
        'app.js'  
    ];

    public $depends = [
        'app\assets\AngularAsset',
    ];
    
    public $jsOptions = [
        'position' => View::POS_END
    ];
    
}
