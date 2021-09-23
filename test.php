class Translate {

    const DETECT_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/detect';
    const TRANSLATE_YA_URL = 'https://translate.yandex.net/api/v1.5/tr.json/translate';

   
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