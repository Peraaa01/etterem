<?php

require_once('../../helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR, 'DishFormController.php')); 
require_once(sprintf('%s/%s', MODELS_DIR, 'Food.php')); 

session_start();

if (!empty($_SESSION) && array_key_exists('logged_in', $_SESSION)) {

    if ($_SESSION['logged_in']['role'] !== 'admin') {
        redirect('/');
    }
}
else {
    redirect('/login');
}

DishFormController::setForm('dish');
$definitions = DishFormController::getDefinition();

DishFormController::handleDishData();


echo '<script>
    function confirmDelete() {
        if (!confirm("Biztosan törölni akarja az ételt?")) {
            event.preventDefault();
        }
    }
</script>';
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ételek listája</title>
</head>

<body style="width:50%;margin:auto;">
<div style="flex: 1 0 auto; margin:25px;">
            <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/admin/dishes/create">Új étel</a>
            <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/">Főoldal</a>
            <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/logout.php">Kijelentkezés</a>
        </div>
    <?php

    $f = new Food();
    $foods = $f->read(['*']);

    ?>
    <h2>Ételek listája</h2>
    <form method="post" style="display:grid; gap:50px; grid-template-columns: auto 100px 100px;justify-content: center;padding:25px;" >
        <?php foreach ($foods as $food) : ?>
            <div style="display:grid; gap:50px; grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr; border: 1px solid black; padding: 5px; margin: 5px;">
                <span style="font-weight: bold;">Étel: <?php echo $food['name'] ?></span>
                <span>Ára:  <?php echo $food['price'] ?></span>
                <span>Leírás: <br> <?php echo $food['description'] ?></span>
                <span>Étel típusa: <?php echo $food['type_id'] ?></span>
                <span>Étel fényképe: <?php echo $food['image_path'] ?></span>
                
                <span>Létrehozva: <?php echo $food['created_at'] ?></span>
                
            </div>
            <div style="padding: 5px; margin: 5px;  "> 
                <button type="submit" name="update" value="<?php echo $food['id']?>" style="background-color: aqua;padding: 15px; border: none; border-radius: 3px;"> Módosítás</button>
            </div>
            <div style="padding: 5px; margin: 5px;">
                <button type="submit" name="delete" value="<?php echo $food['id']?>" onclick="confirmDelete();" style="background-color: red;padding: 15px; border:none; border-radius: 3px;"> Törlés</button>
            </div>
        <?php endforeach; ?>

        </form>

</body>

</html>