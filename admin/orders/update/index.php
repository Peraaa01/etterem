<?php
require_once('../../../helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR,'/helpers.php'));
require_once(sprintf('%s/%s', HELPERS_DIR,'/views.php'));
require_once(sprintf('%s/%s', HELPERS_DIR,'/OrderFormController.php'));
require_once(sprintf('%s/%s', MODELS_DIR,'/Food.php'));

session_start();

OrderFormController::setForm('order');
$definitions= OrderFormController::getDefinition();
$order=null;
$orderData =null;
$foods = (new Food())->getFoods();
$errors = [];


if (!isset($_SESSION['order_update'])) {
    header("Location: /admin/orders");
    exit();
}
if (empty($_POST) ) { //első megnyitáskor a sessionből tölti be az adatokat


    $order = new Order($_SESSION['order_update']);

    $food = new Food($order->{'food_id'});
    $orderData = [
        'name'=>$food->{'name'}, 
        'doses'=>$order->{'doses'},
        'food_id'=>$order->{'food_id'},
        'delivery_home'=>$order->{'delivery_home'},
        'delivery_time'=>$order->{'delivery_time'},
        'price'=>$food->{'price'}
    ];

    OrderFormController::setFormData($orderData);

}
else { //a formon adatokat küldtek
    $errors = OrderFormController::saveFormData($_POST, $definitions, true);
}

?>

<!DOCTYPE html>
<html lang="hu">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Megrendelés módosítás</title>
    </head>


    <body style="display:flex; flex-direction: column; max-width:500px; align-content: center">
    <h2>Megrendelés módosítás</h2>
    <h3>Megrendelés-azonosító: <?php echo $_SESSION['order_update'] ?></h3>

    <form method="post" style="display:flex; flex-direction: column; gap:5px;">
        <?php orderView($definitions, $foods, $errors); ?>
        <input type="submit" value="Módosítás mentés" />
    </form>

    <div style="flex: 1 0 auto; margin-top:15px;">
    <a  style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/orders">Renedeléseim</a>
 <a  style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/">Főoldal</a>
 <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/logout.php">Kijelentkezés</a>
 </div>
</body>

</html>