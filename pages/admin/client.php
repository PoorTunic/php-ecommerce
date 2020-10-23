<script type="text/javascript">

  function goToPart(np){
    location.href = "control.php?content=client&part=" + np;
  }

  function openDeleteConfirmation(id){
    $('#delModal' + id).modal('show');
  }

  $(document).ready(function (){
    $('#exampleModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.data('whatever') // Extract info from data-* attributes
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      modal.find('.modal-title').text('Nuevo ' + recipient)
      });
  });
</script>

<?php
  include "../../php/database.php";
  $conn = connect_db();

  $paginas = 5;
  $sinres = FALSE;
  $sqlquery = "";
  $qryrows = "";
  $dato = "";
  if (isset($_REQUEST['buscarDato'])){
    $dato = $_REQUEST['data'];
    if(is_numeric($dato)){
      $sqlquery = "SELECT * FROM t_clientes INNER JOIN t_estados ON t_clientes.id_estado = t_estados.id_estado INNER JOIN t_municipios ON t_clientes.id_municipio = t_municipios.id_municipio INNER JOIN t_colonias ON t_clientes.id_colonia = t_colonias.id_colonia INNER JOIN t_codpos ON t_clientes.id_codpos = t_codpos.id_codpos WHERE rfc like '%$dato%' or telefono1 like '%$dato%' or correo1 like '%$dato%' or telefono2 like '%$dato%' or correo2 like '%$dato%' or codpos like '%$dato%' or noext like '%$dato%' or noint like '%$dato%'";
      $qryrows = "SELECT count(*) as conteo FROM t_clientes INNER JOIN t_estados ON t_clientes.id_estado = t_estados.id_estado INNER JOIN t_municipios ON t_clientes.id_municipio = t_municipios.id_municipio INNER JOIN t_colonias ON t_clientes.id_colonia = t_colonias.id_colonia INNER JOIN t_codpos ON t_clientes.id_codpos = t_codpos.id_codpos WHERE rfc like '%$dato%' or telefono1 like '%$dato%' or correo1 like '%$dato%' or telefono2 like '%$dato%' or correo2 like '%$dato%' or codpos like '%$dato%' or noext like '%$dato%' or noint like '%$dato%'";
    } else {
      $sqlquery = "SELECT * FROM t_clientes INNER JOIN t_estados ON t_clientes.id_estado = t_estados.id_estado INNER JOIN t_municipios ON t_clientes.id_municipio = t_municipios.id_municipio INNER JOIN t_colonias ON t_clientes.id_colonia = t_colonias.id_colonia INNER JOIN t_codpos ON t_clientes.id_codpos = t_codpos.id_codpos WHERE rfc like '%$dato%' or cliente like '%$dato%' or contacto like '%$dato%' or correo1 like '%$dato%' or correo2 like '%$dato%' or estado like '%$dato%' or municipio like '%$dato%' or colonia like '%$dato%' or calle like '%$dato%'";
      $qryrows = "SELECT count(*) as conteo FROM t_clientes INNER JOIN t_estados ON t_clientes.id_estado = t_estados.id_estado INNER JOIN t_municipios ON t_clientes.id_municipio = t_municipios.id_municipio INNER JOIN t_colonias ON t_clientes.id_colonia = t_colonias.id_colonia INNER JOIN t_codpos ON t_clientes.id_codpos = t_codpos.id_codpos WHERE rfc like '%$dato%' or cliente like '%$dato%' or contacto like '%$dato%' or correo1 like '%$dato%' or correo2 like '%$dato%' or estado like '%$dato%' or municipio like '%$dato%' or colonia like '%$dato%' or calle like '%$dato%'";
    }
  } else {
    $sqlquery = "SELECT * FROM t_clientes INNER JOIN t_estados ON t_clientes.id_estado = t_estados.id_estado INNER JOIN t_municipios ON t_clientes.id_municipio = t_municipios.id_municipio INNER JOIN t_colonias ON t_clientes.id_colonia = t_colonias.id_colonia INNER JOIN t_codpos ON t_clientes.id_codpos = t_codpos.id_codpos";
    $qryrows = "SELECT count(*) as conteo FROM t_clientes";
  }

  $cantidad = mysqli_fetch_assoc(mysqli_query($conn, $qryrows))["conteo"];

  if($cantidad == 0){
    $sinres = TRUE;
    $np = 1 % $paginas == 0? (1 / $paginas) : (floor(1 / $paginas) + 1);
  } else {
    $np = $cantidad % $paginas == 0? ($cantidad / $paginas) : (floor($cantidad / $paginas) + 1);
  }

  $pag = isset($_GET['part'])? $_GET['part'] : 1;
  if(!is_numeric($pag) || $pag <= 0){
    $pag = 1;
    echo "<script>goToPart($pag)</script>";
  }

  if($pag > $np){
    $pag -= 1;
    if($pag == $np){
      echo "<script>goToPart($pag)</script>";
    } else {
      echo "<script>goToPart($np)</script>";
    }
  }

  $nf = ($paginas * $pag);
  $ni = (($nf - 1) - ($paginas - 1));
?>

<input type='hidden' value="<?php echo $pag?>" id='parte'>
<nav class="navbar navbar-light bg-white">
  <a class="navbar-brand">Clientes</a>
  <button type="button" class="btn btn-outline-primary" onclick="location.href = 'control.php?content=client'">Ver todo</button>
</nav>
<?php
  if(isset($_POST['registrar'])){
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
      $info = "El municipio seleccionado y el estado no concuerdan. No se insertará el proveedor.";
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
        $info = "El código postal y la colonia no concuerdan. No se modificará el proveedor.";
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
          $info = "Ya existe un cliente registrado con estas características. No se agregará el cliente.";
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
              $info = "Cliente agregado. Se actualizará la lista de clientes.";
              echo "<div class='modal' tabindex='-1' role='dialog' id='myModal'>
                        <div class='modal-dialog' role='document'>
                          <div class='modal-content'>
                            <div class='modal-header'>
                              <h5 class='modal-title'>Cuadro de información</h5>
                              <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar' onclick='goToPart($np + 1)'>
                                <span aria-hidden='true'>&times;</span>
                              </button>
                            </div>
                            <div class='modal-body'>
                              <p>$info</p>
                            </div>
                            <div class='modal-footer'>
                              <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='goToPart($np + 1)'>Ver producto agregado</button>
                            </div>
                          </div>
                        </div>
                      </div>";
                  echo "<script>$('#myModal').modal('show');</script>";
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
<nav class="navbar navbar-light bg-light justify-content-between">
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="cliente">Nuevo cliente</button>
  <form class="form-inline" action="control.php?content=client" method="post">
    <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search" id="data" name="data" value="<?php echo $dato != ""? $dato : "" ?>" required>
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" id="buscarDato" name="buscarDato">Buscar</button>
  </form>
</nav>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nuevo cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="control.php?content=client" method="post">
          <div class="form-group">
            <label for="cliente" class="col-form-label">Cliente</label>
            <input type="text" class="form-control" name="cliente" placeholder="Nombre del cliente" required maxlength="255">
          </div>
          <div class="form-group">
            <label for="rfc" class="col-form-label">RFC</label>
            <input type="text" class="form-control" name="rfc" placeholder="Escriba su RFC" required maxlength="18">
          </div>
          <div class="form-group">
            <label for="contacto" class="col-form-label">Contacto</label>
            <input type="text" class="form-control" name="contacto" placeholder="Contacto" required maxlength="60">
          </div>
          <div class="form-group">
            <label for="telefono1" class="col-form-label">Teléfono 1</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">#</div>
              </div>
              <input type="number" class="form-control" name="telefono1" placeholder="Teléfono de contacto 1" required maxlength="20" minlength="10">
            </div>
          </div>
          <div class="form-group">
            <label for="correo1" class="col-form-label">Correo 1</label>
            <input type="email" class="form-control" name="correo1" placeholder="Correo de contacto 1" required maxlength="60">
          </div>
          <div class="form-group">
            <label for="telefono2" class="col-form-label">Teléfono 2</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">#</div>
              </div>
              <input type="number" class="form-control" name="telefono2" placeholder="Teléfono de contacto 2" required maxlength="20" minlength="10">
            </div>
          </div>
          <div class="form-group">
            <label for="correo2" class="col-form-label">Correo 2</label>
            <input type="email" class="form-control" name="correo2" placeholder="Correo de contacto 2" required maxlength="60">
          </div>
          <div class="form-group">
            <label for="estado" class="col-form-label">Estado</label>
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
            <label for="municipio" class="col-form-label">Municipio</label>
            <input type="text" class="form-control" name="municipio" placeholder="Municipio" required maxlength="60">
          </div>
          <div class="form-group">
            <label for="colonia" class="col-form-label">Colonia</label>
            <input type="text" class="form-control" name="colonia" placeholder="Colonia" required maxlength="60">
          </div>
          <div class="form-group">
            <label for="cp" class="col-form-label">Código Postal</label>
            <input type="number" step="1" class="form-control" name="cp" placeholder="Código Postal" required minlength="5" maxlength="5">
          </div>
          <div class="form-group">
            <label for="calle" class="col-form-label">Calle</label>
            <input type="text" class="form-control" name="calle" placeholder="Calle" required maxlength="40">
          </div>
          <div class="form-group">
            <label for="noext" class="col-form-label">No. Ext</label>
            <input type="number" step="1" class="form-control" name="noext" placeholder="Número exterior" required maxlength="5">
          </div>
          <div class="form-group">
            <label for="noint" class="col-form-label">No. Int</label>
            <input type="number" step="1" class="form-control" name="noint" placeholder="Número interior" required maxlength="5">
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" name="registrar" id="registrar">Agregar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="table-responsive">
  <table class="table">
    <caption>Lista de clientes</caption>
    <thead>
      <tr>
        <th scope="col">Cliente</th>
        <th scope="col">RFC</th>
        <th scope="col">Contacto</th>
        <th scope="col">Teléfono 1</th>
        <th scope="col">Correo 1</th>
        <th scope="col">Teléfono 2</th>
        <th scope="col">Correo 2</th>
        <th scope="col">Estado</th>
        <th scope="col">Municipio</th>
        <th scope="col">Colonia</th>
        <th scope="col">Código Postal</th>
        <th scope="col">Calle</th>
        <th scope="col">No. Int</th>
        <th scope="col">No. Ext</th>
        <th scope="col">Editar</th>
        <th scope="col">Eliminar</th>
      </tr>
    </thead>
    <tbody>
      <?php
        if($sinres){
          echo '<tr>
                  <th colspan="16" class="text-center text-danger" >
                    No hay resultados
                  </th>
                </tr>';
        } else {
          $sqlquery = $sqlquery." LIMIT $ni, $paginas";

          $result = mysqli_query($conn, $sqlquery);
          while($row=mysqli_fetch_assoc($result)){
            modalforupdate($row['id_cliente'], $pag);
            modalfordelete($row['id_cliente'], $pag);
            echo '<tr>
                    <td>'.$row['cliente'].'</td>
                    <td>'.$row['rfc'].'</td>
                    <td>'.$row['contacto'].'</td>
                    <td>'.$row['telefono1'].'</td>
                    <td>'.$row['correo1'].'</td>
                    <td>'.$row['telefono2'].'</td>
                    <td>'.$row['correo2'].'</td>
                    <td>'.$row['estado'].'</td>
                    <td>'.$row['municipio'].'</td>
                    <td>'.$row['colonia'].'</td>
                    <td>'.$row['codpos'].'</td>
                    <td>'.$row['calle'].'</td>
                    <td>'.$row['noext'].'</td>
                    <td>'.$row['noint'].'</td>
                    <td><button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updModal'.$row['id_cliente'].'" data-whatever="cliente">Editar</button></td>';
            echo "<td><button type='button' class='btn btn-outline-danger' onclick='openDeleteConfirmation(".$row['id_cliente'].");'>Eliminar</button></td>
                  </tr>
                ";
          }
        }

      ?>
    </tbody>
  </table>
</div>
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
    <li class="page-item <?php echo $pag == 0 || $pag == 1? 'disabled' : ''?>">
      <a class="page-link" href="control.php?content=client&part=<?php echo $pag != 0? ($pag-1).'' : '1'; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Anterior</a>
    </li>
    <?php
      for ($i=1; $i <= $np; $i++) {
        echo "<li class='page-item ".($i!=$pag? "" : "active")."'><a class='page-link' href='".($i!=$pag? "control.php?content=client&part=".$i : "#").($dato != ""? "&buscarDato=true&data=$dato" : "")."'>".$i."</a></li>";
      }
    ?>
    <li class="page-item <?php echo $pag == $np? 'disabled' : ''?>">
      <a class="page-link" href="control.php?content=client&part=<?php echo $pag != $np? ($pag+1).'' : $np.''; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Siguiente</a>
    </li>
  </ul>
</nav>

<?php
  function modalforupdate($id, $part){
    $conn = connect_db();
    $updQry = "SELECT * FROM t_clientes INNER JOIN t_estados ON t_clientes.id_estado = t_estados.id_estado INNER JOIN t_municipios ON t_clientes.id_municipio = t_municipios.id_municipio INNER JOIN t_colonias ON t_clientes.id_colonia = t_colonias.id_colonia INNER JOIN t_codpos ON t_clientes.id_codpos = t_codpos.id_codpos WHERE t_clientes.id_cliente = $id";

    $resultupdqry = mysqli_query($conn, $updQry);
    $options = "";

    while($row=mysqli_fetch_assoc($resultupdqry)){
      echo '<div class="modal fade" id="updModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="updModalLabel'.$id.'" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="updModalLabel'.$id.'">Actualizar cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="control.php?content=client&part='.$part.'" method="post">
                      <input type="hidden" value="'.$row["id_cliente"].'" name="idcliente">
                      <input type="hidden" value="'.$row["rfc"].'" name="rfcori">

                      <div class="form-group">
                        <label for="cliente" class="col-form-label">Cliente</label>
                        <input value="'.$row["cliente"].'" type="text" class="form-control" name="cliente" placeholder="Nombre del cliente" required maxlength="255">
                      </div>
                      <div class="form-group">
                        <label for="rfc" class="col-form-label">RFC</label>
                        <input value="'.$row["rfc"].'" type="text" class="form-control" name="rfc" placeholder="Escriba su RFC" required maxlength="18">
                      </div>
                      <div class="form-group">
                        <label for="contacto" class="col-form-label">Nombre del contacto</label>
                        <input value="'.$row["contacto"].'" type="text" class="form-control" name="contacto" placeholder="Nombre del contacto" required maxlength="60">
                      </div>
                      <div class="form-group">
                        <label for="telefono1" class="col-form-label">Teléfono 1</label>
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                            <div class="input-group-text">#</div>
                          </div>
                          <input value="'.$row["telefono1"].'" type="tel" class="form-control" name="telefono1" placeholder="Teléfono de contacto 1" required maxlength="20" minlength="10">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="correo1" class="col-form-label">Correo 1</label>
                        <input value="'.$row["correo1"].'" type="email" class="form-control" name="correo1" placeholder="Correo de contacto 1" required maxlength="60">
                      </div>
                      <div class="form-group">
                        <label for="telefono2" class="col-form-label">Teléfono 2</label>
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                            <div class="input-group-text">#</div>
                          </div>
                          <input value="'.$row["telefono2"].'" type="tel" class="form-control" name="telefono2" placeholder="Teléfono de contacto 2" required maxlength="20" minlength="10">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="correo2" class="col-form-label">Correo 2</label>
                        <input value="'.$row["correo2"].'" type="email" class="form-control" name="correo2" placeholder="Correo de contacto 2" required maxlength="60">
                      </div>
                      <div class="form-group">
                        <label for="estado" class="col-form-label">Estado</label>
                        <select name="estado" class="form-control">
                        <option selected>'.$row["estado"].'</option>'.$options;
              $consultaopc = mysqli_query($conn, "SELECT estado FROM t_estados");
              while ($edores=mysqli_fetch_assoc($consultaopc)) {
                echo $edores['estado'] != $row["estado"]? '<option>'.$edores['estado'].'</option>' : '';
              }
              echo '</select>
                      </div>
                      <div class="form-group">
                        <label for="municipio" class="col-form-label">Municipio</label>
                        <input value="'.$row["municipio"].'" type="text" class="form-control" name="municipio" placeholder="Municipio" required maxlength="60">
                      </div>
                      <div class="form-group">
                        <label for="colonia" class="col-form-label">Colonia</label>
                        <input value="'.$row["colonia"].'" type="text" class="form-control" name="colonia" placeholder="Colonia" required maxlength="60">
                      </div>
                      <div class="form-group">
                        <label for="cp" class="col-form-label">Código Postal</label>
                        <input value="'.$row["codpos"].'" type="number" step="1" class="form-control" name="cp" placeholder="Código Postal" required minlength="5" maxlength="5">
                      </div>
                      <div class="form-group">
                        <label for="calle" class="col-form-label">Calle</label>
                        <input value="'.$row["calle"].'" type="text" class="form-control" name="calle" placeholder="Calle" required maxlength="40">
                      </div>
                      <div class="form-group">
                        <label for="noext" class="col-form-label">No. Ext</label>
                        <input value="'.$row["noext"].'" type="number" step="1" class="form-control" name="noext" placeholder="Número exterior" required maxlength="5">
                      </div>
                      <div class="form-group">
                        <label for="noint" class="col-form-label">No. Int</label>
                        <input value="'.$row["noint"].'" type="number" step="1" class="form-control" name="noint" placeholder="Número interior" required maxlength="5">
                      </div>
                      <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" name="actualizar" id="actualizar">Actualizar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>';
      echo '<script>
              $("#updModal'.$id.'").on("show.bs.modal", function (event) {
              var button = $(event.relatedTarget) // Button that triggered the modal
              var recipient = button.data("whatever")

              var modal = $(this)
              modal.find(".modal-title").text("Actualizar " + recipient)
              });
           </script>';

    }


  }

  function modalfordelete($id, $pag){
    $info = "¿Está seguro(a) de eliminar este cliente?";
    echo "<div class='modal' tabindex='-1' role='dialog' id='delModal".$id."'>
            <div class='modal-dialog' role='document'>
              <div class='modal-content'>
                <div class='modal-header'>
                  <h5 class='modal-title'>Cuadro de confirmación</h5>
                  <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar'>
                    <span aria-hidden='true'>&times;</span>
                  </button>
                </div>
                <div class='modal-body'>
                  <p>$info</p>
                </div>
                <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>No, cancelar</button>
                  <form action='control.php?content=client&part=$pag' method='post'>
                    <input type='hidden' value='".$id."' name='idcliente'>
                    <button type='submit' class='btn btn-primary' name='eliminar'>Sí, eliminar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>";
  }

  if(isset($_POST['actualizar'])){
    $cliente = $_POST['cliente'];
    $rfc = $_POST['rfc'];
    $rfcori = $_POST['rfcori'];
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
    $id = $_POST['idcliente'];
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
      $info = "El municipio seleccionado y el estado no concuerdan. No se insertará el proveedor.";
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
        $info = "El código postal y la colonia no concuerdan. No se modificará el proveedor.";
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
        $sent = "UPDATE t_clientes SET cliente = '$cliente', rfc = '$rfc', contacto = '$contacto', telefono1 = '$telefono1', correo1 = '$correo1', telefono2 = '$telefono2', correo2 = '$correo2', id_estado = (SELECT id_estado FROM t_estados WHERE estado = '$estado'), id_municipio = (SELECT id_municipio FROM t_municipios WHERE municipio = '$municipio' AND id_estado = (SELECT id_estado FROM t_estados WHERE estado = '$estado')), id_colonia = (SELECT id_colonia FROM t_colonias WHERE colonia = '$colonia' AND id_municipio = (SELECT id_municipio FROM t_municipios WHERE municipio = '$municipio' AND id_estado = (SELECT id_estado FROM t_estados WHERE estado = '$estado'))), id_codpos = (SELECT id_codpos FROM t_codpos WHERE codpos = '$codpos'), calle = '$calle', noext = '$noext', noint = '$noint' WHERE id_cliente = ".$id;

        $upd = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as coin FROM t_clientes WHERE rfc = '$rfc'"))["coin"] == 1? "D" : $sent;

        if($upd == "D"){
          if($rfcori != $rfc){
            $info = "Ya existe un cliente registrado con estas características. No se modificará el cliente.";
          } else{
            if($conn->query($sent) === TRUE){
              $info = "Cliente actualizado. Se actualizará la lista de clientes.";
            } else {
              $info = "Error en la actualización de los datos, vuelva a intentarlo.";
            }
          }
        } else {
          if($conn->query($upd) === TRUE){
            $info = "Cliente actualizado. Se actualizará la lista de clientes.";
          } else {
            $info = "Error en la actualización de los datos, vuelva a intentarlo.";
          }
        }
      }
    }

    echo "<div class='modal' tabindex='-1' role='dialog' id='updModal'>
            <div class='modal-dialog' role='document'>
              <div class='modal-content'>
                <div class='modal-header'>
                  <h5 class='modal-title'>Cuadro de información</h5>
                  <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar' onclick='goToPart(document.getElementById(\"parte\").value);'>
                    <span aria-hidden='true'>&times;</span>
                  </button>
                </div>
                <div class='modal-body'>
                  <p>$info</p>
                </div>
                <div class='modal-footer'>
                  <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='goToPart(document.getElementById(\"parte\").value);'>De acuerdo</button>
                </div>
              </div>
            </div>
          </div>";
    echo "<script>$('#updModal').modal('show');</script>";
}

if(isset($_POST['eliminar'])){
  $iddel = $_POST['idcliente'];
  $info = "El cliente se ha eliminado. La lista de clientes se actualizará.";
  $sql = "DELETE FROM t_clientes WHERE id_cliente = $iddel";
  if($conn->query($sql) === TRUE){
    echo "<div class='modal' tabindex='-1' role='dialog' id='delModal'>
            <div class='modal-dialog' role='document'>
              <div class='modal-content'>
                <div class='modal-header'>
                  <h5 class='modal-title'>Cuadro de información</h5>
                  <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar' onclick='goToPart(document.getElementById(\"parte\").value);'>
                    <span aria-hidden='true'>&times;</span>
                  </button>
                </div>
                <div class='modal-body'>
                  <p>$info</p>
                </div>
                <div class='modal-footer'>
                  <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='goToPart(document.getElementById(\"parte\").value);'>De acuerdo</button>
                </div>
              </div>
            </div>
          </div>";
    echo "<script>$('#delModal').modal('show');</script>";
  }
}
?>
