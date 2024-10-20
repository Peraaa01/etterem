<?php
require('../../helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR, 'UserFormController.php')); 



session_start();
$errors = [];
$user = new User();
FormController::setForm('userUpdate');
$definitions = FormController::getDefinition();

if (!empty($_POST)) {
    $errors = UserFormController::update(array_merge($_POST, $_FILES), $definitions);
}

if (empty($_SESSION['logged_in'])) {
    redirect('/login');
}

$keyMap = ['id' => 'Azonosító', 'email' => 'E-mail cím', 'created_at' => 'Létrehozási idő'];
$userId = $_SESSION['logged_in']['id'];
$loggedUser = $user->filterFillables($_SESSION['logged_in']);
?>
<!DOCTYPE html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body style="display: flex; flex-wrap: wrap; gap: 15px; max-width: 800px;">
        <form method="POST" enctype="multipart/form-data">
            <?php foreach($definitions as $definition) :
                if (!array_key_exists($definition['key'], $loggedUser) && $definition['force_show'] !== true) {
                    continue;
                }
            ?>
                <div style="display: flex; flex-direction: column;">
                    <label><?php echo $definition['label']; ?></label>
                    <input
                        type="<?php echo $definition['type'] ?? 'text'; ?>"
                        name="<?php echo $definition['key']; ?>" 
                        value="<?php echo $loggedUser[$definition['key']] ?? ''; ?>"
                    />
                    <?php if (is_array($errors) && array_key_exists($definition['key'], $errors) && !empty($errors[$definition['key']])): ?>
                        <div style="color: red;"><?php echo $errors[$definition['key']]; ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <img src='<?php echo $user->getProfileImage($userId); ?>' />
            <input type='file' name='profile_img' value='<?php echo $loggedUser['profile_img']; ?>' />
            <input type='hidden' name='id' value='<?php echo $userId; ?>' />

            <?php if (is_array($errors) && array_key_exists('form_errors', $errors)): ?>
                <div style="color: red;"><?php echo $errors['form_errors']; ?></div>
            <?php endif; ?>

            <div>
                <input type="submit" value="Módosítás" />
                <a style="background-color: red; color: white; padding: 2px 5px; border-radius:3px;" href="/">Vissza a főoldalra</a>
            </div>
        </form>
    </body>
</html>
