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

<body>
    <?php

    ?>
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

        <div class="row">
            <div class="col-sm-12 col-md-4 mx-auto">
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group-vertical" role="group" aria-label="First group">
                        <button type="button" class="btn btn-primary btn-block p-2 bg-dark text-white fa fa-home" style="max-width:100%"> Inicio</button>
                        <button type="button" class="btn btn-primary btn-block p-2 bg-dark text-white fa fa-user" style="max-width:100%"> Administradores</button>
                        <button type="button" class="btn btn-primary btn-block p-2 bg-dark text-white fa fa-user-o" style="max-width:100%"> Clientes</button>
                        <button type="button" class="btn btn-primary btn-block p-2 bg-dark text-white fa fa-bars" style="max-width:100%"> Categorías</button>
                        <button type="button" class="btn btn-primary btn-block p-2 bg-dark text-white fa fa-users" style="max-width:100%"> Proveedores</button>
                        <button type="button" class="btn btn-primary btn-block p-2 bg-dark text-white fa fa-laptop" style="max-width:100%"> Productos</button>
                        <button type="button" class="btn btn-primary btn-block p-2 bg-dark text-white fa fa-shopping-cart" style="max-width:100%"> Compras</button>
                        <button type="button" class="btn btn-primary btn-block p-2 bg-dark text-white fa fa-arrow-right" style="max-width:100%"> Slider</button>
                        <button type="button" class="btn btn-primary btn-block p-2 bg-dark text-white fa fa-sign-out" style="max-width:100%"> Salir</button>
                    </div>

                </div>
            </div>
            <div class="col-sm-12 col-md-8 mx-auto">
                <br><br>
                <h1 class="display-4">BIENVENIDO AL SISTEMA</h1>
            </div>
        </div>
    </div>
</body>

</html>