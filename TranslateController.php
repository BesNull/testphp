<?php

class TranslateController {

    const DETECT_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/detect';
    const TRANSLATE_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/translate';

   // public static $key = null ;
    public static $key = "AlzalyCf2zgkmk-nRxdbB4gg49M9GZhmFei55uo" ;
    //т.к. у нас ключ не меняются, то следует сделать свойство $key статичным, значение присвоится только один раз при первом вызове метоода класса.

    public static function init(){
        //"parent::" используется для доступа к методам и свойствам базового (родительского) класса
   //  parent::init(); //здесь мы не можем вызвать init(), т.к. нет родительского класса с такой функцией
        //такой вызов можно использовать в дочернем классе (например class TranslateExtension extends Translate), можем обратиться к переменной из родителя,
        //можем, например, создать такую же функцию init() в TranslateExtension и в ней вызвать parent::init() из Translate.
        //Допустим, в дочернем классе в функции init мы задали какие-то условия c помощью if, если они выполняются, то выполняется код в дочернем классе, иначе вызываем parent::init() из родителя и выполнится код из родительского класса

        if (empty( self::$key)) 
            {
            throw new InvalidConfigException("Field <b>$key</b> is required");
            }
    }
    //"self::" берет данные из класса, в котором находиться
    //"static::" может работать как self, а также может взять данные из дочернего класса. Т.е. вызываем static свойство в родителе, а данные записываются из дочернего. Опять же перемещаем вызов в дочерний класс и сработает как self
        /*
        class Model {
       public static $table='table';
       public static function getTable() {
          return static::$table;
          }
        }
        class User extends Model{
          public static $table='users';
       }
       echo User::getTable(); //выведет 'users'
         */
     
    
    /**
    * @param $format text format need to translate
    * @return string
    */
    public static function translate_text($format="text") {  //если в данном случае объявляем метод класса статическим, то тогда мы не можем использовать конструкцию $this->key, необходимо использоватть self::$key, но тогда свойство key тоже должно быть статическим (ошибка Access to undeclared static property)
    
    self::init();
   // if (empty($this->key)) { throw new InvalidConfigException("Field <b>$key</b> is required");} можно убрать, заменив строкой выше.
    $values = array(
        'key' => self::$key,
        'text' => $_GET['text'],
        'lang' => $_GET['lang'],
        'format' => $format == "text" ? 'plain' : $format,
    );
    $formData = http_build_query($values) ;
    $ch = curl_init(self::TRANSLATE_YA_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

    $json = curl_exec($ch);
    curl_close($ch) ;

    $data = json_decode($json, true);

    if ($data['code']==200) {
        return $data['text'];
    }
    return $data;
    }
}


function indexAction($smarty){  //функция smarty, так и должнеа называться

    
    //TranslateController::translate_text();
  // $tran = new TranslateController();
   //$tran->init();
    $tran=TranslateController::translate_text();
    simpledebug($tran);
     
    
    $smarty->assign('pageName', "Translate");
    
    TemplateLoading($smarty, 'header');
    TemplateLoading($smarty, 'translate');
    TemplateLoading($smarty, 'footer');
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class TranslateController1 {
    const DETECT_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/detect';
    const TRANSLATE_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/translate';
    const TRANSLATE_YA_URL1 = 'https://translate.api.cloud.yandex.net/translate/v2/translate';
    const GET_IAM_TOKEN_URL = 'https://iam.api.cloud.yandex.net/iam/v1/tokens';

 public $key = "AlzalyCf2zgkmk-nRxdbB4gg49M9GZhmFei55uo"; //для старой версии;
 public $key1 = "b1g40pvdlleomcooersp"; //этот ключ из яндекс CLOUD
 public $auth ="AQAAAABYogzJAATuwf1kg7agekCik-N7yMXXXhI"; //Этот кллюч из инструкции для получаения IAM TOKEN

 
 //его надо менять раз в сутки с помощью $auth, auth так же может измениться
public $IAM_TOKEN = "t1.9euelZqNmJnMzJPOmcaRm53NysmOke3rnpWaj52UyJjPkMbNksaVisaLiorl8_d2elJ1-e8eSXox_t3z9zYpUHX57x5JejH-.HwNvusfiYOFFAoj5xfDANJFKOKNzl2WGWJmBG1Dz9Y8PJNVdp0gpvaJp1YUF_xPWQ7FcV75exzjzXA3FXcrPAg";


    public function init(){
    parent::init();

    if (empty( $this->key)) 
        {
        throw new InvalidConfigException("Field <b>$key</b> is required");
        }
    }

    /**
    * @param $format text format need to translate
    * @return string
    */
    public function translate_text_new($format="text") {
    if (empty($this->key1)) { throw new InvalidConfigException("Field <b>$key</b> is required");}
    
    
    //массив для новой версии
    $values = array(
        "folderId" => $this->key1,    
        "texts" => ["Hello", "bye"],
        "targetLanguageCode"=> "ru"
       // 'format' => $format == "text" ? 'plain' : $format,  //Такое поле также считается за токен
    );
    $formData1 = json_encode($values);

    //с новой версией
    $passiam = array(     // для хедера, 
        'Authorization: Bearer '.$this->IAM_TOKEN,
        'Content-Type:application/json'
        );

    $ch = curl_init(self::TRANSLATE_YA_URL1);
    //simpledebug($getiam);
    
    //curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $passiam);   //для хедера не создаем json, либо что-то еще, а тупо кидаем массив
    curl_setopt($ch, CURLOPT_POSTFIELDS, $formData1); //без этой строчки вернется пустой запрос, а с ней ругается на то, что облако неактивно, но вроде должно работать, если занесем денег
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $json = curl_exec($ch);
    curl_close($ch) ;
    $NewVerTran = json_decode($json, true);
    //simpledebug($NewVerTran);
       if ($NewVerTran['code']==200) {
        return $NewVerTran['text'];
    }
    simpledebug($NewVerTran);
    return $NewVerTran;
    }
    
    public function translate_text_old($format="text") {
          //старая версия!!!!!!!!!!!!!!
   
    $values = array(
        'key' => $this->key,
        'text' => $_GET['text'],
        'lang' => $_GET['lang'],
        'format' => $format == "text" ? 'plain' : $format,
    );
    $formData = http_build_query($values) ;
    
    $ch = curl_init(self::TRANSLATE_YA_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);
    //simpledebug($ch);
    $json = curl_exec($ch);
    curl_close($ch);
//simpledebug($json);
    $data = json_decode($json, true);
//simpledebug($data);
    if ($data['code']==200) {
        return $data['text'];
    }
    simpledebug($data);
    return $data;
     }
}