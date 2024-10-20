<?php

require_once('../helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR, 'helpers.php')); //relavív útvonal
require_once(sprintf('%s/%s', MODELS_DIR, 'Food.php')); //relavív útvonal
require_once(sprintf('%s/%s', HELPERS_DIR, 'FoodController.php')); //relavív útvonal

//$foodController = new FoodController();
FormController::setForm('food');
$definitions = FormController::getDefinition();


echo '<select name="a" id="">
    <option  name="a" value="">Alma</option>
</select>';
?>
<?php foreach ($definitions as $field): ?>
    <div style="display:flex; flex-direction: column;">
        <label> <?php echo $field['label'] ?> </label>
        <?php if (array_key_exists('type', $field) && $field['type'] === 'textarea') : ?>
            <textarea name="<?php echo $field['key'] ?>" rows="10" cols="50">
                <?php echo FormController::getFieldValue($field['key']) ?>
                </textarea>       
        <?php else : ?>
            <input type="<?php echo $field['type'] ?? 'text' ?>" name="<?php echo $field['key'] ?>"
                value="<?php echo FormController::getFieldValue($field['key']) ?>" />
        <?php endif; ?>
        <?php
        if (is_array($errors) && !empty($errors) && array_key_exists($field['key'], $errors) && !empty($errors[$field['key']])) : ?>
            <div style="color:red;"> <?php echo $errors[$field['key']] ?></div>
        <?php endif ?>

    </div>
<?php endforeach; ?>