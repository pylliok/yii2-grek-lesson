<?

namespace frontend\components;
use yii\base\Component;
use yii\helpers\Url;
use yii\helpers\BaseFileHelper;
class Common extends Component {

     //показываем как создаётся событие и обработчики к нему в yii2

     // 1) Создаётся константа этого собатия
     // например когды мы отправляем письмо это событие оповещает админа о том что было отправлено письмо с сайта
     const EVENT_NOTIFY = 'notify_admin';





	public function sendMail($subject,$text,$dop,$email='sdfsdfsdf.sdfsdf.99@bk.ru',$name='Sergey')
    { //статичный метод не имеет доступа к обычным методоам
        $text = 'Ot kogo: '.$dop[0].'</br> Name: '.$dop[1].'</br>'.$text;
         if(\Yii::$app->mail->compose()
       //  ->setFrom([ \Yii::$app->params['supportEmail'] => \yii::$app->name])
         ->setFrom(['pyiiiok1@ya.ru' => 'Sergey1']) //тот же самый адрес по которому авторизуемся
         ->setTo([$email => $name])
         ->setSubject($subject)
         ->setHtmlBody($text)
         ->send()) {

              // 2) Нужно активизировать это событие через триггер в нем передаём имя события

              $this->trigger(self::EVENT_NOTIFY);
              return true;
     }
	}













     public function notifyAdmin($event){

          print "Notify Admin Opoveshenie<br/>";
     }

     public function getTitleAdvert($data) //подобие заголовка
     {
          return $data['bedroom'].'Bed rooms and ' .$data['kitchen']. 'Kitchen room Aparment on Sale';
     }

    /* public static function getImageAdvert($data,$general = true,$original=false) // эти картинки выводим на главнйо в виде фона
     { //функции делаем в maindefaultcontroller и в head меняем вся fe/view common head
          $image=[];
          $base = Url::base(); //базовый путь до картинки
         $image[] = 'uploads/adverts/'.$data['idadvert'].'/general/general.jpg';
        /*  if($original)
          {
              // $image[] = $base.'uploads/advert/'.$data['id'].'/general/'.$data['general_image'];
               $image[] = $base.'uploads/advert/'.$data['idadvert'].'/general/general.jpg';
          } else
          {
              // $image[] = $base.'uploads/advert/'.$data['id'].'/general/small_'.$data['general_image'];
               $image[] = $base.'uploads/advert/'.$data['idadvert'].'/general/general.jpg';
          }
          return $image;
     }*/

    public static function getImageAdvert($data,$general = true,$original = false){

        $image = [];
        $base = '/';
        if($general){

            $image[] = $base.'uploads/adverts/'.$data['idadvert'].'/general/general.jpg';
        }
        else{
            $path = \Yii::getAlias("@frontend/web/uploads/adverts/".$data['idadvert']);
            $files = BaseFileHelper::findFiles($path);//передаём полный путь до директории к нашим фоткам

            foreach($files as $file){
                if (/*strstr($file, "small_") && */!strstr($file, "general")) {
                  //  $image[] = $base . 'uploads/adverts/' . $data['idadvert'] . '/' . basename($file);
                    $image[] = $base.'uploads/adverts/'.$data['idadvert'].'/'.basename($file);
                }
            }
        }

        return $image;
    }

    public function substr($data,$start=0,$end=50)
    {
        return substr($data,$start,$end);//Обрезаем текс от 0 й буквы до 50
    }

    public static function getType($row)
    {
        return ($row['sold']) ? 'Sold' : 'New'; //после вопроса тру если и после : false если
    }


}