<script type="text/javascript">
  function goToPart(np){
    location.href = "control.php?content=product&part=" + np;
  }

  function openDeleteConfirmation(id){
    $('#delModal' + id).modal('show');
  }

  //Inicio validaciones

  $(document).ready(function (){

    $('#cat').change(function(){
      $('#registrar').attr("disabled", false);
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
             alert('No ha seleccionado una imagen');
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
  });

  //Fin validaciones

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
      $sqlquery = "SELECT * FROM t_productos NATURAL JOIN t_categorias WHERE preven = $dato";
      $qryrows = "SELECT count(*) as conteo FROM t_productos NATURAL JOIN t_categorias WHERE preven = $dato";
    } else {
      $sqlquery = "SELECT * FROM t_productos NATURAL JOIN t_categorias WHERE producto like \"%$dato%\" or descripcion LIKE \"%$dato%\" or categoria LIKE \"%$dato%\"";
      $qryrows = "SELECT count(*) as conteo FROM t_productos NATURAL JOIN t_categorias WHERE producto like \"%$dato%\" or descripcion LIKE \"%$dato%\" or categoria LIKE \"%$dato%\"";
    }
  } else {
    $sqlquery = "SELECT * FROM t_productos NATURAL JOIN t_categorias";
    $qryrows = "SELECT count(*) as conteo FROM t_productos";
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
  <a class="navbar-brand">Productos</a>
  <button type="button" class="btn btn-outline-primary" onclick="location.href = 'control.php?content=product'">Ver todo</button>
</nav>
<?php

  if(isset($_POST['registrar'])){
    $prod = $_POST['prod'];
    $preven = $_POST['preven'];
    $desc = $_POST['desc'];

    if(isset($_FILES['archivo']['tmp_name']) && $_FILES['archivo']['tmp_name'] != ""){
      $imagen = $_FILES['archivo']['name'];
      $ruta_imagen = '../../img/';
      $cat = $_POST['cat'];
      $idcat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_categoria FROM t_categorias WHERE categoria = '$cat'"))["id_categoria"];

      $ins = "INSERT INTO t_productos VALUES (null, '$prod', $preven, '$desc', '$ruta_imagen', $idcat)";

      $info = "";


      if($conn->query($ins) === TRUE){
        if($rowsql=mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_producto as id, imagen FROM t_productos ORDER by id_producto DESC LIMIT 1"))){

            foreach($_FILES["archivosr"]['tmp_name'] as $key => $tmp_name){

          		if($_FILES["archivosr"]["name"][$key]) {
          			$filename = $_FILES["archivosr"]["name"][$key]; //Obtenemos el nombre original del archivo
          			$source = $_FILES["archivosr"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
                $id = $rowsql['id'];
          			$directorio = $rowsql["imagen"]; //Declaramos un  variable con la ruta donde guardaremos los archivos

          			$dir=opendir($directorio); //Abrimos el directorio de destino
          			$target_path = '../../img/'.$id.'_'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo

                if(copy($source, $target_path)){
                  $ruta = 'img/'.$id.'_'.$filename;
                  $idprod = $rowsql["id"];
                  $insimgslider = "INSERT INTO t_imagenes VALUES (null, '$ruta', $idprod, 1, 1)";
                  $conn->query($insimgslider);
                }

          			closedir($dir); //Cerramos el directorio de destino
          		}
          	}

            $rutaact = 'img/'.$rowsql["id"].'_main_'.$filename;
            $rutaind = $ruta_imagen.$rowsql["id"].'_m_'.$imagen;
            $rutalt = 'img/'.$rowsql["id"].'_m_'.$imagen;
            if($conn->query("UPDATE t_productos SET imagen = '$rutalt' WHERE id_producto = ".$rowsql["id"])){
              if(copy($_FILES["archivo"]["tmp_name"], $rutaind)){
                $info = "Producto agregado. Se actualizará la lista de productos.";
              }
            }

        }
      } else {
        $info = "Error en la inserción de los datos, vuelva a intentarlo.";
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
                    <p>$info</p>
                  </div>
                  <div class='modal-footer'>
                    <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='goToPart($np + 1)'>Ver producto agregado</button>
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

  <form class="form-inline" action="control.php?content=product" method="post">
    <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search" id="data" name="data" value="<?php echo $dato != ""? $dato : "" ?>" required>
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" id="buscarDato" name="buscarDato" >Buscar</button>
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
            <input type="text" class="form-control" name="prod" placeholder="Nombre del producto" required maxlength="150">
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
            <label class="col-form-label">Imagen principal</label>
            <input type="file" class="form-control-file" name="archivo" id="archivo" accept="image/png, image/jpeg, image/gif, image/webp" required>
          </div>
          <div class="form-group">
            <label class="col-form-label">Imágenes relacionadas</label>
            <input type="file" multiple class="form-control-file" name="archivosr[]" id="archivosr" accept="image/png, image/jpeg, image/gif, image/webp" required>
          </div>
          <div class="form-group">
            <label for="cat" class="col-form-label">Categoría</label>
            <select name="cat" id="cat" class="form-control">
              <option selected disabled>Elegir...</option>
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
    <caption>Lista de productos</caption>
    <thead>
      <tr>
        <th scope="col">Nombre</th>
        <th scope="col">Precio de venta</th>
        <th scope="col">Descripción</th>
        <th scope="col">Imagen </th>
        <th scope="col">Categoría</th>
        <th scope="col">Editar</th>
        <th scope="col">Eliminar</th>
        <th scope="col">Agregar a almacén</th>
        <th scope="col">Agregar a compras</th>
      </tr>
    </thead>
    <tbody>
      <?php

        if($sinres){
          echo '<tr>
                  <th colspan="9" class="text-center text-danger" >
                    No hay resultados
                  </th>
                </tr>';
        } else {
          $sqlquery = $sqlquery." LIMIT $ni, $paginas";

          $result = mysqli_query($conn, $sqlquery);
          while($row=mysqli_fetch_assoc($result)){
            modalforupdate($row['id_producto'], $pag);
            modalfordelete($row['id_producto'], $pag);
            echo '<tr>
                    <th>'.$row['producto'].'</th>
                    <td>$'.$row['preven'].'</td>
                    <td>'.$row['descripcion']."</td>
                    <td><img src='../../".$row['imagen']."' style='height: 50px;'></td>
                    <td>".$row['categoria'].'</td>
                    <td><button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updModal'.$row['id_producto'].'" data-whatever="producto">Editar</button></td>';

            echo "<td><button type='button' class='btn btn-outline-danger' onclick='openDeleteConfirmation(".$row['id_producto'].");'>Eliminar</button></td>
                  <td><button type='button' class='btn btn-outline-primary' data-toggle='modal' data-target='#addToStorageModal".$row['id_producto']."' data-whatever='producto'>Agregar al almacén</button></td>
                  <td><button type='button' class='btn btn-outline-dark' data-toggle='modal' data-target='#addToPurchaseModal".$row['id_producto']."' data-whatever='producto'>Agregar a compras</button></td>
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
      <a class="page-link" href="control.php?content=product&part=<?php echo $pag != 0? ($pag-1).'' : '1'; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Anterior</a>
    </li>
    <?php
      for ($i=1; $i <= $np; $i++) {
        echo "<li class='page-item ".($i!=$pag? "" : "active")."'><a class='page-link' href='".($i!=$pag? "control.php?content=product&part=".$i : "#").($dato != ""? "&buscarDato=true&data=$dato" : "")."'>".$i."</a></li>";
      }
    ?>
    <li class="page-item <?php echo $pag == $np? 'disabled' : ''?>">
      <a class="page-link" href="control.php?content=product&part=<?php echo $pag != $np? ($pag+1).'' : $np.''; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Siguiente</a>
    </li>
  </ul>
</nav>

<?php
  function modalforupdate($id, $part){
    $conn = connect_db();
    $proveed = "";
    $obtprov = mysqli_query($conn, "SELECT proveedor FROM t_proveedores");
    while ($cadarow=mysqli_fetch_assoc($obtprov)) {
        $proveed = $proveed.'<option>'.$cadarow['proveedor'].'</option>';
    }

    $updQry = "SELECT * FROM t_productos NATURAL JOIN t_categorias WHERE id_producto = $id";
    $resultupdqry = mysqli_query($conn, $updQry);
    while($row=mysqli_fetch_assoc($resultupdqry)){
      echo '<div class="modal fade" id="updModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="updModalLabel'.$id.'" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="updModalLabel'.$id.'">Actualizar producto</h5>
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
                        <input maxlength="150" value="'.$row['producto'].'"type="text" class="form-control" name="prod" placeholder="Nombre del producto" required>
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
                        <label class="col-form-label">Imagen principal</label>
                        <small class="text-primary">*Si quiere conservar la imagen original, puede omitir este campo</small>
                        <input type="file" class="form-control-file" name="archivo" id="archivoUpd" accept="image/png, image/jpeg, image/gif, image/webp">
                      </div>';

                      echo '<div class="form-group">
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

          echo '<div class="modal fade" id="addToStorageModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="addToStorageModalLabel">Agregar producto al almacén</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form action="control.php?content=storage" method="post">
                          <input type="hidden" name="idpr" value="'.$id.'">
                          <input type="hidden" name="parteor" value="'.$part.'">
                          <div class="form-group">
                            <label for="producto" class="col-form-label">Producto</label>
                            <input type="text" class="form-control" name="producto" value="'.$row['producto'].'" readonly>
                          </div>
                          <div class="form-group">
                            <label for="entrada" class="col-form-label">Entrada</label>
                            <input type="number" min="0" step="0.01" class="form-control" name="entrada" placeholder="Entrada" required>
                          </div>
                          <div class="form-group">
                            <label for="salida" class="col-form-label">Salida</label>
                            <input type="number" min="0" step="0.01" class="form-control" name="salida" placeholder="Salida" required>
                          </div>
                          <div class="form-group">
                            <label for="fecha" class="col-form-label">Fecha</label>
                            <input type="date" min="0" step="0.01" class="form-control" name="fecha" placeholder="Fecha" required>
                          </div>
                          <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" name="registrarSto" id="registrarSto">Agregar al almacén</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>';
        echo '<div class="modal fade" id="addToPurchaseModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="addToStorageModalLabel">Agregar producto compras</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="control.php?content=purchase" method="post">
                        <input type="hidden" name="idpr" value="'.$id.'">
                        <input type="hidden" name="parteor" value="'.$part.'">
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
                            <input type="number" min="1" step="0.01" class="form-control" name="precompra" placeholder="Precio de compra" required>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="cantidad" class="col-form-label">Cantidad</label>
                          <input type="number" min="1" step="1" class="form-control" name="cantidad" placeholder="Cantidad" required>
                        </div>
                        <div class="form-group">
                          <label for="prov" class="col-form-label">Proveedor</label>
                          <select name="prov" id="prov'.$id.'" class="form-control"><option selected disabled>Elegir...</option>'.$proveed.'</select>
                        </div>
                        <div class="form-group">
                          <label for="fecha" class="col-form-label">Fecha</label>
                          <input type="date" class="form-control" name="fecha" placeholder="Fecha" required>
                        </div>
                        <div class="modal-footer">
                          <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <button type="submit" class="btn btn-primary" name="registrarPur" id="registrarPur'.$id.'" disabled>Agregar a compras</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>';
        echo "<script>
              $('#addToStorageModal".$id."').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget) // Button that triggered the modal
              var recipient = button.data('whatever') // Extract info from data-* attributes
              // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
              // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
              var modal = $(this)
              modal.find('.modal-title').text('Agregar ' + recipient + ' al almacén')
            });
            $('#prov".$id."').change(function(){
              $('#registrarPur".$id."').attr('disabled', false);
            });
            </script>";
            $qryimgrel = "SELECT imagen FROM t_imagenes WHERE id_producto = $id";
            $resultqryimgrel = mysqli_query($conn, $qryimgrel);
            $contador = 0;
            echo '<div class="modal fade" id="updImgModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="updImgModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cambiar imágenes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="control.php?content=product" method="post" enctype="multipart/form-data">';
            while($rowimgrel=mysqli_fetch_assoc($resultqryimgrel)){
              $contador += 1;
              echo '   <div class="form-group">
                          <label for="cambiarImg'.$contador.'_'.$id.'" class="col-form-label">Imagen relacionada #'.$contador.'</label>
                          <input type="file" class="form-control" id="cambiarImg'.$contador.'_'.$id.'" name="cambiarImg'.$contador.'_'.$id.'" accept="image/png, image/jpeg, image/gif, image/webp" required>
                        </div>';
                        echo "<script>
                            $('#cambiarImg".$contador.'_'.$id."').change(function(){

                            var filename = $('#cambiarImg".$contador.'_'.$id."').val();

                            if(filename == null)
                                 alert('No ha seleccionado una imagen');
                            else{
                                 var extension = filename.replace(/^.*\./, '');

                                 if (extension == filename)
                                     extension = '';
                                 else{
                                     extension = extension.toLowerCase();

                                     if(!((extension == 'jpg') || (extension == 'png') ||
                                        (extension == 'jpeg') || (extension == 'webp'))){
                                          alert('Solo se aceptan archivos con extensión: jpg, jpeg, png y webp');
                                          $('#cambiarImg".$contador.'_'.$id."').val('');
                                    } else{
                                      $('#actualizarimg".$id."').attr('disabled', false);
                                    }
                               }
                            }

                        });</script>";
            }

            echo '<div class="modal-footer">
              <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" name="actualizarimg'.$id.'" id="actualizarimg'.$id.'" disabled>Cambiar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>';



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
    $preven = $_POST['preven'];
    $desc = $_POST['desc'];
    $id = $_POST['idprod'];
    $rutaimg = $_POST['rutaimg'];

    if(isset($_FILES['archivo']['tmp_name']) && $_FILES['archivo']['tmp_name'] != ""){
      $imagen = $_FILES['archivo']['name'];
      $ruta_imagen = '../../img/'.$imagen;
      $cat = $_POST['cat'];
      $idcat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_categoria FROM t_categorias WHERE categoria = '$cat'"))["id_categoria"];

      $upd = "UPDATE t_productos SET producto = '$prod', preven = $preven, descripcion = '$desc', imagen = '$ruta_imagen', id_categoria = $idcat WHERE id_producto = ".$id;
      $info = "";
      $rt = "/img".$imagen;

      if($conn->query($upd) === TRUE){
        if($conn->query("UPDATE t_imagenes SET imagen = '$rt' WHERE id_producto = $id") == TRUE){
          if(copy($_FILES["archivo"]["tmp_name"], $ruta_imagen)){
            $info = "Producto actualizado. Se actualizará la lista de productos.";
          }
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

      $upd = "UPDATE t_productos SET producto = '$prod', preven = $preven, descripcion = '$desc', imagen = '$rutaimg', id_categoria = $idcat WHERE id_producto = ".$id;
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
  $sql = "DELETE FROM t_productos WHERE id_producto = $iddel";
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
