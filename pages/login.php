<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../css/style.css">

    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/popper.min.js"></script>
</head>

<body>
    <?php require "../php/header.php" ?>

    <div style="padding-top: 3%">

    </div>
    <div class="container col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto border rounded" style="padding-top: 5%;background: #4257B1;">
        <h1 class="text-center" style="color: white">Ingresar</h1>
        <form action="../php/auth.php" method="post">
            <div class="form-group">
                <label for="username" style="color: white">Usuario</label>
                <input required type="email" class="form-control" name="username" aria-describedby="emailHelp" placeholder="Ingresa tu nombre de usuario">
            </div>
            <div class="form-group">
                <label for="password" style="color: white">Contraseña</label>
                <input required type="password" class="form-control" name="password" placeholder="Ingresa tu contraseña">
            </div>
            <div class="form-group row my-auto d-flex justify-content-between">
                <div class="col-4 d-flex align-items-center">
                    <img class="w-100 h-50" src="../php/captcha.php">
                </div>
                <div class="col-8">
                    <label for="captcha" style="color: white">Captcha</label>
							      <input type="text" class="form-control " aria-describedby="code" placeholder="Ingresa el captcha" name="code" required>
                </div>
            </div>
            <br>
            <div class="jumbotron" style="background: #586FD4;">
                <div class="form-group row my-auto d-flex justify-content-between">
                    <button name="submit" type="submit" class="btn btn-light col-3 col-sm-3 col-md-4 col-lg-4 col-xl-4" style="font-size: 14px;">Ingresar</button>
                    <button name="reset" type="submit" class="btn btn-warning col-8 col-sm-8 col-md-6 col-lg-6 col-xl-6" style="font-size: 14px;">Olvidé mi contraseña</button> </div>
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
    <div class="text-center" style="padding-top: 2%; padding-bottom: 3%;">
        <a href="register.php" style="color: #FF8E23">No tengo cuenta, deseo registrarme</a>
    </div>

    <?php require "../php/footer.php" ?>
</body>

</html>
