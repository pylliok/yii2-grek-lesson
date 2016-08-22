<?

namespace frontend\components;
use yii\base\Component;
use yii\helpers\Url;
use yii\helpers\BaseFileHelper;
class Common extends Component {

     //���������� ��� �������� ������� � ����������� � ���� � yii2

     // 1) �������� ��������� ����� �������
     // �������� ����� �� ���������� ������ ��� ������� ��������� ������ � ��� ��� ���� ���������� ������ � �����
     const EVENT_NOTIFY = 'notify_admin';





	public function sendMail($subject,$text,$dop,$email='sdfsdfsdf.sdfsdf.99@bk.ru',$name='Sergey')
    { //��������� ����� �� ����� ������� � ������� ��������
        $text = 'Ot kogo: '.$dop[0].'</br> Name: '.$dop[1].'</br>'.$text;
         if(\Yii::$app->mail->compose()
       //  ->setFrom([ \Yii::$app->params['supportEmail'] => \yii::$app->name])
         ->setFrom(['pyiiiok1@ya.ru' => 'Sergey1']) //��� �� ����� ����� �� �������� ������������
         ->setTo([$email => $name])
         ->setSubject($subject)
         ->setHtmlBody($text)
         ->send()) {

              // 2) ����� �������������� ��� ������� ����� ������� � ��� ������� ��� �������

              $this->trigger(self::EVENT_NOTIFY);
              return true;
     }
	}













     public function notifyAdmin($event){

          print "Notify Admin Opoveshenie<br/>";
     }

     public function getTitleAdvert($data) //������� ���������
     {
          return $data['bedroom'].'Bed rooms and ' .$data['kitchen']. 'Kitchen room Aparment on Sale';
     }

    /* public static function getImageAdvert($data,$general = true,$original=false) // ��� �������� ������� �� ������� � ���� ����
     { //������� ������ � maindefaultcontroller � � head ������ ��� fe/view common head
          $image=[];
          $base = Url::base(); //������� ���� �� ��������
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
            $files = BaseFileHelper::findFiles($path);//������� ������ ���� �� ���������� � ����� ������

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
        return substr($data,$start,$end);//�������� ���� �� 0 � ����� �� 50
    }

    public static function getType($row)
    {
        return ($row['sold']) ? 'Sold' : 'New'; //����� ������� ��� ���� � ����� : false ����
    }


}