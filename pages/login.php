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

    <section class="container">
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="inputEmail">Correo</label>
                <input name="email" type="email" class="form-control" id="inputEmail">
            </div>
            <div class="form-group">
                <label for="inputPassword">Contraseña</label>
                <input name="password" type="password" class="form-control" id="inputPassword">
            </div>
            <button name="submit" type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </section>

    <?php require "../php/footer.php" ?>
</body>

</html>