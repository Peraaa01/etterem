
<?php
require_once 'init.php';
require_once('helpers.php'); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR, 'User.php')); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR, 'Food.php')); //relavív útvonal

class FormController
{
    public static string $defName = "";
    public static array $formData = [];

    public static function setForm(string $name)
    {
        self::$defName = $name;
    } 

    public static function  getDefinition()
    {

        $path = sprintf('%s/%s.json', DEFINITION_DIR, self::$defName. 'FormDefinition');

        return json_decode(file_get_contents($path), true);
    }


    public static function getFieldValue(string | int $key)
    {

        if (empty(self::$formData) || !array_key_exists($key, self::$formData) || $key == 'password' || $key == 'password_repeated') {
            return '';
        }
        return self::$formData[$key];
    }
 
    public static function sanitizeData(string | int |null $data)  {
        return trim(strip_tags($data));
    
    }
}
?>