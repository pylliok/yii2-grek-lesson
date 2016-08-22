<?

namespace common\cache;

use yii\caching\FileCache;

class Base64Cache extends FileCache{ // Храним всё в файлах это файловый кэш Filecache

    public $cacheFileSuffix = '.base64'; //добавляем расширение

    protected function getValue($key) //срабатыает когда мы возвращаем значения это два стандартных метода
    {
        $value = base64_decode(parent::getValue($key));
        return $value;
    }

    protected function setValue($key, $value, $duration){ //когда устанавливаем key-ключ value-значение duration-время
        $value = base64_encode($value);
        parent::setValue($key,$value,$duration);
    }

}