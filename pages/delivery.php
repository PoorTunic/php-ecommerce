<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="icon" href="../favicon.ico">
    <link rel="stylesheet" href="../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../css/style.css">

    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/popper.min.js"></script>

</head>

<?php
include_once "../php/database.php";
?>

<body class="container">

    <?php require "../php/header.php";?>
    <?php
      $conn = connect_db();
      if($_SESSION['logged'] == false){
    ?>
    <?php
    if(!isset($_POST['terminar'])){
    echo "  <div class='modal' tabindex='-1' role='dialog' id='alertModal'>
        <div class='modal-dialog' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title'>Cuadro de información</h5>
              <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar'>
                <span aria-hidden='true'>&times;</span>
              </button>
            </div>
            <div class='modal-body'>
              <p>
                Por favor proporcione sus datos de contacto y envío de sus productos para finalizar su registro.
              </p>
            </div>
            <div class='modal-footer'>
              <button type='reset' class='btn btn-warning' data-dismiss='modal'>De acuerdo</button>
            </div>
          </div>
        </div>
      </div>
      <script>$('#alertModal').modal('show');</script>";
    }
    ?>
    <div class="container col-8 col-sm-8 col-md-6 col-lg-5 col-xl-4 mx-auto border rounded" style="padding-top: 5%;background: #4257B1;">
        <h1 class="text-center" style="color: white">Datos de contacto y entrega</h1>
        <form action="delivery.php" method="post">
          <div class="form-group">
            <label for="cliente" class="col-form-label" style="color: white">Nombre del cliente</label>
            <input type="text" class="form-control" name="cliente" placeholder="Nombre del cliente" required maxlength="255">
          </div>
          <div class="form-group">
            <label for="rfc" class="col-form-label" style="color: white">RFC</label>
            <input type="text" class="form-control" name="rfc" placeholder="Escriba su RFC" required maxlength="18">
          </div>
          <div class="form-group">
            <label for="contacto" class="col-form-label" style="color: white">Contacto</label>
            <input type="text" class="form-control" name="contacto" placeholder="Contacto" required maxlength="60">
          </div>
          <div class="form-group">
            <label for="telefono1" class="col-form-label" style="color: white">Teléfono 1</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">#</div>
              </div>
              <input type="number" class="form-control" name="telefono1" placeholder="Teléfono de contacto 1" required maxlength="20" minlength="10">
            </div>
          </div>
          <div class="form-group">
            <label for="correo1" class="col-form-label" style="color: white">Correo 1</label>
            <input type="email" readonly value="<?= $_SESSION['correounr'] ?>" class="form-control" name="correo1" placeholder="Correo de contacto 1" required maxlength="60">
          </div>
          <div class="form-group">
            <label for="telefono2" class="col-form-label" style="color: white">Teléfono 2</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">#</div>
              </div>
              <input type="number" class="form-control" name="telefono2" placeholder="Teléfono de contacto 2" required maxlength="20" minlength="10">
            </div>
          </div>
          <div class="form-group">
            <label for="correo2" class="col-form-label" style="color: white">Correo 2</label>
            <input type="email" class="form-control" name="correo2" placeholder="Correo de contacto 2" required maxlength="60">
          </div>
          <div class="form-group">
            <label for="estado" class="col-form-label" style="color: white">Estado</label>
            <select name="estado" class="form-control">
              <option selected disabled>Elegir...</option>
              <?php
                $cons = "SELECT estado FROM t_estados";
                $res = mysqli_query($conn, $cons);
                while ($row=mysqli_fetch_assoc($res)) {
                  echo '<option>'.$row['estado'].'</option>';
                }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="municipio" class="col-form-label" style="color: white">Municipio</label>
            <input type="text" class="form-control" name="municipio" placeholder="Municipio" required maxlength="60">
          </div>
          <div class="form-group">
            <label for="colonia" class="col-form-label" style="color: white">Colonia</label>
            <input type="text" class="form-control" name="colonia" placeholder="Colonia" required maxlength="60">
          </div>
          <div class="form-group">
            <label for="cp" class="col-form-label" style="color: white">Código Postal</label>
            <input type="number" step="1" class="form-control" name="cp" placeholder="Código Postal" required minlength="5" maxlength="5">
          </div>
          <div class="form-group">
            <label for="calle" class="col-form-label" style="color: white">Calle</label>
            <input type="text" class="form-control" name="calle" placeholder="Calle" required maxlength="40">
          </div>
          <div class="form-group">
            <label for="noext" class="col-form-label" style="color: white">No. Ext</label>
            <input type="number" step="1" class="form-control" name="noext" placeholder="Número exterior" required maxlength="5">
          </div>
          <div class="form-group">
            <label for="noint" class="col-form-label" style="color: white">No. Int</label>
            <input type="number" step="1" class="form-control" name="noint" placeholder="Número interior" required maxlength="5">
          </div>
          <div class="jumbotron text-center justify-content-center" style="background: #586FD4;">
            <button name="terminar" type="submit" class="btn btn-light col-4 col-sm-4 col-md-5 col-lg-5 col-xl-5" style="font-size: 14px;">Terminar registro</button>
          </div>
        </form>
      </div>
      <?php
        } else {
            echo '<script>location.href = "../index.php"</script>';
        }
      ?>
    <?php require "../php/footer.php" ?>
</body>

</html>

<?php
  if(isset($_POST['terminar'])){
    $conn = connect_db();
    $cliente = $_POST['cliente'];
    $rfc = $_POST['rfc'];
    $contacto = $_POST['contacto'];
    $telefono1 = $_POST['telefono1'];
    $correo1 = $_POST['correo1'];
    $telefono2 = $_POST['telefono2'];
    $correo2 = $_POST['correo2'];
    $estado = $_POST['estado'];
    $municipio = $_POST['municipio'];
    $colonia = $_POST['colonia'];
    $codpos = $_POST['cp'];
    $calle = $_POST['calle'];
    $noext = $_POST['noext'];
    $noint = $_POST['noint'];
    $abort = false;

    $existemunicipio = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as contadormun FROM t_municipios WHERE municipio = '$municipio'"))["contadormun"];
    $existecolonia = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as contadorcol FROM t_colonias WHERE colonia = '$colonia'"))["contadorcol"];
    $existecodpos = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as contadorcp FROM t_codpos WHERE codpos = '$codpos'"))["contadorcp"];
    $existemunenest = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as existe FROM t_municipios WHERE municipio = '$municipio' AND id_estado = (SELECT id_estado FROM t_estados WHERE estado = '$estado')"))['existe'];
    $existecolmun = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as existecolmun FROM t_colonias WHERE colonia = '$colonia' AND id_municipio = (SELECT id_municipio FROM t_municipios WHERE municipio = '$municipio' AND id_estado = (SELECT id_estado FROM t_estados WHERE estado = '$estado'))"))['existecolmun'];
    $existecpcol = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as existecpcol FROM t_codpos WHERE id_colonia = (SELECT id_colonia FROM t_colonias WHERE colonia = '$colonia' AND id_municipio = (SELECT id_municipio FROM t_municipios WHERE municipio = '$municipio' AND id_estado = (SELECT id_estado FROM t_estados WHERE estado = '$estado')))"))['existecpcol'];
    $conccpcol = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as conccpcol FROM t_codpos WHERE codpos = '$codpos' AND id_colonia = (SELECT id_colonia FROM t_colonias WHERE colonia = '$colonia' AND id_municipio = (SELECT id_municipio FROM t_municipios WHERE municipio = '$municipio' AND id_estado = (SELECT id_estado FROM t_estados WHERE estado = '$estado')))"))['conccpcol'];

    if(($existecolonia == 0 && $existemunenest != 0) || ($existecolonia != 0 && $existecolmun == 0 && $existemunenest != 0)){
      if($conn->query("INSERT INTO t_colonias VALUES(null, UPPER('$colonia'), (SELECT id_municipio FROM t_municipios WHERE municipio = '$municipio' AND id_estado = (SELECT id_estado FROM t_estados WHERE estado = '$estado')))")){
        echo ($existecolonia > 1? '<script>alert("Existen '.($existecolonia + 1).' colonias en diferentes municipios.");</script>' : '');
      }
    } else if($existemunenest == 0 || $existecolmun == 0){
      $abort = true;
    }

    $info = "";

    if($abort){
      $info = "El municipio seleccionado y el estado no concuerdan. No se completó el registro correctamente.";
      echo "<div class='modal' tabindex='-1' role='dialog' id='myModal'>
                <div class='modal-dialog' role='document'>
                  <div class='modal-content'>
                    <div class='modal-header'>
                      <h5 class='modal-title'>Cuadro de información</h5>
                      <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                    </div>
                    <div class='modal-body'>
                      <p>$info</p>
                    </div>
                    <div class='modal-footer'>
                      <button type='button' class='btn btn-primary' data-dismiss='modal'>De acuerdo</button>
                    </div>
                  </div>
                </div>
              </div>";
          echo "<script>$('#myModal').modal('show');</script>";
    } else {
      if($existecodpos == 0 && $existemunenest != 0 && $existecpcol == 0){
        $conn->query("INSERT INTO t_codpos VALUES(null, UPPER('$codpos'), (SELECT id_colonia FROM t_colonias WHERE colonia = '$colonia' AND id_municipio = (SELECT id_municipio FROM t_municipios WHERE municipio = '$municipio' AND id_estado = (SELECT id_estado FROM t_estados WHERE estado = '$estado'))))");
      } else if($existemunenest == 0){
        $abort = true;
      }

      if($abort){
        $info = "El código postal y la colonia no concuerdan. No se completó el registro.";
        echo "<div class='modal' tabindex='-1' role='dialog' id='myModal'>
                  <div class='modal-dialog' role='document'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h5 class='modal-title'>Cuadro de información</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar'>
                          <span aria-hidden='true'>&times;</span>
                        </button>
                      </div>
                      <div class='modal-body'>
                        <p>$info</p>
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' data-dismiss='modal'>De acuerdo</button>
                      </div>
                    </div>
                  </div>
                </div>";
            echo "<script>$('#myModal').modal('show');</script>";
      } else {
        $inst = "INSERT INTO t_clientes VALUES (null, '$cliente', '$rfc','$contacto', '$telefono1', '$correo1', '$telefono2', '$correo2', (SELECT id_estado FROM t_estados WHERE estado = '$estado'), (SELECT id_municipio FROM t_municipios WHERE municipio = '$municipio' AND id_estado = (SELECT id_estado FROM t_estados WHERE estado = '$estado')), (SELECT id_colonia FROM t_colonias WHERE colonia = '$colonia' and id_municipio = (SELECT id_municipio FROM t_municipios WHERE municipio = '$municipio' AND id_estado = (SELECT id_estado FROM t_estados WHERE estado = '$estado'))),(SELECT id_codpos FROM t_codpos WHERE codpos = '$codpos'), '$calle', '$noext', '$noint')";

        $ins = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as contador FROM t_clientes WHERE rfc = '$rfc'"))["contador"] == 1? "D" : $inst;

        if($ins == "D"){
          $info = "Ya existe un cliente registrado con estas características. No se completó el registro.";
          echo "<div class='modal' tabindex='-1' role='dialog' id='myModal'>
                    <div class='modal-dialog' role='document'>
                      <div class='modal-content'>
                        <div class='modal-header'>
                          <h5 class='modal-title'>Cuadro de información</h5>
                          <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar'>
                            <span aria-hidden='true'>&times;</span>
                          </button>
                        </div>
                        <div class='modal-body'>
                          <p>$info</p>
                        </div>
                        <div class='modal-footer'>
                          <button type='button' class='btn btn-primary' data-dismiss='modal'>De acuerdo</button>
                        </div>
                      </div>
                    </div>
                  </div>";
              echo "<script>$('#myModal').modal('show');</script>";
        } else {
          if($conn->query($ins) === TRUE){
            if($conn->query("UPDATE t_usuarios SET status = 1 WHERE correo = '$correo1'") === TRUE){
              $_SESSION['logged'] = true;
              $info = "Su registro se ha completado. Gracias por su tiempo.";
              echo "<div class='modal' tabindex='-1' role='dialog' id='myModal'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title'>Cuadro de información</h5>
                              <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar' onclick=\"location.href = '../index.php'\">
                                <span aria-hidden='true'>&times;</span>
                              </button>
                            </div>
                            <div class='modal-body'>
                              <p>$info</p>
                            </div>
                            <div class='modal-footer'>
                              <button type='button' class='btn btn-success' data-dismiss='modal' onclick=\"location.href = '../index.php'\">De acuerdo</button>
                            </div>
                          </div>
                        </div>
                      </div>";
                  echo "<script>$('#myModal').modal('show');</script>";
                }
          } else {
              $info = "Error en la inserción de los datos, vuelva a intentarlo.";
              echo "<div class='modal' tabindex='-1' role='dialog' id='myModal'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title'>Cuadro de información</h5>
                              <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar'>
                                <span aria-hidden='true'>&times;</span>
                              </button>
                            </div>
                            <div class='modal-body'>
                              <p>$info</p>
                            </div>
                            <div class='modal-footer'>
                              <button type='button' class='btn btn-primary' data-dismiss='modal'>De acuerdo</button>
                            </div>
                          </div>
                        </div>
                      </div>";
                  echo "<script>$('#myModal').modal('show');</script>";
          }
        }
      }
    }
  }
?>
