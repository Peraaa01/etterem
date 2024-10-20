
<?php
require_once 'init.php';
require_once('helpers.php'); 
require_once(sprintf('%s/%s', MODELS_DIR, 'Food.php'));
require_once('FormController.php'); 

class DishFormController extends FormController
{


    private static bool $initialized = false;

    private static function initialize() {
        if (!self::$initialized) {
            self::$defName="dish";
            self::$initialized = true;
        }
    }


    public static function setFormData(array | null $formData) {
        self::initialize();
        self::$formData = $formData;

    }
    
    public static function handleDishData() : void
    {

        if (isset($_POST['update']) && $_SESSION['logged_in']['role'] === 'admin') {

            $_SESSION['dish_update'] = $_POST['update'];
            header("Location: /admin/dishes/update?id={$_POST['update']}");
            exit();
        }
        else if (isset($_POST['delete']) && $_SESSION['logged_in']['role'] === 'admin') {

            $_SESSION['dish_delete'] = $_POST['delete'];
            header("Location: /admin/dishes/delete?id={$_POST['delete']}");
            exit();
        }
        else if ($_SESSION['logged_in']['role'] === 'admin'){


        }
        else {
            header("Location: /dishes");
            exit();
        }
    }

    public static function saveFormData(array $formData, array| null $definitions, bool  $isUpdate) :array
    {
        if (empty($formData)){ 
            return [];
        } 
        self::initialize();

        
        foreach ($formData as &$fieldData) {
            $fieldData = self::sanitizeData($fieldData);
        }

        self::$formData = $formData;
        if (!empty($errors = self::validateDishFormData($formData, $definitions))) {

            return $errors;
        }

        //Az adatok mentése az adatbázisba

        $food= new Food();
        if (! $isUpdate) {
             $food->create(self::$formData);

            redirect('/dishes');
            return [];
        }
        else {
            $food->update(self::$formData, ['id' => $_SESSION['dish_update']]);
    
            echo "Az étel sikeresen módosítva!";
            unset($_SESSION['dish_update']);

            echo '<script type="text/javascript">
                   setTimeout(function() {
                       window.location.href = "/dishes";
                   }, 1000); // 10000 ms = 10 másodperc
                 </script>';
            exit();
            return [];
        }

    }



    private static function validateDishFormData(array $formData, array | null $definitions)
    {
        $errors = []; // asszociatv tömb lesz a hibaüzenetek tárolására

        self::initialize();

        try {
            if ($definitions===null) {
                throw new Exception('Nem lehetett a JSON fájlt dekódolni.');
            }

            foreach ($formData as $key => $value) {

                foreach ($definitions as $definition) {
                    if ($definition['key'] === $key && array_key_exists('rules', $definition)) {

                       foreach ($definition['rules'] as $rule) {
                            $isError=false;

                            switch ($rule['type'])
                            {
                                case 'min_price':


                                    if ( (int) $value < $rule['condition']) {

                                        $isError=true;
                                    }
                                break;
                                case 'min_length':
                                    if (strlen($value) < $rule['condition']) {
                                       
                                            $isError=true;
                                        }

                                break;
                            }
                            if ($isError) {
                                if (array_key_exists('error', $rule)) {
                                    if (array_key_exists($key, $errors)) {
                                        $errors[$key] .= $rule['error'] . "<br>";
                                    } else {
                                        $errors[$key] = $rule['error'] . "<br>";
                                    }
                                } else {
                                    if (array_key_exists($key, $errors)) {
                                        $errors[$key] .= "Helytelenül kitöltött mező.<br>";

                                    } else {
                                        $errors[$key] = "Helytelenül kitöltött mező.<br>";

                                    }
                                }
                            }
                        }
                    }
                }
    
            }

        }
        catch (Exception $e) {
            error_log($e->getMessage());
            echo 'Hiba történt az adatok ellenőrzése közben. Kérjük próbálja újra később. <a href="/">Vissza a főoldalra</a>';
            exit;
        }

        return $errors;
    }


}
?>