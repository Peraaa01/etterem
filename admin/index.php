<?php
require_once('../helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR,'/helpers.php'));
require_once(sprintf('%s/%s', HELPERS_DIR,'/DatabaseManager.php'));

session_start();

$errors= login_admin();


?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin belépés</title>
</head>


    <body style="display:flex; flex-direction: column; max-width:500px; align-content: center">
    <h2>Adminisztrátori belépés</h2>

    <form method="get">        
        <div style="display:flex; flex-direction: column; gap:5px;">
            <label>E-mail cím</label>
            <input type="text" name="email" value="<?php echo empty($_GET['email']) ? '' : $_GET['email'] ?>"/>
            <?php if (is_array($errors) && array_key_exists('email',$errors) && !empty($errors['email'])) : ?>
                <div style="color:red;"> <?php echo $errors['email'] ?></div>
            <?php endif ?>
            
            <label>Jelszó</label>
            <input type="password" name="password" />
            <?php if (is_array($errors) && array_key_exists('password',$errors) && !empty($errors['password'])) : ?>
                    <div style="color:red;"> <?php echo $errors['password'] ?></div>
            <?php endif ?>
            <input type="submit" value="Bejelentkezés"/>
        </div>

    </form>

    <div style="flex: 1 0 auto; margin-top:15px;">
 <a  style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/register/index.php">Regisztráció</a> 
 <a  style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/">Főoldal</a>
 </div>
</body>

</html>