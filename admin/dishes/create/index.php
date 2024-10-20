<?php
require_once('../../../helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR,'/helpers.php'));
require_once(sprintf('%s/%s', MODELS_DIR,'/FoodType.php'));
require_once(sprintf('%s/%s', HELPERS_DIR,'/DishFormController.php'));

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
$ft= new FoodType();
$foodtypes= $ft->read(['*']);

$errors = DishFormController::saveFormData($_POST, $definitions, false);

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Új étel</title>
</head>


<body style="display:flex; flex-direction: column; max-width:500px; align-content: center">
    <h2>Új étel</h2>

    <form method="post" style="display:flex; flex-direction: column; gap:5px;">
        <?php foreach ($definitions as $field ): ?>
        <div style="display:flex; flex-direction: column;">
            <label> <?php echo $field['label'] ?> </label>
            <?php if ( array_key_exists('type', $field) && $field['type'] === 'textarea') : ?>
                <textarea name="<?php echo $field['key']; ?>" rows="10" cols="50"><?php echo DishFormController::getFieldValue($field['key'])?></textarea>
            <?php elseif (array_key_exists('type', $field) && $field['type'] == 'select') : ?>
                <select name="<?php echo $field['key'] ?>" value="<?php echo 
                DishFormController::getFieldValue($field['key']) ?>">
                    <?php foreach ($foodtypes as $foodtype) : ?>
                        <option <?php if ($foodtype['id']==DishFormController::getFieldValue($field['key'])) echo "selected"; ?> value="<?php echo $foodtype['id'] ?>"><?php echo $foodtype['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else : ?>
                <input type="<?php echo $field['type'] ?? 'text' ?>" name="<?php echo $field['key'] ?>"
                value="<?php echo DishFormController::getFieldValue($field['key']) ?>" />
            <?php endif; ?>
            <?php
            if (is_array($errors) && !empty($errors) && array_key_exists($field['key'],$errors) && !empty($errors[$field['key']])) : ?>
            <div style="color:red;"> <?php echo $errors[$field['key']] ?></div>
            <?php endif ?>

        </div>
        <?php endforeach; ?>

        <input type="submit" value="Mentés" />


    </form>

    <div style="flex: 1 0 auto; margin-top:15px;">
        <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;"
            href="/admin/order/">Rendelés adminisztráció</a>
        <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;"
            href="/">Főoldal</a>
        <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;"
            href="/logout.php">Kijelentkezés</a>
    </div>
</body>

</html>