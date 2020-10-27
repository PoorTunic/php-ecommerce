<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administración del sitio - TechStore</title>

    <link rel="stylesheet" href="../../css/bootstrap.min.css" >

    <script src="../../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/popper.min.js"></script>
</head>
<?php
  $load = isset($_REQUEST['content']) ? strtolower($_REQUEST['content']) : 'initial-page';

  if (!ini_set('default_charset', 'utf-8')) {
    echo "could not set default_charset to utf-8<br>";
  }
?>
<body>
    <div class="container">
        <nav class="navbar navbar-light bg-light">
          <a class="navbar-brand">Bienvenido(a): <?php echo $_SESSION["username"]; ?></a>
          <img class="img img-fluid" src="../../img/techstore.png">
        </nav>
        <?php require_once('administrator_menu.php')?>
        <br>
        <?php require_once($load.'.php')?>
    </div>
</body>

</html>
