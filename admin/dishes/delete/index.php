<?php
require_once('../../../helpers/init.php');
require_once(sprintf('%s/%s', MODELS_DIR,'/Food.php'));

session_start();

$food = new Food();
if ($food->delete(['id'=>$_SESSION['dish_delete']]))
{
unset($_SESSION['dish_delete']);

echo 'Az étel törölve lett.';
echo '<script type="text/javascript">
setTimeout(function() {
    window.location.href = "/admin/dishes";
}, 2000); // 10000 ms = 10 másodperc
</script>';
}
else {
    echo 'Az étel törlése nem sikerült.';
echo '<script type="text/javascript">
setTimeout(function() {
    window.location.href = "/admin/dishes";
}, 2000); // 10000 ms = 10 másodperc
</script>';
}
?>