<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../css/style.css">

    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/popper.min.js"></script>


</head>
<script>
var validations = 0;
var valLimit = 1;
var passed = false;

function validarPass(){
  var value = $('#password-c').val();

  if(value.length == $('#password').val().length){
    if(value == $('#password').val() && value != ""){
      if(value.match(/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}/g)){
        if(!passed){
          alert("Contraseñas válidas. Puede continuar.");
          validations += 1;
          $('#password-c').attr("readonly", true);
          $('#password').attr("readonly", true);
          passed = true;
          if(validations == valLimit){
            $('#crear').attr("disabled", false);
            validations = 0;
          }
        }
      } else {
        alert("La contraseña no cumple con los requerimientos");
      }
    } else if(value != $('#password').val() && value != ""){
      alert("Las contraseñas no coinciden");
    }
  }
}

$(document).ready(function (){

  $('#password-c').keyup(function(){
    validarPass();
  });

  $('#password').keyup(function(){
    validarPass();
  });

});
</script>
<?php
include_once "../php/database.php";
?>

<body>
    <?php require "../php/header.php"?>


    <div style="padding-top: 3%">

    </div>
    <div class="container col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto border rounded" style="padding-top: 5%;background: #FF8E23;">
        <h1 class="text-center" style="color: white">Crear cuenta</h1>
        <input type="hidden" name="level" value="3">
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="email" style="color: white">Correo electrónico</label>
                <input required type="email" class="form-control" name="email" aria-describedby="emailHelp" placeholder="Ingresa tu correo electrónico">
            </div>
            <div class="form-group">
                <label for="password" style="color: white">Contraseña</label>
                <input required type="password" class="form-control" name="password" id="password" placeholder="Ingresa tu contraseña">
                <small class="text-dark">Requerimientos: Al menos una mayúscula, una minúscula, un número y un caracter de los siguientes: #?!@$%^&*-</small>
            </div>
            <div class="form-group">
                <label for="password-c" style="color: white">Confirma tu contraseña</label>
                <input required type="password" class="form-control" name="password-c" id="password-c" placeholder="Vuelve a ingresar tu contraseña">
            </div>
            <div class="form-group row my-auto d-flex justify-content-between">
                <div class="col-4 d-flex align-items-center">
                    <img class="w-100 h-50" src="../php/captcha-register.php">
                </div>
                <div class="col-8">
                    <label for="captcha" style="color: white">Captcha</label>
							      <input type="text" class="form-control " aria-describedby="code" placeholder="Ingresa el captcha" name="code" required>
                </div>
            </div>
            <br>
            <div class="jumbotron" style="background: #FAA14C;">
                <div class="form-group row my-auto d-flex justify-content-between">
                    <button type="submit" class="btn btn-light col-3 col-sm-3 col-md-4 col-lg-4 col-xl-4" style="font-size: 14px;" name="crear" id="crear" disabled>Crear cuenta</button>
                    <button type="button" class="btn btn-primary col-8 col-sm-8 col-md-6 col-lg-6 col-xl-6" style="font-size: 14px;" onclick="location.href='login.php'">Ya tengo una cuenta</button>
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
        <?php
        if(isset($_POST['crear'])){

          if($_POST['code']==$_SESSION['realcode']){

            $correo = $_POST['email'];
            $password = $_POST['password'];
            $passwordc = $_POST['password-c'];
            if($password == $passwordc){
              $conn = connect_db();
              $qty = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as coin FROM t_usuarios WHERE correo = '$correo' and nevel = 3"))['coin'];
              if($qty >= 1){
                echo '<script>alert("Ya existe un usuario registrado con estas características");location.href = "register.php";</script>';
              } else {
                if($conn->query("INSERT INTO t_usuarios VALUES (null, '$correo', '$password', 3, 0)") === TRUE){
                  $result = login($conn, $correo, $password);
                  if (is_array($result)) {
                      $_SESSION["username"] = $correo;
                      $_SESSION["iduser"] = $result["id_usuario"];
                      $_SESSION["logged"] = true;
                      $_SESSION["level"] = $result["nevel"];
                        echo "  <div class='modal' tabindex='-1' role='dialog' id='myModal'>
                            <div class='modal-dialog' role='document'>
                              <div class='modal-content'>
                                <div class='modal-header'>
                                  <h5 class='modal-title'>Cuadro de información</h5>
                                  <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar' onclick="location.href = 'login.php'">
                                    <span aria-hidden='true'>&times;</span>
                                  </button>
                                </div>
                                <div class='modal-body'>
                                  <p>
                                    Su cuenta ha sido creada. Por favor proporcione sus datos de contacto y de envío de sus productos.
                                  </p>
                                </div>
                                <div class='modal-footer'>
                                  <form action='delivery.php' method='post'>
                                  <input type='hidden' name='correo' value='$correo'>
                                  <button type='submit' name='entrega' class='btn btn-primary' data-dismiss='modal'>Ir</button>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                          <script>$('#myModal').modal('show');</script>";
                  } else {
                    echo '<script>location.href = "register.php?err=Usuario/Contraseña incorrectos";</script>';
                  }
                }
              }
            } else {
              echo '<script>location.href = "register.php?err=Las contraseñas no coinciden";</script>';
            }
          } else {
            echo '<script>location.href = "register.php?err=El captcha no es correcto";</script>';
          }
        }
      ?>
    </div>
    <?php require "../php/footer.php" ?>
</body>

</html>
