<?php
require_once('./helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR, 'helpers.php')); //relavív útvonal

session_start();
unset($_SESSION["logged_in"]);
session_unset();
header('Location: /');
exit;

?>

