<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css" >
    <link rel="stylesheet" href="../css/style.css" >

    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/popper.min.js"></script>
</head>
<script type="text/javascript">
function gotologin(){
  $('#myModal').modal('show');
}
</script>
<?php
  $pdfroute = "";
  $bought = false;
  require "../php/database.php";
  require "../fpdf/fpdf.php";

  if(isset($_POST['up'])){
    foreach ($_SESSION['cart'] as $key => $producto) {
      if($producto['id'] == $_POST['ident']){
        if($producto['cantidad'] < 50){
          $_SESSION['cart'][$key]['cantidad'] += 1;
          $_SESSION['cart'][$key]['subtotal'] = $_SESSION['cart'][$key]['precio'] * $_SESSION['cart'][$key]['cantidad'];
        }
      }
    }
  }
  if(isset($_POST['down'])){
    foreach ($_SESSION['cart'] as $key => $producto) {
      if($producto['id'] == $_POST['ident']){
        if($producto['cantidad'] > 1){
          $_SESSION['cart'][$key]['cantidad'] -= 1;
          $_SESSION['cart'][$key]['subtotal'] = $_SESSION['cart'][$key]['precio'] * $_SESSION['cart'][$key]['cantidad'];
        }
      }
    }
  }
  if(isset($_POST['drop'])){
    foreach ($_SESSION['cart'] as $key => $producto) {
      if($producto['id'] == $_POST['id']){
        unset($_SESSION['cart'][$key]);
      }
    }
  }

  if(isset($_POST['comprar'])){
    if($_SESSION['totalcart'] != 0){
      $bought = false;
      class PDF extends FPDF
      {
      // Cabecera de página
      function Header()
      {
          // Logo
          $this->Image('../img/techstore.png',10,8,73);
          // Arial bold 15
          $this->SetFont('Arial','B',10);
          $this->setFillColor(255, 142, 35);
          $this->setDrawColor(255, 142, 35);
          // Movernos a la derecha
          $this->Cell(85);
          $this->Cell(0,0,utf8_decode('Av. de las Rosas Mz.3'),0,1);
          $this->Cell(85);
          $this->Cell(43,7,utf8_decode('Lt. 8 Los Héroes Tecámac'),0,1);
          $this->Cell(85);
          $this->Cell(43,0,utf8_decode('Sección Flores, Tecámac,'),0,1);
          $this->Cell(85);
          $this->Cell(43,7,utf8_decode('Estado de México'),0,1);

          $this->SetY(8);
          $this->Cell(143);
          $this->SetFont('Arial', 'B', 11);

          $this->Cell(20,7,'FECHA', 1, 1, 'C',true);
          $this->SetFont('Arial','B',10);
          $this->Cell(143);
          $this->Cell(20,7,''.date("Y-m-d"), 1, 1, 'C');

          $this->SetY(8);
          $this->Cell(170);
          $this->SetFont('Arial','B',11);

          $this->Cell(20,7,'FOLIO', 1, 1, 'C',true);
          $this->SetFont('Arial','B',10);
          $this->Cell(170);
          $this->Cell(20,7,'F-'.rand(1000,9999), 1, 1, 'C');

          $this->Ln(20);
      }

      // Pie de página
      function Footer()
      {
          // Posición: a 1,5 cm del final
          $this->SetY(-15);
          // Arial italic 8
          $this->SetFont('Arial','I',8);
          // Número de página
          $this->Cell(0,10,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
      }
      }

      // Creación del objeto de la clase heredada
      $pdf = new PDF();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',12);
      $pdf->setFillColor(255, 142, 35);
      $pdf->setDrawColor(255, 142, 35);
      $pdf->Cell(190,7,utf8_decode('CLIENTE'),1,1,'C', true);

      $conn = connect_db();
      $correo = $_SESSION['username'];
      $total = $_SESSION['totalcart'];
      $cliente = null;
      $qrycliente = mysqli_query($conn, "SELECT * FROM t_clientes INNER JOIN t_estados ON t_clientes.id_estado = t_estados.id_estado INNER JOIN t_municipios ON t_clientes.id_municipio = t_municipios.id_municipio INNER JOIN t_colonias ON t_clientes.id_colonia = t_colonias.id_colonia INNER JOIN t_codpos ON t_clientes.id_codpos = t_codpos.id_codpos WHERE correo1 = '$correo'");
      while($row = mysqli_fetch_assoc($qrycliente)){
        $cliente = array(
          'id_cliente' => $row['id_cliente'],
          'cliente' => $row['cliente'],
          'calle' => $row['calle'],
          'noint' => $row['noint'],
          'noext' => $row['noext'],
          'colonia' => $row['colonia'],
          'municipio' => $row['municipio'],
          'estado' => $row['estado'],
          'codpos' => $row['codpos']
        );
      }

      $clientnamerows = 7;
      $nombre = $cliente['cliente'];

      if(strlen($nombre)>63){
        $clientnamerows *= (ceil(strlen($nombre)/63));
      }

      $pdf->Cell(50,$clientnamerows,utf8_decode('Nombre o razón social:'),1,0,'L');
      $pdf->MultiCell(140,7,utf8_decode($nombre),1,'J');

      $clientdirectionrows = 7;
      $direccion = $cliente['calle'].', '
      .$cliente['noext'].', '
      .$cliente['noint'].', '
      .$cliente['colonia'].', '
      .$cliente['municipio'].', '
      .$cliente['estado'].', '
      .$cliente['codpos'];

      if(strlen($direccion)>63){
        $clientdirectionrows *= (ceil(strlen($direccion)/63));
      }

      $pdf->Cell(50,$clientdirectionrows,utf8_decode('Dirección:'),1,0,'L');
      $pdf->MultiCell(140,7, utf8_decode($direccion),1,'J');

      $pdf->Ln(10);

      $pdf->Cell(105,7,utf8_decode('Descripción del producto'),0,0,'C', true);
      $pdf->Cell(35,7,utf8_decode('Valor unitario'),0,0,'C', true);
      $pdf->Cell(25,7,utf8_decode('Cantidad'),0,0,'C', true);
      $pdf->Cell(25,7,utf8_decode('Importe'),0,1,'C', true);

      foreach ($_SESSION['cart'] as $key => $producto) {
        $pdf->Cell(105,7,utf8_decode($producto['producto']),1,0,'C');
        $pdf->Cell(35,7,utf8_decode('$'.$producto['precio']),1,0,'C');
        $pdf->Cell(25,7,utf8_decode($producto['cantidad']),1,0,'C');
        $pdf->Cell(25,7,utf8_decode('$'.$producto['subtotal']),1,1,'C');
      }

      $pdf->Ln(40);

      $pdf->Cell(160,21,'',1,0,'C', true);
      $pdf->Cell(30,7,'TOTAL',1,1,'C', true);
      $pdf->Cell(160);
      $pdf->Cell(30,14,'$'.$total,1,1,'C');


      $idcliente = $cliente['id_cliente'];
      $pdfroute = "fpdf/generated/FC$idcliente-comprobante.pdf";
      $pdf->Output('F', "../$pdfroute", true);


      $ins = "INSERT INTO t_ventas VALUES (null, 1, $total , $idcliente, now(), '$pdfroute')";
      if($conn->query($ins) === TRUE){

      }
      foreach ($_SESSION['cart'] as $key => $producto) {
        $pedidosidprod = $producto['id'];
        $pedidoscantidad = $producto['cantidad'];
        $inspedido = "INSERT INTO t_pedidos VALUES (null, $pedidosidprod, $pedidoscantidad, (SELECT id_venta FROM t_ventas WHERE pdfventa = 'fpdf/generated/FC".$idcliente."-comprobante.pdf' ORDER BY id_venta DESC LIMIT 1), now())";
        if($conn->query($inspedido) === TRUE){
          $bought = true;
        }
      }
    } else{
      echo "<script>location.href = 'cart.php'</script>";
    }
  }

  $_SESSION['totalcart'] = 0;
  if( isset($_SESSION['cart'])){
     foreach ($_SESSION['cart'] as $key => $producto) {
       $_SESSION['totalcart'] += $_SESSION['cart'][$key]['subtotal'];
     }
  }

?>

<body class="container">
  <?php require "../php/header.php" ?>
  <br>
  <?php
    if($bought){
      foreach ($_SESSION['cart'] as $key => $producto) {
        unset($_SESSION['cart'][$key]);
      }
  ?>
  <div class="alert alert-success" role="alert">
    Su compra se ha realizado con éxito <a target="_blank" href="../<?= $pdfroute ?>" class="alert-link">Haga clic aquí para ver su comprobante</a>. Su pedido se está procesando.
  </div>
  <?php
    } 
  ?>

  <table class="table table-bordered">
    <thead class="thead-dark">
      <tr>
        <th scope="col" class="text-center">Imagen</th>
        <th scope="col" class="text-center">Precio</th>
        <th scope="col" colspan="3" class="text-center">Cantidad</th>
        <th scope="col" class="text-center">Subtotal</th>
        <th scope="col" class="text-center">X</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if(isset($_SESSION['cart'])){
        if(count($_SESSION['cart']) > 0){
          foreach ($_SESSION['cart'] as $key => $articulo) {
      ?>
      <tr>
        <td class="text-center"><img src="../<?= $articulo['imagen'] ?>" class="img-fluid" style="max-height: 50px;"></td>
        <td class="text-center">$<?= $articulo['precio'] ?></td>
        <td class="text-center">
          <form action="cart.php" method="post" >
            <input type="hidden" name="ident" value="<?= $articulo['id'] ?>">
            <input type="hidden" name="cant" value="<?= $articulo['cantidad'] ?>">
            <button class="btn btn-info" type="submit" name="down">-</button>
          </form>
        </td>
        <td class="text-center">
          <?php echo $articulo['cantidad'] ?>
        </td>
        <td class="text-center">
            <form action="cart.php" method="post">
              <input type="hidden" name="ident" value="<?= $articulo['id'] ?>">
              <input type="hidden" name="cant" value="<?= $articulo['cantidad'] ?>">
              <button class="btn btn-info" type="submit" name="up">+</button>
            </form>
        </td>
        <td class="text-center">$<?= $articulo['subtotal'] ?></td>
        <td class="text-center">
          <form action="cart.php" method="post">
            <input type="hidden" name="id" value="<?= $articulo['id'] ?>">
            <button class="btn btn-danger" type="submit" name="drop">X</button>
          </form>
        </td>
      </tr>
    <?php
      }
    } else {
    ?>
      <tr>
        <td colspan="8">No hay productos en el carrito</td>
      </tr>
    <?php
    }
  } else {
    ?>
      <tr>
        <td colspan="8">No hay productos en el carrito</td>
      </tr>
    <?php  }   ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5">Total: </td>
        <td colspan="2">$<?= $_SESSION['totalcart']?></td>
      </tr>
    </tfoot>
  </table>
  <br>
  <?php
    if(isset($_SESSION['cart'])){
      if(count($_SESSION['cart']) > 0){
        if(isset($_SESSION['iduser'])){
  ?>
  <form action="cart.php" method="post">
    <input type="hidden" name="correo" value="<?= $_SESSION['username'] ?>">
    <button type="submit" class="btn btn-success" name="comprar">Comprar estos artículos</button>
  </form>
  <?php
        } else {
  ?>
  <button type="button" class="btn btn-success" onclick="gotologin()">Comprar estos artículos</button>

  <div class='modal' tabindex='-1' role='dialog' id='myModal'>
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
            Primero tiene que ingresar al sistema. Puede registrarse si no tiene cuenta o ingrese si ya tiene una cuenta creada por usted o por nuestros asesores.
          </p>
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-primary' data-dismiss='modal' onclick="location.href = 'login.php'">Ir</button>
        </div>
      </div>
    </div>
  </div>
  <?php
        }
      }
    }
  ?>
  <br>
  <?php require "../php/footer.php" ?>
</body>

</html>
