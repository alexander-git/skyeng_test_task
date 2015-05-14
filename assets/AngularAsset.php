<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AngularAsset extends AssetBundle
{
    public $basePath = '@webroot/js/lib';
    public $baseUrl = '@web/js/lib';
     
    public $js = [
        'angular/angular.js',
        'angular-route/angular-route.js',
        'angular-animate/angular-anumate.js' //TODO# Удалить, если не будет нужно.
    ];
    
    public $jsOptions = [
        'position' => View::POS_HEAD
    ];

}
