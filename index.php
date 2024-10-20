<?php

require_once('./helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR, 'helpers.php')); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR, 'User.php')); //relavív útvonal

session_start();
$userId = null;
if (!empty($_SESSION) && array_key_exists('logged_in', $_SESSION)) {
    $userId = $_SESSION["logged_in"]['id'];
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal</title>
</head>

<body style="display:flex; flex-wrap: wrap; gap:5px; max-width:500px; align-content: center">

    <div style="flex: 1 0 auto;">
        <?php if (!is_null($userId)) : $user = new User($userId);  ?>
            <h2>Üdvözöllek, <?php echo $user->{'email'} ?>!</h2>
            <?php if ($user->isAdministrator( $userId )) :?> 
                <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/order">Rendelés leadás</a>
                <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/orders">Rendeléseim</a>
                <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/profile">Profilom</a>
                <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/logout.php">Kijelentkezés</a>
                <br>
                <br> 
                <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/admin/dishes">Ételek adminisztrálása</a>
                <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/admin/orders">Rendelések adminisztrálása</a>

            <?php else :?>    
                <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/order">Rendelés leadás</a>
                <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/orders">Rendeléseim</a>
                <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/profile">Profilom</a>
                <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/logout.php">Kijelentkezés</a>
            <?php endif ?>    
        <?php else : ?>
            <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/register">Regisztráció</a>
            <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/login">Bejelentkezés</a>
            <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/admin">Bejelentkezés - Adminisztrátoroknak</a>
        <?php endif ?>
    </div>
</body>

</html>