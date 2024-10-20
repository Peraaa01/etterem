<?php

require_once('../helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR, 'helpers.php')); 
require_once(sprintf('%s/%s', MODELS_DIR, 'Order.php')); 
require_once(sprintf('%s/%s', HELPERS_DIR, 'OrderFormController.php')); 

session_start();

OrderFormController::setForm('order');
if (!empty($_SESSION) && array_key_exists('logged_in', $_SESSION) && !empty($_POST)) {
    OrderFormController::handleOrderData();
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendelések</title>
</head>

<body>

    <?php

    $o = new Order();
    $userId = $_SESSION['logged_in']['id'];
    $orders = $o->read_two_table(['orders.id', 'name', 'doses', 'delivery_home', 'delivery_time','total_amount', 'orders.created_at'], "user_id=$userId","order by orders.created_at desc", "foods", ['food_id', 'foods.id']);
    ?>
    <h2>Rendeléseim (<?php echo 'user_ID:'.$_SESSION['logged_in']['id']?>)</h2>
    <form method="post" style="display:grid; gap:50px; grid-template-columns: auto 100px;justify-content: center; padding:25px;">
        <?php foreach ($orders as $order) : ?>
            <div style="display:grid; gap:50px; grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr; border: 1px solid black; padding: 5px; margin: 5px;">
                <span style="font-weight: bold;">Rendelés-azonosító: <?php echo $order['id'] ?></span>
                <span >Étel: <?php echo $order['name'] ?></span>
                <span>Adag: <br> <?php echo $order['doses'] ?></span>
                <span>Házhozszállítás: <br> <?php echo $order['delivery_home'] ? "Kért." : "Nem kért." ?></span>
                <span>Kiszállítás ideje: <?php echo $order['delivery_time'] ?></span>
                <span>Végösszeg: <?php echo $order['total_amount'] ?></span>
                <span>Rögzítve: <?php echo $order['created_at'] ?></span>
            </div>
            <div style="padding: 5px; margin: 5px;"> 
                <button type="submit" name="details" value="<?php echo $order['id']?>" style="background-color: aqua;padding: 15px; border: none; border-radius: 3px;"> Részletes megtekintés</button>
            </div>

        <?php endforeach; ?>

        </form>
        <div style="flex: 1 0 auto; margin:25px;">
            <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/order">Új Rendelés</a>
            <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/">Főoldal</a>
            <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/logout.php">Kijelentkezés</a>
        </div>
</body>

</html>