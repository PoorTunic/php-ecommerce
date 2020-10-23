<script type="text/javascript">

  function goToPart(np){
    location.href = "control.php?content=storage&part=" + np;
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
      $sqlquery = "SELECT * FROM t_almacen NATURAL JOIN t_productos WHERE entrada = $dato or salida = $dato or year(fecha) = $dato or month(fecha) = $dato or day(fecha) = $dato";
      $qryrows = "SELECT count(*) as conteo FROM t_almacen NATURAL JOIN t_productos WHERE entrada = $dato or salida = $dato or year(fecha) = $dato or month(fecha) = $dato or day(fecha) = $dato";
    } else {
      $sqlquery = "SELECT * FROM t_almacen NATURAL JOIN t_productos WHERE producto like \"%$dato%\"";
      $qryrows = "SELECT count(*) as conteo FROM t_almacen NATURAL JOIN t_productos WHERE producto like \"%$dato%\"";
    }
  } else {
    $sqlquery = "SELECT * FROM t_almacen NATURAL JOIN t_productos";
    $qryrows = "SELECT count(*) as conteo FROM t_almacen";
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
  <a class="navbar-brand">Almacén</a>
  <button type="button" class="btn btn-outline-primary" onclick="location.href = 'control.php?content=storage'">Ver todo</button>
</nav>
<?php
  if(isset($_POST['registrarSto'])){
    $idpr = $_POST['idpr'];
    $entrada = $_POST['entrada'];
    $salida = $_POST['salida'];
    $fecha = $_POST['fecha'];
    $parteor = $_POST['parteor'];

    $ins = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as contador FROM t_almacen WHERE id_producto = $idpr"))["contador"] == 1? "D" : "INSERT INTO t_almacen VALUES (null, $idpr, $entrada, $salida, '$fecha')";

    $info = "";

    if($ins == "D"){
      $info = "Ya existe un producto registrado con estas características. No se agregará el producto.";
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
                      <button type='button' class='btn btn-primary' data-dismiss='modal' onclick=\"location.href = 'control.php?content=product&part=$parteor'\">De acuerdo</button>
                    </div>
                  </div>
                </div>
              </div>";
          echo "<script>$('#myModal').modal('show');</script>";
    } else {
      if($conn->query($ins) === TRUE){
          $info = "Producto agregado. Se actualizará la lista de productos del almacén.";
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
                          <button type='button' class='btn btn-secondary' data-dismiss='modal' onclick='location.href = \"control.php?content=product\"'>Agregar otro producto</button>
                          <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='goToPart($np + 1)'>Ver producto agregado</button>
                        </div>
                      </div>
                    </div>
                  </div>";
              echo "<script>$('#myModal').modal('show');</script>";
      } else {
          $info = "Error en la inserción de los datos, vuelva a intentarlo.";
      }
    }

  }
?>
<nav class="navbar navbar-light bg-light justify-content-between">
  <button type="button" class="btn btn-primary" onclick="location.href = 'control.php?content=product'">Nuevo producto</button>
  <form class="form-inline" action="control.php?content=storage" method="post">
    <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search" id="data" name="data" value="<?php echo $dato != ""? $dato : "" ?>" required>
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" id="buscarDato" name="buscarDato">Buscar</button>
  </form>
</nav>

<div class="table-responsive">
  <table class="table">
    <caption>Lista de productos del almacén</caption>
    <thead>
      <tr>
        <th scope="col">Producto</th>
        <th scope="col">Entrada</th>
        <th scope="col">Salida</th>
        <th scope="col">Fecha</th>
        <th scope="col">Editar</th>
        <th scope="col">Eliminar</th>
      </tr>
    </thead>
    <tbody>
      <?php
        if($sinres){
          echo '<tr>
                  <th colspan="6" class="text-center text-danger" >
                    No hay resultados
                  </th>
                </tr>';
        } else {
          $sqlquery = $sqlquery." LIMIT $ni, $paginas";

          $result = mysqli_query($conn, $sqlquery);
          while($row=mysqli_fetch_assoc($result)){
            modalforupdate($row['id_almacen'], $pag);
            modalfordelete($row['id_almacen'], $pag);
            echo '<tr>
                    <td>'.$row['producto'].'</td>
                    <td>'.$row['entrada'].'</td>
                    <td>'.$row['salida'].'</td>
                    <td>'.$row['fecha'].'</td>
                    <td><button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updModal'.$row['id_almacen'].'" data-whatever="producto">Editar</button></td>';
            echo "<td><button type='button' class='btn btn-outline-danger' onclick='openDeleteConfirmation(".$row['id_almacen'].");'>Eliminar</button></td>
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
      <a class="page-link" href="control.php?content=storage&part=<?php echo $pag != 0? ($pag-1).'' : '1'; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Anterior</a>
    </li>
    <?php
      for ($i=1; $i <= $np; $i++) {
        echo "<li class='page-item ".($i!=$pag? "" : "active")."'><a class='page-link' href='".($i!=$pag? "control.php?content=storage&part=".$i : "#").($dato != ""? "&buscarDato=true&data=$dato" : "")."'>".$i."</a></li>";
      }
    ?>
    <li class="page-item <?php echo $pag == $np? 'disabled' : ''?>">
      <a class="page-link" href="control.php?content=storage&part=<?php echo $pag != $np? ($pag+1).'' : $np.''; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Siguiente</a>
    </li>
  </ul>
</nav>

<?php
  function modalforupdate($id, $part){
    $conn = connect_db();
    $updQry = "SELECT * FROM t_almacen NATURAL JOIN t_productos WHERE id_almacen = $id";
    $resultupdqry = mysqli_query($conn, $updQry);

    while($row=mysqli_fetch_assoc($resultupdqry)){
      echo '<div class="modal fade" id="updModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="updModalLabel'.$id.'" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="updModalLabel'.$id.'">Actualizar producto de almacén</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="control.php?content=storage&part='.$part.'" method="post">
                      <input type="hidden" value="'.$row["id_producto"].'" name="idproducto">
                      <input type="hidden" value="'.$id.'" name="idalmacen">
                      <div class="form-group">
                        <label for="producto" class="col-form-label">Producto</label>
                        <input type="text" class="form-control" name="producto" value="'.$row['producto'].'" readonly>
                      </div>
                      <div class="form-group">
                        <label for="entrada" class="col-form-label">Entrada</label>
                        <input value="'.$row['entrada'].'" type="number" min="0" step="0.01" class="form-control" name="entrada" placeholder="Entrada" required>
                      </div>
                      <div class="form-group">
                        <label for="salida" class="col-form-label">Salida</label>
                        <input value="'.$row['salida'].'" type="number" min="0" step="0.01" class="form-control" name="salida" placeholder="Salida" required>
                      </div>
                      <div class="form-group">
                        <label for="fecha" class="col-form-label">Fecha</label>
                        <input value="'.$row['fecha'].'" type="date" min="0" step="0.01" class="form-control" name="fecha" placeholder="Fecha" required>
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
    $info = "¿Está seguro(a) de eliminar este producto del almacén?";
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
                  <form action='control.php?content=storage&part=$pag' method='post'>
                    <input type='hidden' value='".$id."' name='idalmacen'>
                    <button type='submit' class='btn btn-primary' name='eliminar'>Sí, eliminar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>";
  }

  if(isset($_POST['actualizar'])){
    $entrada = $_POST['entrada'];
    $salida = $_POST['salida'];
    $fecha = $_POST['fecha'];
    $id = $_POST['idalmacen'];

    $upd = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as coin FROM t_almacen WHERE entrada = $entrada and salida = $salida and fecha = '$fecha'"))["coin"] == 1? "D" : ("UPDATE t_almacen SET entrada = $entrada, salida = $salida, fecha = '$fecha' WHERE id_almacen = ".$id);
    $info = "";
    if($upd == "D"){
        $info = "Ya existe un producto registrado con estas características en el almacén. No se modificará el producto.";
    } else {
      if($conn->query($upd) === TRUE){
        $info = "Producto del almacén actualizado. Se actualizará la lista de productos del almacén.";
      } else {
        $info = "Error en la actualización de los datos, vuelva a intentarlo.";
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
  $iddel = $_POST['idalmacen'];
  $info = "El producto se ha eliminado del almacén. La lista de productos del almacén se actualizará.";
  $sql = "DELETE FROM t_almacen WHERE id_almacen = $iddel";
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
