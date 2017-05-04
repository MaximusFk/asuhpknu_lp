<?php
namespace app\modules\timetable\assets;

use yii\web\AssetBundle;

class TTAsset extends AssetBundle {
    public $sourcePath = '@vendor/bower/jquery/dist';
    public $js = [
        'jquery.min.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
