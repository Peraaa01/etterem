<?php
require_once 'init.php'; 
require_once(sprintf('%s/%s', MODELS_DIR, 'Order.php'));

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'order') {

        $orderData = requestFilter(['user_id','food_id', 'delivery_home', 'delivery_time', 'doses', 'total_amount'], $_SESSION['order']);
        $o = new Order();
        $o->create($orderData);

        echo "Rendelés sikeresen leadva!";
        unset($_SESSION['order']);
        echo '<script type="text/javascript">
                setTimeout(function() {
                window.location.href = "/orders";
            }, 1000); // 10000 ms = 10 másodperc
        </script>';
        exit();
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
    } else if ($_POST['action'] === 'update') {
        $_SESSION['order_update'] = $_POST['update'];
        header("Location: /order/modify");
        exit();
    } 
}

?>