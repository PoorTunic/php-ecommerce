<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información</title>
    <link rel="icon" href="../favicon.ico">
    <link rel="stylesheet" href="../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../css/style.css" >

    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/popper.min.js"></script>
</head>

<body class="container">
    <?php require "../php/header.php" ?>

    <div style="padding-top: 3%">

    </div>
    <?php
      require "../php/database.php";

      require "../phpmailer/src/PHPMailer.php";
      require "../phpmailer/src/SMTP.php";
      require "../phpmailer/src/Exception.php";

      use PHPMailer\PHPMailer\PHPMailer;
      use PHPMailer\PHPMailer\SMTP;
      use PHPMailer\PHPMailer\Exception;

      $mail = new PHPMailer(true);
      $conn = connect_db();

      if(isset($_POST['confirmar'])){
        $correo = $_POST['username'];
        $coin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as cou FROM t_usuarios WHERE correo = '$correo'"))["cou"];
        if($coin == 1){
          try {
            $_SESSION['resetcode'] = rand(1000000,9999999);
            $_SESSION['username'] = $_POST['username'];
            $addressee = $_SESSION['username'];
            $subject = "Restauración de contraseña";
            $content = "Su clave es ".$_SESSION['resetcode'].". Gracias por su cooperación. Ingrese este código en el apartado correspondiente.";

            $mail->SMTPDebug = 0;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'ecommerce.techstore.contact@gmail.com';                     // SMTP username
            $mail->Password   = 'patitodehule21';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('ecommerce.techstore.contact@gmail.com');
            $mail->addAddress('2518160004lvalenciam@gmail.com');               // Name is optional
            $mail->addCC($addressee);

            $mail->Subject = utf8_decode($subject);
            $mail->Body    =  $content;

            $mail->send();

          } catch (Exception $e) {
            echo "Mensaje no enviado: ".$e;
          }
        } else {
            echo "<script>alert('No existe esa cuenta de usuario.');location.href = 'restore_password.php';</script>";
        }
    ?>

    <div class="container col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto border rounded" style="padding-top: 5%;background: #4257B1;">
      <div class="alert alert-info" role="alert">
      Se ha enviado un código a <?= $_SESSION['username'] ?>. Por favor escríbalo debajo.
      </div>
        <h1 class="text-center" style="color: white">Ingresar</h1>
        <form action="restore_password.php" method="post">
            <input type="hidden" name="coder" id='coder' value="<?= $_SESSION['resetcode'] ?>">
            <div class="form-group">
                <label for="username" style="color: white">Usuario</label>
                <input readonly value="<?= $_POST['username'] ?>" required type="email" class="form-control" name="username" aria-describedby="emailHelp" placeholder="Ingresa tu nombre de usuario">
            </div>
            <div class="form-group">
                <label for="username" style="color: white">Código</label>
                <input required type="text" class="form-control" name="code" id="code" aria-describedby="emailHelp" placeholder="Ingresa el código recibido">
            </div>
            <br>
            <div class="jumbotron" style="background: #586FD4;">
                <div class="form-group row my-auto d-flex justify-content-center">
                    <button disabled name="verificar" id="verificar" type="submit" class="btn btn-light col-5 col-sm-5 col-md-6 col-lg-6 col-xl-6" style="font-size: 14px;">Continuar</button>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript">
      $('#code').keyup(function(){
        var coder = $('#coder').val();
        var code = $('#code').val();
        if(coder.length == code.length){
          if(coder == code){
            alert('Los códigos coinciden. Puede continuar.');
            $('#verificar').attr('disabled', false);
            $('#code').attr('disabled', true);
          } else {
            alert('Los códigos no coinciden. Vuelva a escribirlo.');
            $('#code').val("");
          }
        }
      });
    </script>
    <?php
      } else if(isset($_POST['verificar'])){
    ?>

    <div class="container col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto border rounded" style="padding-top: 5%;background: #4257B1;">
      <div class="alert alert-info" role="alert">
      Se ha confirmado su identidad, proceda a crear una nueva contraseña.
      </div>
        <h1 class="text-center" style="color: white">Ingresar</h1>
        <form action="restore_password.php" method="post">
            <div class="form-group">
                <label for="username" style="color: white">Nueva contraseña</label>
                <input required type="password" class="form-control" id="contrapass" name="contrapass" aria-describedby="emailHelp" placeholder="Ingresa la nueva contraseña">
            </div>
            <div class="form-group">
                <label for="username" style="color: white">Confirmar nueva contraseña</label>
                <input required type="password" class="form-control" id="contrapass-confirmed" name="contrapass-confirmed" aria-describedby="emailHelp" placeholder="Vuelva a escribir la nueva contraseña">
            </div>
            <br>
            <div class="jumbotron" style="background: #586FD4;">
                <div class="form-group row my-auto d-flex justify-content-center">
                    <button disabled name="restaurar" id="restaurar" type="submit" class="btn btn-light col-5 col-sm-5 col-md-6 col-lg-6 col-xl-6" style="font-size: 14px;">Restaurar</button>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript">
      let passed = false;
      function validarPass(){
        var value = $('#contrapass-confirmed').val();

        if(value.length == $('#contrapass').val().length){
          if(value == $('#contrapass').val() && value != ""){
            if(value.match(/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}/g)){
              if(!passed){
                alert("Contraseñas válidas. Puede continuar.");
                $('#contrapass-confirmed').attr("readonly", true);
                $('#contrapass').attr("readonly", true);
                passed = true;
                $('#restaurar').attr("disabled", false);
              }
            } else {
              alert("La contraseña no cumple con los requerimientos");
            }
          } else if(value != $('#contrapass').val() && value != ""){
            alert("Las contraseñas no coinciden");
          }
        }
      }

      $('#contrapass-confirmed').keyup(function(){
        validarPass();
      });

      $('#contrapass').keyup(function(){
        validarPass();
      });
    </script>
  <?php
    } else if(isset($_POST['restaurar'])){
      $contrapass = $_POST['contrapass'];
      $correo = $_SESSION['username'];
      $upd = "UPDATE t_usuarios SET contrapass = '$contrapass' WHERE correo = '$correo'";
      if($conn->query($upd)===TRUE){
        echo "<script>alert('Contraseña reestablecida.');</script>";
      }
  ?>
  <script type="text/javascript">
    location.href= 'login.php';
  </script>
  <?php } else { ?>
    <div class="container col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto border rounded" style="padding-top: 5%;background: #4257B1;">
        <h1 class="text-center" style="color: white">Restaurar contraseña</h1>
        <form action="restore_password.php" method="post">
            <div class="form-group">
                <label for="username" style="color: white">Usuario</label>
                <input required type="email" class="form-control" name="username" aria-describedby="emailHelp" placeholder="Ingresa tu nombre de usuario">
            </div>

            <br>
            <div class="jumbotron" style="background: #586FD4;">
                <div class="form-group row my-auto d-flex justify-content-center">
                    <button name="confirmar" type="submit" class="btn btn-light col-5 col-sm-5 col-md-6 col-lg-6 col-xl-6" style="font-size: 14px;">Enviar</button>
                </div>
            </div>
        </form>
    </div>
    <div class="text-center" style="padding-top: 2%; padding-bottom: 3%;">
        <a href="register.php" style="color: #FF8E23">Cancelar</a>
    </div>

  <?php } ?>

    <?php require "../php/footer.php" ?>
</body>
</html>
