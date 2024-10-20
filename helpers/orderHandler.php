<?php

require_once('init.php');
require_once(sprintf('%s/%s', MODELS_DIR,'/Order.php'));

session_start();


if (isset($_POST['update']) && $_SESSION['logged_in']['role'] === 'admin') {
    $_SESSION['order_update'] = $_POST['update'];
    header("Location: /admin/orders/update");
    exit();
}
else if (isset($_POST['delete']) && $_SESSION['logged_in']['role'] === 'admin') {
    $_SESSION['order_delete'] = $_POST['delete'];
    header("Location: /admin/orders/delete");
    exit();
}
else if (isset($_POST['details'])) {
    header("Location: /admin/orders/cart");
    exit();
}
else {
    header("Location: /admin/orders");
    exit();
}

?>