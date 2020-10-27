<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css" >

    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/popper.min.js"></script>
</head>

<?php
include_once "../php/database.php";
?>

<body>
    <?php require "../php/header.php" ?>

    <div style="padding-top: 3%">

    </div>
    <div class="container col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto border rounded" style="padding-top: 5%;background: #FF8E23;">
        <h1 class="text-center" style="color: white">Crear cuenta</h1>
        <input type="hidden" name="level" value="3">
        <form action="" method="">
            <div class="form-group">
                <label for="email" style="color: white">Correo electrónico</label>
                <input required type="email" class="form-control" name="email" aria-describedby="emailHelp" placeholder="Ingresa tu correo electrónico">
            </div>
            <div class="form-group">
                <label for="password" style="color: white">Contraseña</label>
                <input required type="password" class="form-control" name="password" placeholder="Ingresa tu contraseña">
            </div>
            <div class="form-group">
                <label for="password-confirmed" style="color: white">Confirma tu contraseña</label>
                <input required type="password-confirmed" class="form-control" name="password" placeholder="Vuelve a ingresar tu contraseña">
            </div>
            <div class="jumbotron" style="background: #FAA14C;">
                <div class="form-group row my-auto d-flex justify-content-between">
                    <button name="submit" type="submit" class="btn btn-light col-3 col-sm-3 col-md-4 col-lg-4 col-xl-4" style="font-size: 14px;">Crear cuenta</button>
                    <button name="reset" type="submit" class="btn btn-primary col-8 col-sm-8 col-md-6 col-lg-6 col-xl-6" style="font-size: 14px;" onclick="location.href='login.php'">Ya tengo una cuenta</button>
                </div>
            </div>
            <div class="error">
                <?php
                if (isset($_GET["err"])) {
                    echo "<p class='danger'>" . $_GET['err'] . "</p>";
                }
                ?>
            </div>
        </form>
    </div>
    <?php require "../php/footer.php" ?>
</body>

</html>
