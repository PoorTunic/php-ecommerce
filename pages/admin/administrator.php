<script type="text/javascript">
  let passed = false;
  let changededit = false;
  function goToPart(np){
    location.href = "control.php?content=administrator&part=" + np;
  }

  function openDeleteConfirmation(id){
    $('#delModal' + id).modal('show');
  }
  //Inicio validaciones
  function changepass(id){
    $('#contrapass-edit'+id).attr('disabled', false);
    $('#contrapass-confirmed-edit'+id).attr('disabled', false);
    $('#actualizar'+id).attr('disabled', true);
    $('#chpass'+id).hide();
    $('#notchpass'+id).show();
  }

  function notchangepass(id){
    $('#contrapass-edit'+id).attr('disabled', true);
    $('#contrapass-confirmed-edit'+id).attr('disabled', true);
    $('#actualizar'+id).attr('disabled', false);
    $('#chpass'+id).show();
    $('#notchpass'+id).hide();
  }

  function validarPass(){
    var value = $('#contrapass-confirmed').val();

    if(value.length == $('#contrapass').val().length){
      if(value == $('#contrapass').val() && value != ""){
        if(value.match(/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}/g)){
          if(!passed){
            alert("Contraseñas válidas. Puede continuar.");
            validations += 1;
            $('#contrapass-confirmed').attr("readonly", true);
            $('#contrapass').attr("readonly", true);
            passed = true;
            if(validations == valLimit){
              $('#registrar').attr("disabled", false);
              validations = 0;
            }
          }
        } else {
          alert("La contraseña no cumple con los requerimientos");
        }
      } else if(value != $('#contrapass').val() && value != ""){
        alert("Las contraseñas no coinciden");
      }
    }
  }
  valLimitEdit = 1;
  function editPassVal(id){
    var value = $('#contrapass-confirmed-edit' + id).val();

    if(value.length == $('#contrapass-edit' + id).val().length){
      if(value == $('#contrapass-edit' + id).val() && value != ""){
        if(value.match(/(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}/g)){
          alert("Contraseñas válidas. Puede continuar.");
          validations += 1;
          $('#contrapass-confirmed-edit' + id).attr("readonly", true);
          $('#contrapass-edit' + id).attr("readonly", true);
            if(validations == valLimitEdit){
            $('#actualizar'+id).attr('disabled', false);
            validations = 0;
          }
        } else {
          alert("La contraseña no cumple con los requerimientos");
        }
      } else if(value != $('#contrapass-edit' + id).val() && value != ""){
        alert("Las contraseñas no coinciden");
      }
    }
  }

  var validations = 0;
  var valLimit = 2;
  var changed = false;

  $(document).ready(function (){

    $('#contrapass-confirmed').keyup(function(){
      validarPass();
    });

    $('#contrapass').keyup(function(){
      validarPass();
    });

    $('#nivel').change(function(){
      if(!changed){
        validations += 1;
        changed = true;
        if(validations == valLimit){
          $('#registrar').attr("disabled", false);
          validations = 0;
        }
      }
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
      $sqlquery = "SELECT * FROM t_usuarios WHERE correo like \"%$dato%\"";
      $qryrows = "SELECT count(*) as conteo FROM t_usuarios WHERE correo like \"%$dato%\"";
    }
  } else {
    $sqlquery = "SELECT * FROM t_usuarios";
    $qryrows = "SELECT count(*) as conteo FROM t_usuarios";
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
  <a class="navbar-brand">Administradores</a>
  <button type="button" class="btn btn-outline-primary" onclick="location.href = 'control.php?content=administrator'">Ver todo</button>
</nav>
<?php
  if(isset($_POST['registrar'])){
    $correo = $_POST['email'];
    $contrapass = $_POST['contrapass'];
    $nivel = $_POST['nivel'];

    $ins = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as contador FROM t_usuarios WHERE correo = '$correo'"))["contador"] == 1? "D" : "INSERT INTO t_usuarios VALUES (null, '$correo', '$contrapass',".($nivel == 'Administrador'? 1 : 2).", 1)";

    $info = "";

    if($ins == "D"){
      $info = "Ya existe un usuario registrado con ese correo. No se agregará el usuario administrador.";
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
          $info = "Administrador agregado. Se actualizará la lista de administradores.";
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
                          <button type='button' class='btn btn-primary' data-dismiss='modal' onclick='goToPart($np + 1)'>Ver administrador agregado</button>
                        </div>
                      </div>
                    </div>
                  </div>";
              echo "<script>$('#myModal').modal('show');changed = false;</script>";
      } else {
          $info = "Error en la inserción de los datos, vuelva a intentarlo.";
      }
    }

  }
?>
<nav class="navbar navbar-light bg-light justify-content-between">
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="administrador">Nuevo administrador</button>
  <!--form class="form-inline" action="control.php?content=administrator" method="post"-->
    <div class="">
      <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search" id="data" name="data" value="<?php echo $dato != ""? $dato : "" ?>" onkeyup="search()" required>
      <!--button class="btn btn-outline-success my-2 my-sm-0" type="button" id="buscarDato" name="buscarDato" >Buscar</button-->
    </div>
  <!--/form-->
</nav>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nuevo administrador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="control.php?content=administrator" method="post">
          <div class="form-group">
            <label for="email" class="col-form-label">Correo electrónico:</label>
            <input type="email" class="form-control" name="email" placeholder="Correo del administrador" required maxlength="60">
          </div>
          <div class="form-group">
            <label for="contrapass" class="col-form-label">Nueva contraseña:</label>
            <input type="password" class="form-control" name="contrapass" id="contrapass" placeholder="Nueva contraseña" required maxlength="255" minlength="8">
            <small class="text-danger">Requerimientos: Al menos una mayúscula, una minúscula, un número y un caracter de los siguientes: #?!@$%^&*-</small>
          </div>
          <div class="form-group">
            <label for="contrapass-confirmed" class="col-form-label">Confirmar nueva contraseña:</label>
            <input type="password" class="form-control" name="contrapass-confirmed" id="contrapass-confirmed" placeholder="Confirmar nueva contraseña" required maxlength="255" minlength="8">
          </div>
          <div class="form-group">
            <label for="nivel" class="col-form-label">Nivel</label>
            <select name="nivel" id="nivel" class="form-control">
              <option selected disabled>Elegir...</option>
              <option>Administrador</option>
              <option>Secretario</option>
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
    <caption>Lista de administradores</caption>
    <thead>
      <tr>
        <th scope="col">Usuario (Correo electrónico)</th>
        <th scope="col">Nivel</th>
        <th scope="col">Estado</th>
        <th scope="col">Editar</th>
        <th scope="col">Eliminar</th>
      </tr>
    </thead>
    <tbody>
      <?php
        if($sinres){
          echo '<tr>
                  <th colspan="5" class="text-center text-danger" >
                    No hay resultados
                  </th>
                </tr>';
        } else {
          $sqlquery = $sqlquery." LIMIT $ni, $paginas";

          $result = mysqli_query($conn, $sqlquery);
          while($row=mysqli_fetch_assoc($result)){
            modalforupdate($row['id_usuario'], $pag);
            modalfordelete($row['id_usuario'], $pag);
            echo '<tr>
                    <td>'.$row['correo'].'</td>
                    <td>'.($row['nevel'] == 1? "Administrador(a)" : "Secretario(a)").'</td>
                    <td>'.($row['status'] == 1? "Vigente" : "Denegado").'</td>
                    <td><button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#updModal'.$row['id_usuario'].'" data-whatever="usuario">Editar</button></td>';
            echo "<td><button type='button' class='btn btn-outline-danger' onclick='openDeleteConfirmation(".$row['id_usuario'].");'>Eliminar</button></td>
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
      <a class="page-link" href="control.php?content=administrator&part=<?php echo $pag != 0? ($pag-1).'' : '1'; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Anterior</a>
    </li>
    <?php
      for ($i=1; $i <= $np; $i++) {
        echo "<li class='page-item ".($i!=$pag? "" : "active")."'><a class='page-link' href='".($i!=$pag? "control.php?content=administrator&part=".$i : "#").($dato != ""? "&buscarDato=true&data=$dato" : "")."'>".$i."</a></li>";
      }
    ?>
    <li class="page-item <?php echo $pag == $np? 'disabled' : ''?>">
      <a class="page-link" href="control.php?content=administrator&part=<?php echo $pag != $np? ($pag+1).'' : $np.''; echo ($dato != ""? "&buscarDato=true&data=$dato" : "");?>">Siguiente</a>
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
      xhr.open("POST", "?content=administrator");
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
    $updQry = "SELECT * FROM t_usuarios WHERE id_usuario = $id";
    $resultupdqry = mysqli_query($conn, $updQry);

    while($row=mysqli_fetch_assoc($resultupdqry)){
      echo '<div class="modal fade" id="updModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="updModalLabel'.$id.'" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="updModalLabel'.$id.'">Actualizar usuarios</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="control.php?content=administrator&part='.$part.'" method="post">
                        <input type="hidden" value="'.$id.'" name="idusuario">
                        <input type="hidden" value="'.$row['correo'].'" name="correooriginal">
                        <div class="form-group">
                          <label for="email" class="col-form-label">Correo electrónico:</label>
                          <input value="'.$row['correo'].'" type="email" class="form-control" name="email" placeholder="Correo del administrador" required maxlength="60">
                        </div>
                        <div class="form-group">
                          <label for="contrapass" class="col-form-label">Nueva contraseña: <button type="button" class="btn btn-link" onclick="changepass('.$id.');" id="chpass'.$id.'">Cambiar contraseña</button><button type="button" class="btn btn-link" onclick="notchangepass('.$id.');" id="notchpass'.$id.'">No cambiar contraseña</button></label>
                          <input disabled type="password" class="form-control" id="contrapass-edit'.$id.'" name="contrapass" placeholder="Nueva contraseña" maxlength="255" minlength="8" onkeyup="editPassVal('.$id.');">
                          <small class="text-danger">Requerimientos: Al menos una mayúscula, una minúscula, un número y un caracter de los siguientes: #?!@$%^&*-</small>
                        </div>
                        <div class="form-group">
                          <label for="contrapass-confirmed" class="col-form-label">Confirmar nueva contraseña:</label>
                          <input disabled type="password" class="form-control" id="contrapass-confirmed-edit'.$id.'" name="contrapass-confirmed" placeholder="Confirmar nueva contraseña" maxlength="255" minlength="8" onkeyup="editPassVal('.$id.');">
                        </div>
                        <div class="form-group">
                          <label for="nivel" class="col-form-label">Nivel</label>
                          <select name="nivel" id="nivelupd" class="form-control">
                            <option selected>'.($row['nevel'] == 1? 'Administrador' : 'Secretario').'</option>
                            <option>'.($row['nevel'] == 1? 'Secretario' : 'Administrador').'</option>
                          </select>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <button type="submit" class="btn btn-primary" name="actualizar" id="actualizar'.$id.'">Actualizar</button>
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
              $("#notchpass"+'.$id.').hide();
           </script>';

    }


  }

  function modalfordelete($id, $pag){
    $info = "¿Está seguro(a) de eliminar este usuario?";
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
                  <form action='control.php?content=administrator&part=$pag' method='post'>
                    <input type='hidden' value='".$id."' name='idusuario'>
                    <button type='submit' class='btn btn-primary' name='eliminar'>Sí, eliminar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>";
  }

  if(isset($_POST['actualizar'])){
    $correo = $_POST['email'];
    $co = $_POST['correooriginal'];
    $contrapass = $_POST['contrapass'];
    $nivel = $_POST['nivel'];
    $id = $_POST['idusuario'];

    $upd = $contrapass == "" ? ("UPDATE t_usuarios SET correo = '$correo', nevel = ".($nivel == 'Administrador'? 1 : 2)." WHERE id_usuario = ".$id) : ("UPDATE t_usuarios SET correo = '$correo', contrapass = '$contrapass', nevel = ".($nivel == 'Administrador'? 1 : 2)." WHERE id_usuario = ".$id);
    $coin = mysqli_fetch_assoc(mysqli_query($conn,"SELECT count(*) as contador FROM t_usuarios WHERE correo = '$correo'"))["contador"] == 1? "D": "";
    $info = "";

    if($coin == "D"){
      if($co != $correo){
        $info = "Ya existe un usuario registrado con ese correo. No se modificará el usuario administrador.";
      } else {
        if($conn->query($upd) === TRUE){
          $info = "Usuario actualizado. Se actualizará la lista de administradores.";
        } else {
          $info = "Error en la actualización de los datos, vuelva a intentarlo.";
        }
      }
    } else {
        if($conn->query($upd) === TRUE){
          $info = "Usuario actualizado. Se actualizará la lista de administradores.";
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
  $iddel = $_POST['idusuario'];
  $info = "El usuario se ha eliminado. La lista de administradores se actualizará.";
  $sql = "DELETE FROM t_usuarios WHERE id_usuario = $iddel";
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
