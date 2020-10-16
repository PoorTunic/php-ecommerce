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
<?php $load = isset($_GET['content']) ? strtolower($_GET['content']) : 'initial-page'?>
<body>

    <div class="container">
        <nav class="text-white navbar navbar-expand navbar-toggler" style="background-color: black; color: white; width: 100%">
            <div class="collapse navbar-collapse d-flex flex-row-reverse" id="navbarNav">
                <div>
                    <ul class="navbar-nav">
                        <li class="nav-item" class="col-sm-1 col-md-2" style="text-align: right;">
                            <h3><?php echo $_SESSION["username"]; ?></h3>
                        </li>
                        <li class="nav-item" class="col-sm-1 col-md-2" style="margin-left: 10px">
                            <form action="../php/logout.php" method="get">
                                <input type="submit" name="logout" value="SALIR" class="btn btn-dark">
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php require_once('administrator_menu.php')?>
        <?php require_once($load.'.php')?>
    </div>
</body>

</html>
