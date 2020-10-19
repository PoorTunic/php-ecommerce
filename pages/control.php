<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Control</title>

    <?php require "../php/cdn.php" ?>
</head>
<?php $load = isset($_REQUEST['content']) ? strtolower($_REQUEST['content']) : 'initial-page'?>
<body>
    <div class="container">
        <nav class="navbar navbar-light bg-light">
          <a class="navbar-brand">Bienvenido(a): <?php echo $_SESSION["username"]; ?></a>
          <img class="img img-fluid" src="../img/techstore.png">
        </nav>
        <?php require_once('administrator_menu.php')?>
        <br>
        <?php require_once($load.'.php')?>
    </div>
</body>

</html>
