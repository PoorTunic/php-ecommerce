<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar</title>

    <?php require "../php/cdn.php" ?>
</head>

<?php
if (isset($_POST["submit"])) {
    
}
?>

<body>
    <?php require "../php/header.php" ?>

    <div style="padding-top: 3%">
        
    </div>
    <div class="container col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto border rounded" style="padding-top: 5%;background: #4257B1;">
        <h1 class="text-center" style="color: white">Ingresar</h1>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username" style="color: white">Usuario</label>
                <input type="user" class="form-control" name="username" aria-describedby="emailHelp" placeholder="Ingresa tu nombre de usuario">
            </div>
            <div class="form-group">
                <label for="password" style="color: white">Contraseña</label>
                <input type="password" class="form-control" name="password" placeholder="Ingresa tu contraseña">
            </div>
            <div class="jumbotron" style="background: #586FD4;">
                <div class="form-group row my-auto d-flex justify-content-between">
                    <button type="submit" class="btn btn-light col-3 col-sm-3 col-md-4 col-lg-4 col-xl-4" style="font-size: 14px;">Ingresar</button>
                    <button type="submit" class="btn btn-warning col-8 col-sm-8 col-md-6 col-lg-6 col-xl-6" style="font-size: 14px;">Olvidé mi contraseña</button>
                </div>
            </div>
        </form>
    </div>

    <?php require "../php/footer.php" ?>
</body>

</html>