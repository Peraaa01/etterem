<?php
require_once('../helpers/init.php');
//require_once(sprintf('%s/%s', HELPERS_DIR, 'helpers.php')); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR, 'Food.php')); //relavív útvonal
require_once(sprintf('%s/%s', HELPERS_DIR, 'OrderFormController.php')); //relavív útvonal
require_once(sprintf('%s/%s', HELPERS_DIR, 'views.php')); //relavív útvonal

session_start();
if (!empty($_SESSION) && array_key_exists('logged_in', $_SESSION)) {
    $userId = $_SESSION["logged_in"];
}
else {
    redirect('/login');
}

OrderFormController::setForm('order');
$definitions = OrderFormController::getDefinition();
$f= new Food();
$foods= $f->getFoods();

$errors = OrderFormController::saveFormData($_POST, $definitions, false);
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendelés leadás</title>
</head>


<body style="display:flex; flex-direction: column; max-width:500px; align-content: center">
    <h2>Új rendelés</h2>

    <form method="post" style="display:flex; flex-direction: column; gap:5px;">
        <?php orderView($definitions, $foods, $errors); ?>
        <input type="submit" value="Kosárba helyezés" />
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