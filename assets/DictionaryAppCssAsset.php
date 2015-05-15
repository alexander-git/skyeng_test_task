<?php

namespace app\assets;

use yii\web\AssetBundle;

class DictionaryAppCssAsset extends AssetBundle
{
    public $basePath = '@webroot/css/dictionaryApp';
    public $baseUrl = '@web/css/dictionaryApp';
    
    public $css = [
        'layout.css',
        'main.css'
    ];
    
    public $depends = [
        'app\assets\CommonCssAsset',
    ];
    
    
}
