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
    
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/popper.min.js"></script>
</head>

<?php

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
  if(isset($_POST['buy'])){

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
  <table class="table table-bordered">
    <thead class="thead-dark">
      <tr>
        <th scope="col" class="text-center">Imagen</th>
        <th scope="col" class="text-center">Precio</th>
        <th scope="col" colspan="3" class="text-center">Cantidad</th>
        <th scope="col" class="text-center">Subtotal</th>
        <th scope="col" class="text-center">Quitar</th>
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
              <form action="cart.php" method="post">
                <input type="hidden" name="ident" value="<?= $articulo['id'] ?>">
                <input type="hidden" name="cant" value="<?= $articulo['cantidad'] ?>">
                <button class="btn btn-info" type="submit" name="up">+</button>
              </form>
            </td>
            <td class="text-center">
              <?php echo $articulo['cantidad'] ?>
            </td>
            <td class="text-center">
              <form action="cart.php" method="post" >
                <input type="hidden" name="ident" value="<?= $articulo['id'] ?>">
                <input type="hidden" name="cant" value="<?= $articulo['cantidad'] ?>">
                <button class="btn btn-info" type="submit" name="down">-</button>
              </form>
            </td>
        <td class="text-center">$<?= $articulo['subtotal'] ?></td>
        <td class="text-center">
          <form action="cart.php" method="post">
            <input type="hidden" name="id" value="<?= $articulo['id'] ?>">
            <button class="btn btn-danger" type="submit" name="drop">Quitar</button>
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
  <?php require "../php/footer.php" ?>
</body>

</html>
