<script type="text/javascript">

  function goToPart(np){
    location.href = "control.php?content=category&part=" + np;
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
    if(is_string($dato)){
      $sqlquery = "SELECT * FROM t_categorias WHERE categoria like \"%$dato%\"";
      $qryrows = "SELECT count(*) as conteo FROM t_categorias WHERE categoria like \"%$dato%\"";
    }
  } else {
    $sqlquery = "SELECT * FROM t_categorias";
    $qryrows = "SELECT count(*) as conteo FROM t_categorias";
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
  <a class="navbar-brand">Categorías</a>
  <button type="button" class="btn btn-outline-primary" onclick="location.href = 'control.php?content=category'">Ver todo</button>
</nav>
<?php
  if(isset($_POST['registrar'])){
    $cat = $_POST['categoria'];

    $ins = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as contador FROM t_categorias WHERE categoria = '$cat'"))["contador"] == 1? "D" : "INSERT INTO t_categorias VALUES (null, '$cat')";

    $info = "";

    if($ins == "D"){
      $info = "Ya existe una categoría registrada con ese nombre. No se agregará la categoría.";
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
          $info = "Categoría agregada. Se actualizará la lista de categorías.";
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
                          <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='goToPart($np + 1)'>Ver categoría agregada</button>
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
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="categoría">Nueva categoría</button>
  <form class="form-inline" action="control.php?content=category" method="post">
    <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search" id="data" name="data" value="<?php echo $dato != ""? $dato : "" ?>" required>
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" id="buscarDato" name="buscarDato">Buscar</button>
  </form>
</nav>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nueva categoría</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="control.php?content=category" method="post">
          <div class="form-group">
            <label for="categoria" class="col-form-label">Categoría</label>
            <input type="text" class="form-control" name="categoria" placeholder="Nombre de la categoría" required maxlength="30">
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
    <caption>Lista de categorías</caption>
    <thead>
      <tr>
        <th scope="col">Categoría</th>
        <th scope="col">Editar</th>
        <th scope="col">Eliminar</th>
      </tr>
    </thead>
    <tbody>
      <?php
        if($sinres){
          echo '<tr>
                  <th colspan="3" class="text-center text-danger" >
                    No hay resultados
                  </th>
                </tr>';
        } else {
          $sqlquery = $sqlquery." LIMIT $ni, $paginas";

          $result = mysqli_query($conn, $sqlquery);
          while($row=mysqli_fetch_assoc($result)){
            modalforupdate($row['id_categoria'], $pag);
            modalfordelete($row['id_categoria'], $pag);
            echo '<tr>
                    <td>'.$row['categoria'].'</td>
                    <td><button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updModal'.$row['id_categoria'].'" data-whatever="categoria">Editar</button></td>';
            echo "<td><button type='button' class='btn btn-outline-danger' onclick='openDeleteConfirmation(".$row['id_categoria'].");'>Eliminar</button></td>
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
      <a class="page-link" href="control.php?content=category&part=<?php echo $pag != 0? ($pag-1).'' : '1'; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Anterior</a>
    </li>
    <?php
      for ($i=1; $i <= $np; $i++) {
        echo "<li class='page-item ".($i!=$pag? "" : "active")."'><a class='page-link' href='".($i!=$pag? "control.php?content=category&part=".$i : "#").($dato != ""? "&buscarDato=true&data=$dato" : "")."'>".$i."</a></li>";
      }
    ?>
    <li class="page-item <?php echo $pag == $np? 'disabled' : ''?>">
      <a class="page-link" href="control.php?content=category&part=<?php echo $pag != $np? ($pag+1).'' : $np.''; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Siguiente</a>
    </li>
  </ul>
</nav>

<?php
  function modalforupdate($id, $part){
    $conn = connect_db();
    $updQry = "SELECT * FROM t_categorias WHERE id_categoria = $id";
    $resultupdqry = mysqli_query($conn, $updQry);

    while($row=mysqli_fetch_assoc($resultupdqry)){
      echo '<div class="modal fade" id="updModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="updModalLabel'.$id.'" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="updModalLabel'.$id.'">Actualizar categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="control.php?content=category" method="post">
                      <input type="hidden" value="'.$id.'" name="idcategoria">
                      <div class="form-group">
                        <label for="categoria" class="col-form-label">Categoría</label>
                        <input value="'.$row['categoria'].'" type="text" class="form-control" name="categoria" placeholder="Nombre de la categoría" required maxlength="30">
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
    $info = "¿Está seguro(a) de eliminar esta categoría?";
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
                  <form action='control.php?content=category&part=$pag' method='post'>
                    <input type='hidden' value='".$id."' name='idcategoria'>
                    <button type='submit' class='btn btn-primary' name='eliminar'>Sí, eliminar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>";
  }

  if(isset($_POST['actualizar'])){
    $cat = $_POST['categoria'];
    $id = $_POST['idcategoria'];

    $upd = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as coin FROM t_categorias WHERE categoria = '$cat'"))["coin"] == 1? "D" : ("UPDATE t_categorias SET categoria = '$cat' WHERE id_categoria = ".$id);
    $info = "";
    if($upd == "D"){
        $info = "Ya existe una categoría registrada con ese nombre. No se modificará la categoría.";
    } else {
      if($conn->query($upd) === TRUE){
        $info = "Categoría actualizada. Se actualizará la lista de categorías.";
      } else {
        $info = "Error en la actualización de los datos, vuelva a intentarlo.";
      }
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

}

if(isset($_POST['eliminar'])){
  $iddel = $_POST['idcategoria'];
  $info = "La categoría se ha eliminado. La lista de categorías se actualizará.";
  $sql = "DELETE FROM t_categorias WHERE id_categoria = $iddel";
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
