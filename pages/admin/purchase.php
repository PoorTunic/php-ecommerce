<script type="text/javascript">

  function goToPart(np){
    location.href = "control.php?content=purchase&part=" + np;
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
      $sqlquery = "SELECT * FROM t_compras INNER JOIN t_productos ON t_compras.id_producto = t_productos.id_producto INNER JOIN t_proveedores ON t_compras.id_proveedor = t_proveedores.id_proveedor WHERE precompra = $dato or cantidad = $dato or year(fecha) = $dato or month(fecha) = $dato or day(fecha) = $dato";
      $qryrows = "SELECT count(*) as conteo FROM t_compras INNER JOIN t_productos ON t_compras.id_producto = t_productos.id_producto INNER JOIN t_proveedores ON t_compras.id_proveedor = t_proveedores.id_proveedor WHERE precompra = $dato or cantidad = $dato or year(fecha) = $dato or month(fecha) = $dato or day(fecha) = $dato";
    } else {
      $sqlquery = "SELECT * FROM t_compras INNER JOIN t_productos ON t_compras.id_producto = t_productos.id_producto INNER JOIN t_proveedores ON t_compras.id_proveedor = t_proveedores.id_proveedor WHERE producto like \"%$dato%\" or proveedor like \"%$dato%\"";
      $qryrows = "SELECT count(*) as conteo FROM t_compras INNER JOIN t_productos ON t_compras.id_producto = t_productos.id_producto INNER JOIN t_proveedores ON t_compras.id_proveedor = t_proveedores.id_proveedor WHERE producto like \"%$dato%\" or proveedor like \"%$dato%\"";
    }
  } else {
    $sqlquery = "SELECT * FROM t_compras NATURAL JOIN t_productos NATURAL JOIN t_proveedores";
    $qryrows = "SELECT count(*) as conteo FROM t_compras";
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
  <a class="navbar-brand">Compras</a>
  <button type="button" class="btn btn-outline-primary" onclick="location.href = 'control.php?content=purchase'">Ver todo</button>
</nav>
<?php
  if(isset($_POST['registrarPur'])){
    $idpr = $_POST['idpr'];
    $fecha = $_POST['fecha'];
    $prov = $_POST['prov'];
    $precom = $_POST['precompra'];
    $cantidad = $_POST['cantidad'];
    $parteor = $_POST['parteor'];

    $ins = "INSERT INTO t_compras VALUES (null, $idpr, '$fecha', (SELECT id_proveedor FROM t_proveedores WHERE proveedor = '$prov'), $precom, $cantidad)";

    $info = "";

    if($conn->query($ins) === TRUE){
        $info = "Compra agregada. Se actualizará la lista de compras.";
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
                        <button type='button' class='btn btn-secondary' data-dismiss='modal' onclick='location.href = \"control.php?content=product\"'>Agregar otra compra</button>
                        <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='goToPart($np + 1)'>Ver compra agregada</button>
                      </div>
                    </div>
                  </div>
                </div>";
            echo "<script>$('#myModal').modal('show');</script>";
      } else {
          $info = "Error en la inserción de los datos, vuelva a intentarlo.";
      }
}
?>
<nav class="navbar navbar-light bg-light justify-content-between">
  <button type="button" class="btn btn-primary" onclick="location.href = 'control.php?content=product'">Nueva compra</button>
  <!--form class="form-inline" action="control.php?content=category" method="post"-->
  <div class="">
    <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search" id="data" name="data" value="<?php echo $dato != ""? $dato : "" ?>" onkeyup="search()" required>
    <!--button class="btn btn-outline-success my-2 my-sm-0" type="button" id="buscarDato" name="buscarDato" >Buscar</button-->
  </div>
    <!--button class="btn btn-outline-success my-2 my-sm-0" type="submit" id="buscarDato" name="buscarDato">Buscar</button-->
  <!--/form-->
</nav>

<div class="table-responsive">
  <table class="table">
    <caption>Lista de compras</caption>
    <thead>
      <tr>
        <th scope="col">Producto</th>
        <th scope="col">Fecha</th>
        <th scope="col">Proveedor</th>
        <th scope="col">Precio de compra</th>
        <th scope="col">Cantidad</th>
        <th scope="col">Editar</th>
        <th scope="col">Eliminar</th>
      </tr>
    </thead>
    <tbody>
      <?php
        if($sinres){
          echo '<tr>
                  <th colspan="7" class="text-center text-danger" >
                    No hay resultados
                  </th>
                </tr>';
        } else {
          $sqlquery = $sqlquery." LIMIT $ni, $paginas";

          $result = mysqli_query($conn, $sqlquery);
          while($row=mysqli_fetch_assoc($result)){
            modalforupdate($row['id_compra'], $pag);
            modalfordelete($row['id_compra'], $pag);
            echo '<tr>
                    <td>'.$row['producto'].'</td>
                    <td>'.$row['fecha'].'</td>
                    <td>'.$row['proveedor'].'</td>
                    <td>'.$row['precompra'].'</td>
                    <td>'.$row['cantidad'].'</td>
                    <td><button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updModal'.$row['id_compra'].'" data-whatever="compra">Editar</button></td>';
            echo "<td><button type='button' class='btn btn-outline-danger' onclick='openDeleteConfirmation(".$row['id_compra'].");'>Eliminar</button></td>
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
      <a class="page-link" href="control.php?content=purchase&part=<?php echo $pag != 0? ($pag-1).'' : '1'; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Anterior</a>
    </li>
    <?php
      for ($i=1; $i <= $np; $i++) {
        echo "<li class='page-item ".($i!=$pag? "" : "active")."'><a class='page-link' href='".($i!=$pag? "control.php?content=purchase&part=".$i : "#").($dato != ""? "&buscarDato=true&data=$dato" : "")."'>".$i."</a></li>";
      }
    ?>
    <li class="page-item <?php echo $pag == $np? 'disabled' : ''?>">
      <a class="page-link" href="control.php?content=purchase&part=<?php echo $pag != $np? ($pag+1).'' : $np.''; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Siguiente</a>
    </li>
  </ul>
</nav>
<script type="text/javascript">
  function search(){

    var dato = document.getElementById('data').value;

    //var datos = "buscar=true&dato=" + dato;
    var data = new FormData();
    data.append('buscarDato', 'true');
    data.append('data', dato);


      var xhr = new XMLHttpRequest();
      xhr.open("POST", "?content=purchase");
      xhr.send(data);
      //xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function(){
        if(xhr.readyState == 4){
          document.body.innerHTML = xhr.responseText;
          var elemLen = document.getElementById("data").value.length;

          document.getElementById("data").selectionStart = elemLen;
          document.getElementById("data").selectionEnd = elemLen;

          document.getElementById("data").focus();
        }
      }
    
  }
</script>
<?php
  function modalforupdate($id, $part){
    $conn = connect_db();
    $updQry = "SELECT * FROM t_compras NATURAL JOIN t_productos NATURAL JOIN t_proveedores WHERE id_compra = $id";
    $resultupdqry = mysqli_query($conn, $updQry);
    $proveedopt = "";

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
                    <form action="control.php?content=purchase&part='.$part.'" method="post">
                      <input type="hidden" value="'.$row["id_producto"].'" name="idproducto">
                      <input type="hidden" value="'.$id.'" name="idcompra">
                      <div class="form-group">
                        <label for="producto" class="col-form-label">Producto</label>
                        <input type="text" class="form-control" name="producto" value="'.$row['producto'].'" readonly>
                      </div>
                      <div class="form-group">
                        <label for="precompra" class="col-form-label">Precio de compra</label>
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                          </div>
                          <input value="'.$row['precompra'].'" type="number" min="0" step="0.01" class="form-control" name="precompra" placeholder="Precio de compra" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="cantidad" class="col-form-label">Cantidad</label>
                        <input value="'.$row['cantidad'].'" type="number" min="0" step="1" class="form-control" name="cantidad" placeholder="Cantidad" required>
                      </div>
                      <div class="form-group">
                        <label for="prov" class="col-form-label">Proveedor</label>
                        <select name="prov" class="form-control"><option selected>'.$row['proveedor'].'</option>';
                  $qryproveedores = mysqli_query($conn, "SELECT proveedor FROM t_proveedores");
                  while ($provres=mysqli_fetch_assoc($qryproveedores)) {
                    echo ($provres['proveedor'] != $row["proveedor"]? ('<option>'.$provres['proveedor'].'</option>') : '');
                  }
           echo '  </select>
                      </div>
                      <div class="form-group">
                        <label for="fecha" class="col-form-label">Fecha</label>
                        <input value="'.$row['fecha'].'" type="date" class="form-control" name="fecha" placeholder="Fecha" required>
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
    $info = "¿Está seguro(a) de eliminar esta compra?";
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
                  <form action='control.php?content=purchase&part=$pag' method='post'>
                    <input type='hidden' value='".$id."' name='idcompra'>
                    <button type='submit' class='btn btn-primary' name='eliminar'>Sí, eliminar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>";
  }

  if(isset($_POST['actualizar'])){
      $idpr = $_POST['idproducto'];
      $fecha = $_POST['fecha'];
      $prov = $_POST['prov'];
      $precom = $_POST['precompra'];
      $cantidad = $_POST['cantidad'];
      $id = $_POST['idcompra'];

      $upd = "UPDATE t_compras SET fecha = '$fecha', id_proveedor = (SELECT id_proveedor FROM t_proveedores WHERE proveedor = '$prov'), precompra = $precom, cantidad = $cantidad WHERE id_compra = ".$id;
      $info = "";

      if($conn->query($upd) === TRUE){
        $info = "Compra actualizada. Se actualizará la lista de compras.";
      } else {
        $info = "Error en la actualización de los datos, vuelva a intentarlo.";
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
  $iddel = $_POST['idcompra'];
  $info = "La compra se ha eliminado. La lista de compras se actualizará.";
  $sql = "DELETE FROM t_compras WHERE id_compra = $iddel";
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
