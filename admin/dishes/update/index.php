<?php
require_once('../../../helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR,'/helpers.php'));
require_once(sprintf('%s/%s', HELPERS_DIR,'/views.php'));
require_once(sprintf('%s/%s', HELPERS_DIR,'/DishFormController.php'));
require_once(sprintf('%s/%s', MODELS_DIR,'/FoodType.php'));

session_start();

DishFormController::setForm('dish');

$dish=null;
$dishData =null;
$errors = [];
$definitions = DishFormController::getDefinition();

if (!isset($_SESSION['dish_update'])) {
    header("Location: /admin/dishes");
    exit();
}

if (!empty($_POST)) { //ha a formon adatokat küldtek, akkor azokkal felülírja az előzőeket
    $errors = DishFormController::saveFormData($_POST, $definitions, true);

}
else if (!empty($_GET) && key_exists('id', $_GET) && isset($_SESSION['dish_update']) && $_GET['id']== $_SESSION['dish_update'] ) { //első megnyitáskor a sessionből tölti be az adatokat


    $dish = new Food($_SESSION['dish_update']);
    $dishData = [
        'name'=>$dish->name, 
        'price'=>$dish->price,
        'description'=>$dish->description,
        'type_id'=>$dish->type_id,
        'image_path'=>$dish->image_path];
    DishFormController::setFormData($dishData);

}


$ft= new FoodType();
$foodtypes= $ft->read(['*']);

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Étel módosítása</title>
</head>


    <body style="display:flex; flex-direction: column; max-width:500px; align-content: center">
    <h2>Étel módosítás</h2>

    <form method="post">        
        <div style="display:flex; flex-direction: column; gap:5px;">
            <?php dishView($definitions, $foodtypes, $errors) ?>
            <input type="submit" value="Mentés"/>
        </div>

    </form>

    <div style="flex: 1 0 auto; margin-top:15px;">
    <a  style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/articles">Cikkeim</a>
 <a  style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/">Főoldal</a>
 <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/logout.php">Kijelentkezés</a>
 </div>
</body>

</html>