
<?php
require_once 'init.php';
require_once('helpers.php'); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR, 'Order.php')); //relavív útvonal
require_once('FormController.php'); //relavív útvonal

class CartFormController extends FormController
{


    private static bool $initialized = false;

    public static function initialize() {
        if (!self::$initialized) {
            self::$defName="cart";
            self::$formData = $_SESSION['order'];
            self::$initialized = true;
        }
    }


    public static function getFood( int $id) {
        $f = new Food($id);
        return $f;
    }

    public static function handleCartData() : void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['action'] === 'order') {
        
                self::$formData = requestFilter(['user_id','food_id', 'delivery_home', 'delivery_time', 'doses', 'total_amount'], $_SESSION['order']);

                if (self::saveCardData())
                {        
                    echo "Rendelés sikeresen leadva!";
                    unset($_SESSION['order']);
                    echo '<script type="text/javascript">
                            setTimeout(function() {
                            window.location.href = "/orders";
                        }, 1000); // 10000 ms = 10 másodperc
                    </script>';
                    exit();
                }
                else {
                    echo "Hiba történt a rendelés leadása közben!";
                    echo '<script type="text/javascript">
                         setTimeout(function() {
                          window.location.href = "/order";
                     }, 1000); // 10000 ms = 10 másodperc
                    </script>';
                    exit();   
                }
            } elseif ($_POST['action'] === 'clear_cart') {
                // Kosár ürítése
                unset($_SESSION['order']);
                echo  "A kosár kiürítve!";
                echo '<script type="text/javascript">
                     setTimeout(function() {
                      window.location.href = "/orders";
                      }, 1000); // 10000 ms = 10 másodperc
                    </script>';
                exit();
            
            
            } elseif ($_POST['action'] === 'close') {
                unset($_SESSION['order']);
                redirect('/orders');        

            } else if ($_POST['action'] === 'update') {
                $_SESSION['order_update'] = $_POST['update'];
                header("Location: /order/modify");
                exit();
            } 
        }
    }

    public static function saveCardData() : bool
    {

        if (empty(self::$formData)){ 

            return false;
        } 


        if (self::$defName === 'cart') {



                $o = new Order();

                $o->create(self::$formData);

                return true;
            
        }
        return false;
    }
}
?>