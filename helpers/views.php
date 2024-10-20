<?php

require_once 'init.php'; 
require_once(sprintf('%s/%s', HELPERS_DIR, 'DishFormController.php'));
require_once(sprintf('%s/%s', HELPERS_DIR, 'CartFormController.php'));

function orderView(array $definitions, array $foods, array $errors)
{
    DishFormController::setForm('order');
?>
    <script>
        function showDeliveryTime() {
            var deliveryHome = document.getElementsByName('delivery_home')[0];
            var deliveryTime = document.getElementsByName('delivery_time')[0];
            if (deliveryHome.checked) {
                deliveryTime.disabled=false;
            } else {
                deliveryTime.disabled= true;
            }
        }

        document.addEventListener('DOMContentLoaded', 
        function() {
            var deliveryHome = document.getElementsByName('delivery_home')[0];
            var deliveryTime = document.getElementsByName('delivery_time')[0];
            if (deliveryHome.checked) {
                deliveryTime.disabled=false;
            } else {
                deliveryTime.disabled= true;
            }
        });


    </script>

    <?php 
    foreach ($definitions as $field): ?>
        <div style="display:flex; flex-direction: column;">
            <?php if ($field['key'] === 'delivery_home') : ?> <!-- div-be kell tenni, hogy egymás mellé kerüljön  -->
                <div>
                    <input type="<?php echo $field['type'] ?? 'text' ?>" onclick="showDeliveryTime();"  id="<?php echo $field['key'] ?>"  name="<?php echo $field['key'] ?>" value="1" <?php if (DishFormController::getFieldValue($field['key'])==1) echo "checked"; ?>  />
                    <label for="<?php echo $field['key'] ?>" > <?php echo $field['label'] ?> </label>
                </div>

            <?php else : ?>
                <label> <?php echo $field['label'] ?> </label>
                <?php if (array_key_exists('type', $field) && $field['type'] == 'textarea') : ?>
                    <textarea name="<?php echo $field['key'] ?>" rows="10" cols="50">
                        <?php echo DishFormController::getFieldValue($field['key']) ?>
                    </textarea>

                <?php elseif ($field['key'] === 'doses') : ?>
                    <input type="<?php echo $field['type'] ?? 'text' ?>" name="<?php echo $field['key'] ?>" min="<?php echo $field['min']?>" value="<?php echo FormController::getFieldValue($field['key'])==""?0: FormController::getFieldValue($field['key'])?>" />

                <?php elseif (array_key_exists('type', $field) && $field['type'] == 'select') : ?>
                    <select name="<?php echo $field['key'] ?>" value="<?php echo 
                    DishFormController::getFieldValue($field['key']) ?>">
                        <?php foreach ($foods as $food) : ?>
                            <option <?php if ($food['id']==DishFormController::getFieldValue($field['key'])) echo "selected"; ?> value="<?php echo $food['id'] ?>"> <?php echo $food['name']. " - ".$food['price']. " Ft" ?> </option>
                        <?php endforeach; ?>
                    </select>

                <?php elseif ($field['key'] === 'delivery_time') : ?>
                    <input type="<?php echo $field['type'] ?? 'text' ?>" name="<?php echo $field['key'] ?>"
                    value="<?php echo 
                    DishFormController::getFieldValue($field['key']) ?>"  onclick="showDeliveryTime();" />
    
                <?php else : ?>
                    <input type="<?php echo $field['type'] ?? 'text' ?>" name="<?php echo $field['key'] ?>"
                        value="<?php echo 
                        DishFormController::getFieldValue($field['key']) ?>" />
                <?php endif; ?>
            <?php endif ?>
            <?php if (is_array($errors) && !empty($errors) && array_key_exists($field['key'], $errors) && !empty($errors[$field['key']])) : ?>
                    <div style="color:red;"> <?php echo $errors[$field['key']] ?></div>
            <?php endif ?>
        </div>
    <?php endforeach; ?>
    
<?php } ?>


<?php
function cartView(array $definitions)
{
    CartFormController::initialize();
?>

    <?php 
    foreach ($definitions as $field): ?>
        <div style="display:flex; flex-direction: column;">
            <?php if ($field['key'] === 'delivery_home') : ?> <!-- div-be kell tenni, hogy egymás mellé kerüljön  -->
                <div>
                    <input disabled type="<?php echo $field['type'] ?? 'text' ?>"  id="<?php echo $field['key'] ?>"  name="<?php echo $field['key'] ?>" value="1" <?php if (CartFormController::getFieldValue($field['key'])==1) echo "checked"; ?> />
                    <label for="<?php echo $field['key'] ?>" > <?php echo $field['label'] ?> </label>
                </div>

            <?php else : ?>
                <label> <?php echo $field['label'] ?> </label>
                <?php if (array_key_exists('type', $field) && $field['type'] == 'textarea') : ?>
                    <textarea disabled name="<?php echo $field['key'] ?>" rows="10" cols="50"><?php echo CartFormController::getFieldValue($field['key']) ?>
                    </textarea>
                <?php elseif ($field['key'] === 'image_path') : ?>
                    <img src="<?php echo '/public/foods/images/'.CartFormController::getFieldValue($field['key']) ?>" style="width: 50%; float:right;"/>

                <?php elseif ($field['key'] === 'delivery_time') : ?>
                    <input disabled  type="<?php echo $field['type'] ?? 'text' ?>" name="<?php echo $field['key'] ?>"
                    value="<?php echo CartFormController::getFieldValue($field['key']) ?>"  onclick="showDeliveryTime();" />
    
                <?php else : ?>
                    <input disabled type="<?php echo $field['type'] ?? 'text' ?>" name="<?php echo $field['key'] ?>"
                        value="<?php echo CartFormController::getFieldValue($field['key']) ?>" />
                <?php endif; ?>
            <?php endif ?>

        </div>
    <?php endforeach; ?>
    
<?php } ?>

<?php
function dishView(array $definitions, array $foodtypes, array $errors)
{
    DishFormController::setForm('dish');
   ?>
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
    
<?php } ?>
