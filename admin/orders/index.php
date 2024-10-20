<?php

require_once('../../helpers/init.php');
require_once(sprintf('%s/%s', HELPERS_DIR, 'helpers.php')); 
require_once(sprintf('%s/%s', MODELS_DIR, 'Order.php')); 

session_start();

echo '<script>
    function confirmDelete() {
        if (!confirm("Biztosan törölni akarja a cikket?")) {
            event.preventDefault();
        }
    }
</script>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendelések</title>
</head>

<body>

    <?php

    $o = new Order();
    $userId = $_SESSION['logged_in']['id'];
    $orders = $o->read_two_table(['orders.id', 'name', 'doses', 'delivery_home', 'delivery_time', 'orders.created_at', 'user_id'], "", "order by orders.created_at desc", "foods", ['food_id', 'foods.id']);
    ?>
    <h2>Rendelések</h2>
    <form method="post" action="../../helpers/orderHandler.php" style="display:grid; gap:50px; grid-template-columns: auto 100px 100px;justify-content: center; padding:25px;">
        <?php foreach ($orders as $order) : ?>
            <div style="display:grid; gap:50px; grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr; border: 1px solid black; padding: 5px; margin: 5px;">
                <span style="font-weight: bold;">Megrendelő azonosítója: <?php echo $order['user_id'] ?></span>
                <span>Rögzítve: <?php echo $order['created_at'] ?></span>
                <span style="font-weight: bold;">Étel: <?php echo $order['name'] ?></span>
                <span>Adag: <br> <?php echo $order['doses'] ?></span>
                <span>Házhozszállítás: <br> <?php echo $order['delivery_home'] ? "Kért." : "Nem kért." ?></span>
                <span>Kiszállítás ideje: <?php echo $order['delivery_time'] ?></span>
            </div>
            <div style="padding: 5px; margin: 5px;"> 
                <button type="submit" name="update" value="<?php echo $order['id']?>" style="background-color: aqua;padding: 15px; border: none; border-radius: 3px;"> Módosítás</button>
            </div>
            <div style="padding: 5px; margin: 5px;">
                <button type="submit" name="delete" value="<?php echo $order['id']?>" onclick="confirmDelete();" style="background-color: red;padding: 15px; border:none; border-radius: 3px;"> Törlés</button>
            </div>
        <?php endforeach; ?>

        </form>
        <div style="flex: 1 0 auto; margin:25px;">
            <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/">Főoldal</a>
            <a style="color:white; background-color: red; padding: 2px 5px; margin: 5px; border-radius: 3px;" href="/logout.php">Kijelentkezés</a>
        </div>
</body>

</html>