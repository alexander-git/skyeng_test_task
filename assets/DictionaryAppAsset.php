<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class DictionaryAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/dictionaryApp/layout.css',
        'css/dictionaryApp/main.css'
    ];
    
    public $js = [
        'js/dictionaryApp/main/main.js',
        'js/dictionaryApp/main/MainController.js',
        'js/dictionaryApp/info/info.js',
        'js/dictionaryApp/info/InfoService.js',
        'js/dictionaryApp/backend/backend.js',
        'js/dictionaryApp/backend/BackendService.js',
        'js/dictionaryApp/pages/start/start.js',
        'js/dictionaryApp/pages/start/StartFormContorller.js',
        'js/dictionaryApp/pages/test/test.js',
        'js/dictionaryApp/pages/test/TestContorller.js',
        'js/dictionaryApp/app.js'  
    ];

    public $depends = [
        'app\assets\AngularAsset',
    ];
    
    public $jsOptions = [
        'position' => View::POS_END
    ];
    
}
