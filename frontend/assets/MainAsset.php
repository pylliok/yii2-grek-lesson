<?
namespace frontend\assets;


use yii\web\AssetBundle;
use yii\web\View;

class MainAsset extends  AssetBundle{ //¬се ассетсы наследуютс€ от этого класса //подключаетс€ в view/layouts/bootstrap

    public $basePath = '@webroot'; //корневой путь от этого корн€ будети скаить файлы (frontend/web сюда идЄт)
    public $baseUrl = '@web';// путь в браузере (это так называемые алиасы)

    public $css = [
        'source/style.css',
        'source/owl-carousel/owl.carousel.css',
        'source/owl-carousel/owl.theme.css',
        'source/slitslider/css/style.css',
        'source/slitslider/css/custom.css',
        'css/site.css',
    ];

    public $js = [
        'source/script.js',
        'source/owl-carousel/owl.carousel.js',
        'source/slitslider/js/modernizr.custom.79639.js',
        'source/slitslider/js/jquery.ba-cond.min.js',
        'source/slitslider/js/jquery.slitslider.js',
        'source/js/sait.js',
        'source/js/google_analytics_auto.js'
    ];

    public $depends = [ // через эти зависимости можем подгружать дополнительные стили
        'yii\web\YiiAsset', // yii.js, jquery.js   
        'yii\bootstrap\BootstrapAsset', // bootstrap.css
        'yii\bootstrap\BootstrapPluginAsset' // bootstrap.js
    ];

    public $jsOptions = [ //настроечный массив 
      'position' =>  View::POS_BEGIN, // все Js скрипты будут подключатьс€ вниз страницы
    ];


}