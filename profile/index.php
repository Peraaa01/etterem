<?php
require('../helpers/init.php');
require_once(sprintf('%s/%s', MODELS_DIR, 'User.php')); //relavív útvonal


session_start();


if (!empty($_POST) && array_key_exists('logout', $_POST) && !empty($_POST['logout'])) {

    $user = new User();
    $user->logout();
}

if (empty($_SESSION['logged_in'])) {
    redirect('/login');
}

$keyMap = ['id' => 'Azonosító: ', 'email' => 'E-mail cím: ', 'created_at' => 'Létrehozási idő: ', "password" => "Jelszó: ", "role" => "Szerepkör: "];

?>
<!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <h2>Profil</h2>
        <div style="display: flex; flex-direction: column;">
        <?php foreach($_SESSION['logged_in'] as $key => $data) : ?>
            <div style="display: flex;">
                <p style="font-weight: bold;"><?php echo $keyMap[$key]; ?></p>
                <p><?php echo $data; ?></p>
            </div>
        <?php endforeach; ?>

        <form method="POST" style="flex: 1 0 100%;">
            <input type="hidden" name="logout" value="true" />
            <input type="submit" value="Kijelentkezés" />
        </form>
        </div>
        <br>
        <br>

        <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/profile/update">Adatok módosítása</a>

        <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/">Főoldal</a>
    </body>
</html>
