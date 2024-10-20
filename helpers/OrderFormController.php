
<?php
require_once 'init.php';
require_once('helpers.php'); 
require_once(sprintf('%s/%s', MODELS_DIR, 'User.php')); 
require_once(sprintf('%s/%s', MODELS_DIR, 'Order.php'));
require_once(sprintf('%s/%s', MODELS_DIR, 'Food.php'));
require_once(sprintf('%s/%s', MODELS_DIR, 'FoodType.php'));
require_once('FormController.php'); 

class OrderFormController extends FormController
{


    private static bool $initialized = false;

    private static function initialize() {
        if (!self::$initialized) {
            self::$defName="order";
            self::$initialized = true;
        }
    }


    public static function getFood( int $id) {
        $f = new Food($id);
        return $f;
    }


    public static function setFormData(array | null $formData) {
        self::initialize();
        self::$formData = $formData;

    }
    
    public static function handleOrderData() : void
    {
        if (isset($_POST['update']) && $_SESSION['logged_in']['role'] === 'admin') {
            $_SESSION['order_update'] = $_POST['update'];
            header("Location: /order/modify");
            exit();
        }
        else if (isset($_POST['delete']) && $_SESSION['logged_in']['role'] === 'admin') {
            $_SESSION['order_delete'] = $_POST['delete'];
            header("Location: /order/delete");
            exit();
        }
        else if (isset($_POST['details'])) {

            $o=new Order($_POST['details']);
            $os=$o->read(['*'], "id=".$_POST['details']);
            $orderData=reset($os);


            $f= new Food($orderData['food_id']);
            $orderData['delivery_home_price'] = $o->getDeliveryHomePrice();
            $orderData['name'] = $f->{'name'};

            $fp = new FoodType($f->{'type_id'});
            $orderData['food_type'] = $fp->{'name'};
            
            $orderData['price'] = $f->{'price'};
            $orderData['description'] = $f->{'description'};
            $orderData["image_path"] = $f->{'image_path'};

            $_SESSION['order'] = $orderData;

            header("Location: /orders/cart");
            exit();
        }
        else {
            header("Location: /orders");
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
        if (!empty($errors = self::validateOrderFormData($formData, $definitions))) {

            return $errors;
        }

        //Az adatok mentése az adatbázisba

        $orderData=$formData;

        $orderData['delivery_home'] = $formData['delivery_home'] ?? 0;
        if ( $orderData['delivery_home']) {
        $orderData['delivery_time'] = date('Y-m-d H:i:s', strtotime($formData['delivery_time']));
        }
        else {
            $orderData['delivery_time'] = null;
        }

        $orderData['user_id'] = $_SESSION['logged_in']['id'];

        $f= new Food($orderData['food_id']);
        $o = new Order();
        $orderData['total_amount']= (int)$orderData['doses'] * $f->{'price'} + ($orderData['delivery_home'] ? $o->getDeliveryHomePrice() : 0);
       
        
        self::$formData=[];
        
        if (! $isUpdate) {

            $orderData['delivery_home_price'] = $o->getDeliveryHomePrice();
            $orderData['name'] = $f->{'name'};

            $fp = new FoodType($f->{'type_id'});
            $orderData['food_type'] = $fp->{'name'};
            
            $orderData['price'] = $f->{'price'};
            $orderData['description'] = $f->{'description'};
            $orderData["image_path"] = $f->{'image_path'};
            $_SESSION['order'] = $orderData;
            redirect('/order/cart');
            return [];
        }
        else {
            $o->update($orderData, ['id' => $_SESSION['order_update']]);
    
            echo "Rendelés sikeresen módosítva!";
            unset($_SESSION['order']);
            unset($_SESSION['order_update']);
            echo '<script type="text/javascript">
                   setTimeout(function() {
                       window.location.href = "/admin/orders";
                   }, 1000); // 10000 ms = 10 másodperc
                 </script>';
            exit();
            return [];
        }

    }



    private static function validateOrderFormData(array $formData, array | null $definitions)
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
                                case 'min':


                                    if ( (int) $value < $rule['condition']) {

                                        $isError=true;
                                    }
                                break;
                                case 'hour_limit':
                                    if (array_key_exists('delivery_home', $formData) && $formData['delivery_home'] == 1) {
                                        $now = new DateTime(date('Y-m-d H:i:s'));
                                    
                                        $demand = new DateTime($value);
    
                                        if ( $now->add( DateInterval::createFromDateString($rule['condition']." hour")) > $demand) {
                                            $isError=true;
                                        }
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