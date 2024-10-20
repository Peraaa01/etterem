<?php
require_once('../../helpers/init.php');
require_once(sprintf('%s/%s', MODELS_DIR,'/Order.php'));

session_start();

$o = new Order();
$o->delete(['id'=>$_SESSION['order_delete']]);
unset($_SESSION['order_delete']);

echo 'A megrendelés törölve lett.';
echo '<script type="text/javascript">
setTimeout(function() {
    window.location.href = "/orders";
}, 2000); // 10000 ms = 10 másodperc
</script>';
?>