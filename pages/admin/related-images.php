<script type="text/javascript">

  function goToPart(np){
    location.href = "control.php?content=related-images&part=" + np;
  }

  function openDeleteConfirmation(id){
    $('#delModal' + id).modal('show');
  }

  let valid = 0;




  $(document).ready(function (){
    $('#exampleModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var recipient = button.data('whatever') // Extract info from data-* attributes
      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
      var modal = $(this)
      modal.find('.modal-title').text('Nueva ' + recipient)
      });

    $("#archivosr").bind('change',function(){
        // Esto es un Array-like Object
        //console.log(name_imagen.files);

        //var prueba = Array.from(filesObj);
        //console.log(prueba);

        var filesObj = archivosr.files;

        var filesArray = Object.keys(filesObj).map(function(key){
          return filesObj[key];
        });

        filesArray.forEach(function(file){
          if(!(file.type == "image/jpg" || file.type == "image/jpeg" || file.type == "image/png" || file.type == "image/webp")){
            alert("Solo se aceptan archivos con extensión: jpg, jpeg, png y webp");
            $("#archivosr").val("");
          }
        });

      });

    $('#archivo').change(function(){

          var filename = $("#archivo").val() != ''? $("#archivo").val() : $("#archivoUpd").val();

          if(filename == null)
               alert('No ha seleccionado una imagen. Se pondrá una por defecto');
          else{
               var extension = filename.replace(/^.*\./, '');

               if (extension == filename)
                   extension = '';
               else{
                   extension = extension.toLowerCase();

                   if(!((extension == 'jpg') || (extension == 'png') ||
                      (extension == 'jpeg') || (extension == 'webp'))){
                        alert("Solo se aceptan archivos con extensión: jpg, jpeg, png y webp");
                        $("#archivo").val("");
                        $("#archivoUpd").val("");
                      }
             }
          }

      });



      $('#producto').change(function(){
        $('#registrar').attr("disabled", false);
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
    $sqlquery = "SELECT t_imagenes.imagen, producto, id_imagenes FROM t_imagenes INNER JOIN t_productos ON t_productos.id_producto = t_imagenes.id_producto WHERE producto LIKE '%".$dato."%' and tipo = 1 and status = 1";
    $qryrows = "SELECT count(*) as conteo FROM t_imagenes INNER JOIN t_productos ON t_productos.id_producto = t_imagenes.id_producto WHERE producto LIKE '%".$dato."%' and tipo = 1 and status = 1";
  } else {
    $sqlquery = "SELECT t_imagenes.imagen, producto, id_imagenes FROM t_imagenes INNER JOIN t_productos ON t_productos.id_producto = t_imagenes.id_producto WHERE tipo = 1 and status = 1";
    $qryrows = "SELECT count(*) as conteo FROM t_imagenes INNER JOIN t_productos ON t_productos.id_producto = t_imagenes.id_producto WHERE tipo = 1 and status = 1";
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
  <a class="navbar-brand">Lista de imágenes relacionadas</a>
  <button type="button" class="btn btn-outline-primary" onclick="location.href = 'control.php?content=related-images'">Ver todo</button>
</nav>
<?php
  if(isset($_POST['registrar'])){
    $conn = connect_db();
    $producto = $_POST['producto'];
    $idpr = 0;
    $sel = mysqli_query($conn, "SELECT id_producto FROM t_productos WHERE producto = '$producto'");
    while($rs = mysqli_fetch_assoc($sel)){
      $idpr = $rs['id_producto'];
    }

    $info = "";

    foreach($_FILES["archivosr"]['tmp_name'] as $key => $tmp_name){

      if($_FILES["archivosr"]["name"][$key]) {
        $filename = $_FILES["archivosr"]["name"][$key]; //Obtenemos el nombre original del archivo
        $source = $_FILES["archivosr"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
        $id = $idpr;
        //$directorio = $rowsql["imagen"]; //Declaramos un  variable con la ruta donde guardaremos los archivos

        //$dir=opendir($directorio); //Abrimos el directorio de destino
        $target_path = '../../img/'.$id.'_'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo

        if(copy($source, $target_path)){
          $ruta = 'img/'.$id.'_'.$filename;
          $idprod = $id;
          $ins = "INSERT INTO t_imagenes VALUES (null, '$ruta', $idprod, 1, 1)";
          $conn->query($ins);
        }

        //closedir($dir); //Cerramos el directorio de destino
      }
    }


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
                    <p>La imagen relacionada se ha agregado correctamente</p>
                  </div>
                  <div class='modal-footer'>
                    <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='goToPart($np + 1)'>Ver imagen agregada</button>
                  </div>
                </div>
              </div>
            </div>";
        echo "<script>$('#myModal').modal('show');</script>";

    }
?>
<nav class="navbar navbar-light bg-light justify-content-between">
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="imagen">Nueva imagen relacionada</button>
  <!--form class="form-inline" action="control.php?content=category" method="post"-->
  <div class="">
    <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search" id="data" name="data" value="<?php echo $dato != ""? $dato : "" ?>" onkeyup="search()" required>
    <!--button class="btn btn-outline-success my-2 my-sm-0" type="button" id="buscarDato" name="buscarDato" >Buscar</button-->
  </div>
    <!--button class="btn btn-outline-success my-2 my-sm-0" type="submit" id="buscarDato" name="buscarDato">Buscar</button-->
  <!--/form-->
</nav>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nueva imagen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="control.php?content=related-images" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label class="col-form-label">Imágenes relacionadas</label>
            <input type="file" required multiple class="form-control-file" name="archivosr[]" id="archivosr" accept="image/png, image/jpeg, image/gif, image/webp">
          </div>
          <div class="form-group">
            <label for="producto" class="col-form-label">Producto</label>
            <select name="producto" id="producto" class="form-control">
              <option selected disabled>Elegir...</option>
              <?php
                $cons = "SELECT producto FROM t_productos WHERE id_producto IN (SELECT id_producto FROM t_imagenes WHERE status = 1 and tipo = 1)";
                $res = mysqli_query($conn, $cons);
                while ($row=mysqli_fetch_assoc($res)) {
                  echo '<option>'.$row['producto'].'</option>';
                }
              ?>
            </select>
          </div>
          <div class="modal-footer">
            <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" name="registrar" id="registrar" disabled>Agregar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="table-responsive">
  <table class="table">
    <caption>Lista de imagenes relacionadas</caption>
    <thead>
      <tr>
        <th scope="col">Imagen</th>
        <th scope="col">Producto</th>
        <th scope="col">Cambiar</th>
        <th scope="col">Ocultar</th>
      </tr>
    </thead>
    <tbody>
      <?php
        if($sinres){
          echo '<tr>
                  <th colspan="4" class="text-center text-danger" >
                    No hay resultados
                  </th>
                </tr>';
        } else {
          $sqlquery = $sqlquery." LIMIT $ni, $paginas";

          $result = mysqli_query($conn, $sqlquery);
          while($row=mysqli_fetch_assoc($result)){
            modalforupdate($row['id_imagenes'], $pag);
            modalfordelete($row['id_imagenes'], $pag);
            echo '<tr>
                    <td><img src="../../'.$row['imagen'].'" style="height: 50px;""></td>
                    <td>'.$row['producto'].'</td>
                    <td><button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updModal'.$row['id_imagenes'].'" data-whatever="imagen relacionada">Cambiar</button></td>';
            echo "<td><button type='button' class='btn btn-outline-danger' onclick='openDeleteConfirmation(".$row['id_imagenes'].");'>Ocultar</button></td>
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
      <a class="page-link" href="control.php?content=related-images&part=<?php echo $pag != 0? ($pag-1).'' : '1'; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Anterior</a>
    </li>
    <?php
      for ($i=1; $i <= $np; $i++) {
        echo "<li class='page-item ".($i!=$pag? "" : "active")."'><a class='page-link' href='".($i!=$pag? "control.php?content=related-images&part=".$i : "#").($dato != ""? "&buscarDato=true&data=$dato" : "")."'>".$i."</a></li>";
      }
    ?>
    <li class="page-item <?php echo $pag == $np? 'disabled' : ''?>">
      <a class="page-link" href="control.php?content=related-images&part=<?php echo $pag != $np? ($pag+1).'' : $np.''; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Siguiente</a>
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
      xhr.open("POST", "?content=related-images");
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
    $updQry = "SELECT * FROM t_imagenes WHERE id_imagenes = $id";
    $resultupdqry = mysqli_query($conn, $updQry);
    $proveedopt = "";

    while($row=mysqli_fetch_assoc($resultupdqry)){
      echo '<div class="modal fade" id="updModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="updModalLabel'.$id.'" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="updModalLabel'.$id.'">Actualizar imagen de slider</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <form action="control.php?content=related-images" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="idimg" value="'.$id.'">
                    <div class="form-group">
                      <label class="col-form-label">Imagen del producto</label>
                      <input type="file" class="form-control-file" name="archivo" id="archivo" accept="image/png, image/jpeg, image/gif, image/webp">
                    </div>

                    <div class="form-group">
                      <label for="producto" class="col-form-label">Producto</label>
                      <select name="producto" id="producto" class="form-control">';
                      //  $cons = "SELECT producto FROM t_productos WHERE NOT id_producto IN (SELECT id_producto FROM t_imagenes WHERE status = 1 and tipo = 2)";
                      $cons = "SELECT id_producto, producto FROM t_productos";
                      $res = mysqli_query($conn, $cons);
                      while ($rowprod=mysqli_fetch_assoc($res)) {
                        if($row['id_producto'] == $rowprod['id_producto']){
                          echo '<option selected>'.$rowprod['producto'].'</option>';
                        } else {
                          echo '<option>'.$rowprod['producto'].'</option>';
                        }
                      }

              echo '  </select>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-primary" name="actualizar">Actualizar</button>
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
    $info = "¿Está seguro(a) de ocultar esta imagen relacionada?";
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
                  <form action='control.php?content=related-images&part=$pag' method='post'>
                    <input type='hidden' value='".$id."' name='idimg'>
                    <button type='submit' class='btn btn-primary' name='eliminar'>Sí, ocultar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>";
  }

  if(isset($_POST['actualizar'])){
      $id = $_POST['idimg'];
      $producto = $_POST['producto'];
      $idpr = 0;
      $sel = mysqli_query($conn, "SELECT id_producto FROM t_productos WHERE producto = '$producto'");
      while($rs = mysqli_fetch_assoc($sel)){
        $idpr = $rs['id_producto'];
      }
      $imagen = $_FILES['archivo']['name'];
      $rutalt = 'img/'.$idpr.'_m_'.$imagen;
      $rutaind = "../../img/".$idpr."_m_".$imagen;

      $upd = "UPDATE t_imagenes SET imagen = '$rutalt', id_producto = (SELECT id_producto FROM t_productos WHERE producto = '$producto') WHERE id_imagenes = $id";

      $info = "";
      if($imagen != ""){
        if(copy($_FILES["archivo"]["tmp_name"], $rutaind)){
          if($conn->query($upd) === TRUE){
                $info = "Imagen actualizada.";
            } else {
                $info = "Error en la inserción de los datos, vuelva a intentarlo.";
            }
        }
      } else {
          $upd = "UPDATE t_imagenes SET id_producto = (SELECT id_producto FROM t_productos WHERE producto = '$producto') WHERE id_imagenes = $id";
          if($conn->query($upd) === TRUE){
              $info = "Producto de la imagen actualizado.";
          } else {
              $info = "Error en la inserción de los datos, vuelva a intentarlo.";
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
  $img = $_POST['idimg'];
  $info = "La imagen se ha ocultado. La lista de imagenes relacionadas se actualizará.";
  $sql = "UPDATE t_imagenes SET status = 0 WHERE id_imagenes = $img";
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
