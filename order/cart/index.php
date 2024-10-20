<?php
require_once('../../helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR, 'CartFormController.php'));
require_once(sprintf('%s/%s', HELPERS_DIR, 'views.php'));

session_start();
CartFormController::initialize();
if (!empty($_SESSION) && array_key_exists('logged_in', $_SESSION) && array_key_exists('order', $_SESSION)) {
    CartFormController::handleCartData();
}
elseif (!empty($_SESSION) && array_key_exists('logged_in', $_SESSION)) {
    redirect('/order');
}
else {
    redirect('/login');
}


$definitions = CartFormController::getDefinition();

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kosár tartalma</title>
</head>


<body style="display:flex; flex-direction: column; max-width:500px; align-content: center">
    <h2>Kosár tartalma</h2>

    <form method="post"  style="display:flex; flex-direction: column; gap:5px;">
       
        <?php cartView($definitions); ?>

        <!-- Megrendelés gomb -->
        <button type="submit" name="action" value="order">Megrendelés</button>
        
        <!-- Kosár kiürítése gomb -->
        <button type="submit" name="action" value="clear_cart">Kosár kiürítése</button>

    </form>

    <div style="flex: 1 0 auto; margin-top:15px;">
        <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;"
            href="/orders">Rendeléseim</a>
        <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;"
            href="/">Főoldal</a>
        <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;"
            href="/logout.php">Kijelentkezés</a>
    </div>
</body>

</html>