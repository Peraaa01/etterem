<?php

require_once('../helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR, 'helpers.php')); //relavív útvonal
require_once(sprintf('%s/%s', HELPERS_DIR, 'UserFormController.php')); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR, 'User.php')); //relavív útvonal

session_start();

// áttettünk minden logikát, amit csak lehetett, a FormController-be

$definitions=UserFormController::getDefinition();
$errors = UserFormController::saveFormData($_POST, $definitions);
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
</head>

<body>
    <h2>Regisztráció</h2>
    <form method="post" style="display:flex; flex-direction: column; gap:5px;  max-width:500px; align-content: center; margin:20px">

        <?php foreach ($definitions as $field): ?>
            <div style="display:flex; flex-direction: column;">
                <label> <?php echo $field['label'] ?> </label>
                <?php if ( key_exists('type', $field) && $field['type'] === 'checkbox') : ?>
                    <input
                    type="<?php echo $field['type'] ?? 'text' ?>"
                    name="<?php echo $field['key'] ?>"
                    value="1" <?php if (UserFormController::getFieldValue($field['key'])==1) echo "checked"; ?>/>
                <?php else: ?>
                    <input
                    type="<?php echo $field['type'] ?? 'text' ?>"
                    name="<?php echo $field['key'] ?>"
                    value="<?php echo UserFormController::getFieldValue($field['key']) ?>" />
                <?php endif?>
                <?php
                if (is_array($errors) && !empty($errors) && array_key_exists($field['key'], $errors) && !empty($errors[$field['key']])) : ?>
                    <div style="color:red;"> <?php echo $errors[$field['key']] ?></div>
                <?php endif ?>

            </div>
        <?php endforeach; ?>

        <input type="submit" value="Beküldés" />
    </form>
    <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/">Főoldal</a>

</body>

</html>

<?php

?>