<script type="text/javascript">
  function goToPart(np){
    location.href = "control.php?content=product&part=" + np;
  }

  function openDeleteConfirmation(id){
    $('#delModal' + id).modal('show');
  }

  $('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var recipient = button.data('whatever') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-title').text('Nuevo ' + recipient)
    });
</script>
<?php
  include "../php/database.php";
  $conn = connect_db();

  $paginas = 5;
  $sinres = FALSE;
  $cantidad = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as conteo FROM t_producto"))["conteo"];
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
  <a class="navbar-brand">Productos</a>
</nav>
<?php
  if(isset($_POST['registrar'])){
    $prod = $_POST['prod'];
    $precom = $_POST['precom'];
    $preven = $_POST['preven'];
    $desc = $_POST['desc'];


    if(isset($_FILES['archivo']['tmp_name']) && $_FILES['archivo']['tmp_name'] != ""){
      $imagen = $_FILES['archivo']['name'];
      $ruta_imagen = '../img/'.$imagen;
      $cat = $_POST['cat'];
      $idcat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_categoria FROM t_categorias WHERE categoria = '$cat'"))["id_categoria"];

      $ins = "INSERT INTO t_producto VALUES (null, '$prod', $precom, $preven, '$desc', '$ruta_imagen', $idcat)";
      $info = "";

      if($conn->query($ins) === TRUE){

        if(copy($_FILES["archivo"]["tmp_name"], $ruta_imagen)){
          $info = "Producto agregado. Se actualizará la lista de productos.";
        }
      } else {

        $info = "Error en la inserción de los datos, vuelva a intentarlo.";
      }
      echo "<div class='modal' tabindex='-1' role='dialog' id='myModal'>
              <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                  <div class='modal-header'>
                    <h5 class='modal-title'>Cuadro de información</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar' onclick='goToPart($np)'>
                      <span aria-hidden='true'>&times;</span>
                    </button>
                  </div>
                  <div class='modal-body'>
                    <p>$info</p>
                  </div>
                  <div class='modal-footer'>
                    <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='goToPart($np)'>Ver producto agregado</button>
                  </div>
                </div>
              </div>
            </div>";
        echo "<script>$('#myModal').modal('show');</script>";
    }

  }
?>
<nav class="navbar navbar-light bg-light justify-content-between">
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="producto">Nuevo producto</button>
  <form class="form-inline">
    <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
  </form>
</nav>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nuevo producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="control.php?content=product" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="prod" class="col-form-label">Nombre:</label>
            <input type="text" class="form-control" name="prod" placeholder="Nombre del producto" required>
          </div>
          <div class="form-group">
            <label for="precom" class="col-form-label">Precio de compra:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">$</div>
              </div>
              <input type="number" min="0" step="0.01" class="form-control" name="precom" placeholder="Precio de compra" required>
            </div>
          </div>
          <div class="form-group">
            <label for="preven" class="col-form-label">Precio de venta:</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">$</div>
              </div>
              <input type="number" min="0" step="0.01" class="form-control" name="preven" placeholder="Precio de venta" required>
            </div>
          </div>
          <div class="form-group">
            <label for="desc" class="col-form-label">Descripción</label>
            <textarea class="form-control" name="desc" maxlength="200" required></textarea>
          </div>
          <div class="form-group">
            <label class="col-form-label">Imagen</label>
            <input type="file" class="form-control-file" name="archivo" id="archivo" accept="image/png, image/jpeg, image/gif, image/webp" required>
          </div>
          <div class="form-group">
            <label for="cat" class="col-form-label">Categoría</label>
            <select name="cat" class="form-control">
              <option selected>Elegir...</option>
              <?php
                $cons = "SELECT categoria FROM t_categorias";
                $res = mysqli_query($conn, $cons);
                while ($row=mysqli_fetch_assoc($res)) {
                  echo '<option>'.$row['categoria'].'</option>';
                }
              ?>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" name="registrar">Agregar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="table-responsive">
  <table class="table">
    <caption>Lista de productos</caption>
    <thead>
      <tr>
        <th scope="col">Nombre</th>
        <th scope="col">Precio de compra</th>
        <th scope="col">Precio de venta</th>
        <th scope="col">Descripción</th>
        <th scope="col">Imagen</th>
        <th scope="col">Categoría</th>
        <th scope="col">Editar</th>
        <th scope="col">Eliminar</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if($sinres){
        echo '<tr>
                <th colspan="8" class="text-center text-danger" >
                  No hay resultados
                </th>
              </tr>';
      } else {
        $qry = "SELECT * FROM t_producto NATURAL JOIN t_categorias LIMIT $ni, $paginas";
        $result = mysqli_query($conn, $qry);
        while($row=mysqli_fetch_assoc($result)){
          modalforupdate($row['id_producto'], $pag);
          modalfordelete($row['id_producto'], $pag);
          echo '<tr>
                  <th>'.$row['producto'].'</th>
                  <td>$'.$row['precom'].'</td>
                  <td>$'.$row['preven'].'</td>
                  <td>'.$row['descripcion']."</td>
                  <td><img src='".$row['imagen']."' style='height: 50px;'></td>
                  <td>".$row['categoria'].'</td>
                  <td><button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updModal'.$row['id_producto'].'" data-whatever="producto">Editar</button></td>';

          echo "<td><button type='button' class='btn btn-outline-danger' onclick='openDeleteConfirmation(".$row['id_producto'].");'>Eliminar</button></td>
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
      <a class="page-link" href="control.php?content=product&part=<?php echo $pag != 0? ($pag-1).'' : '1'?>">Anterior</a>
    </li>
    <?php
      for ($i=1; $i <= $np; $i++) {
        echo "<li class='page-item ".($i!=$pag? "" : "active")."'><a class='page-link' href='".($i!=$pag? "control.php?content=product&part=".$i : "#")."'>".$i."</a></li>";
      }
    ?>
    <li class="page-item <?php echo $pag == $np? 'disabled' : ''?>">
      <a class="page-link" href="control.php?content=product&part=<?php echo $pag != $np? ($pag+1).'' : $np.''?>">Siguiente</a>
    </li>
  </ul>
</nav>

<?php
  function modalforupdate($id, $part){
    $conn = connect_db();
    $updQry = "SELECT * FROM t_producto NATURAL JOIN t_categorias WHERE id_producto = $id";
    $resultupdqry = mysqli_query($conn, $updQry);
    while($row=mysqli_fetch_assoc($resultupdqry)){
      echo '<div class="modal fade" id="updModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="updModalLabel'.$id.'" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="updModalLabel'.$id.'">New message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="control.php?content=product&part='.$part.'" method="post" enctype="multipart/form-data">
                      <input type="hidden" value="'.$id.'" name="idprod">
                      <input type="hidden" value="'.$row['imagen'].'" name="rutaimg">
                      <div class="form-group">
                        <label for="prod" class="col-form-label">Nombre:</label>
                        <input value="'.$row['producto'].'"type="text" class="form-control" name="prod" placeholder="Nombre del producto" required>
                      </div>
                      <div class="form-group">
                        <label for="precom" class="col-form-label">Precio de compra:</label>
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                          </div>
                          <input value="'.$row['precom'].'" type="number" min="0" step="0.01" class="form-control" name="precom" placeholder="Precio de compra" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="preven" class="col-form-label">Precio de venta:</label>
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                          </div>
                          <input value="'.$row['preven'].'" type="number" min="0" step="0.01" class="form-control" name="preven" placeholder="Precio de venta" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="desc" class="col-form-label">Descripción</label>
                        <textarea class="form-control" name="desc" maxlength="200" required>'.$row['descripcion'].'</textarea>
                      </div>
                      <div class="form-group">
                        <label class="col-form-label">Imagen</label>
                        <input type="file" class="form-control-file" name="archivo" id="archivo" accept="image/png, image/jpeg, image/gif, image/webp">
                      </div>
                      <div class="form-group">
                        <label for="cat" class="col-form-label">Categoría</label>
                        <select name="cat" class="form-control">
                          <option selected>'.$row['categoria'].'</option>';

                            $catqry = "SELECT categoria FROM t_categorias";
                            $updcatres = mysqli_query($conn, $catqry);
                            while ($catrow=mysqli_fetch_assoc($updcatres)) {
                              if($catrow['categoria']!= $row['categoria']){
                                echo '<option>'.$catrow['categoria'].'</option>';
                              }
                            }
                    echo  '</select>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" name="actualizar">Actualizar</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <script>
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
    $info = "¿Está seguro(a) de eliminar este producto?";
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
                  <form action='control.php?content=product&part=$pag' method='post'>
                    <input type='hidden' value='".$id."' name='idprod'>
                    <button type='submit' class='btn btn-primary' name='eliminar'>Sí, eliminar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>";
  }

  if(isset($_POST['actualizar'])){
    $prod = $_POST['prod'];
    $precom = $_POST['precom'];
    $preven = $_POST['preven'];
    $desc = $_POST['desc'];
    $id = $_POST['idprod'];
    $rutaimg = $_POST['rutaimg'];

    if(isset($_FILES['archivo']['tmp_name']) && $_FILES['archivo']['tmp_name'] != ""){
      $imagen = $_FILES['archivo']['name'];
      $ruta_imagen = '../img/'.$imagen;
      $cat = $_POST['cat'];
      $idcat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_categoria FROM t_categorias WHERE categoria = '$cat'"))["id_categoria"];

      $upd = "UPDATE t_producto SET producto = '$prod', precom = $precom, preven = $preven, descripcion = '$desc', imagen = '$ruta_imagen', id_categoria = $idcat WHERE id_producto = ".$id;
      $info = "";

      if($conn->query($upd) === TRUE){
        if(copy($_FILES["archivo"]["tmp_name"], $ruta_imagen)){
          $info = "Producto actualizado. Se actualizará la lista de productos.";
        }
      } else {
        $info = "Error en la actualización de los datos, vuelva a intentarlo.";
      }
      echo "<div class='modal' tabindex='-1' role='dialog' id='updModal'>
              <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                  <div class='modal-header'>
                    <h5 class='modal-title'>Cuadro de información</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Cerrar'  onclick='goToPart(document.getElementById(\"parte\").value);'>
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
    } else {

      $cat = $_POST['cat'];
      $idcat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_categoria FROM t_categorias WHERE categoria = '$cat'"))["id_categoria"];

      $upd = "UPDATE t_producto SET producto = '$prod', precom = $precom, preven = $preven, descripcion = '$desc', imagen = '$rutaimg', id_categoria = $idcat WHERE id_producto = ".$id;
      $info = "";

      if($conn->query($upd) === TRUE){
        $info = "Producto actualizado. Se actualizará la lista de productos.";
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
}

if(isset($_POST['eliminar'])){
  $iddel = $_POST['idprod'];
  $info = "El producto se ha eliminado. La lista de productos se actualizará.";
  $sql = "DELETE FROM t_producto WHERE id_producto = $iddel";
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
