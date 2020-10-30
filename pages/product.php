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
    <link rel="stylesheet" href="../css/style.css">
    <link href="../open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">

    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/popper.min.js"></script>
</head>
<script>
  function loadImage(rel){
    pimg = document.getElementById("productImage").getAttribute("src");
    relat =  document.getElementById("rel" + rel).getAttribute("src");

    document.getElementById("rel" + rel).setAttribute("src", pimg);
    document.getElementById("productImage").setAttribute("src", relat);
    }
</script>
<body>
    <?php require "./../php/header.php" ?>

    <?php

    require_once "../php/product_action.php";

    $id_producto = -1;
    if (isset($_GET["id"])) {
        $id_producto = $_GET['id'];
        if(is_numeric($id_producto)){
          $conn = connect_db();
          $coin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) as coin FROM t_productos WHERE id_producto = $id_producto"))['coin'];
          if($coin <= 0){
            echo "<script>location.href = 'product.php'</script>";
          }
        } else {
          echo "<script>location.href = 'product.php'</script>";
        }
    } else {
        $id_producto = get_random_id();
    }
    $result = get_product_by_id($id_producto);
    $id = $result['id_producto'];
    $cat = $result['id_categoria'];
    ?>


    <section class="container">
        <br>
        <?php
          if(isset($_POST['add'])){
            $idpr = $_POST['idprod'];
            $prod = $_POST['prod'];
            $imagen = $_POST['imagen'];
            $precio = $_POST['precio'];

            $producto = array(
              'id' => $idpr,
              'producto' => $prod,
              'imagen' => $imagen,
              'precio' => $precio,
              'cantidad' => 1,
              'subtotal' => $precio
            );

            $pos = 0;
            if(isset($_SESSION['cart'])){
              $coincart = 0;
              foreach ($_SESSION['cart'] as $article => $value) {
                if($value['id'] == $idpr){
                  $coincart += 1;
                }
              }

              if($coincart == 0){
                $pos = count($_SESSION['cart']);
                $_SESSION['cart'][$pos] = $producto;
                print_r("<div id='message' class='alert alert-success container'>
                  Producto agregado al carrito
                </div>");
              } else {
                print_r("<section id='message' class='alert alert-danger container'>
                  Ese producto ya se encuentra en el carrito
                </section>");
              }

            } else {
              $pos = 0;
              $_SESSION['cart'][$pos] = $producto;
              print_r("<section id='message' class='alert alert-success container'>
                Producto agregado al carrito
              </section>");
            }

          }
          //print_r($_SESSION);
        ?>
        <br>
        <div class="card-group">
          <div class="card text-white bg-primary col-12 col-sm-6">
            <div class="card-header">Características del producto</div>
            <div class="card-body">
              <h5 class="card-title"><?= $result['producto'] ?></h5>
              <p class="card-text">
                Precio: $<?= $result['preven'] ?> <br>
                Descripcion: <?= $result['descripcion'] ?>
                <p class="card-link">
                  <form action="product.php?id=<?=$id?>" method="post">
                    <input type="hidden" name="idprod" value="<?=$id?>">
                    <input type="hidden" name="prod" value="<?=$result["producto"]?>">
                    <input type="hidden" name="imagen" value="<?=$result["imagen"]?>">
                    <input type="hidden" name="precio" value="<?=$result['preven']?>">
                    <button type="submit" class="btn btn-outline-light font-weight-bold" name="add">Agregar al carrito</button>
                  </form>
                </p>
                <p class="card-text"><small>Categoría: <?= $result['categoria'] ?></small></p>
              </p>
            </div>
          </div>
          <div class="card border-primary col-12 col-sm-6">
            <div class="card-header">Imagen del producto</div>
            <div class="card-body text-primary">
              <img src="../<?= $result['imagen'] ?>" class="img-fluid" id="productImage">
            </div>
          </div>
        </div>
        <br>
        <h4>Imágenes relacionadas</h4>
        <br>
        <div class="card-deck d-flex justify-content-center">
          <?php
          $conn = connect_db();
          $contadorimg = 0;
          $qryimgrel = "SELECT imagen FROM t_imagenes WHERE id_producto = $id";
          $resultimgrel = mysqli_query($conn, $qryimgrel);
          if(mysqli_num_rows($resultimgrel) > 0){
          while($image=mysqli_fetch_assoc($resultimgrel)){
            $contadorimg += 1;
          ?>
          <div class="card">
            <img class="card-img-top" id="rel<?=$contadorimg?>" src="../<?= $image['imagen']?>" alt="Imagen relacionada" class="img-fluid" onclick="loadImage(<?=$contadorimg?>)">
            <div class="card-footer">
              <small class="text-muted">Imagen relacionada <?=$contadorimg?></small>
            </div>
          </div>
        <?php } }?>
        </div>
        <br>
        <h4>Más productos</h4>

    </section>
    <div id="slider" class="carousel slide carousel-multi-item" data-ride="carousel" style="padding-top: 4%;">
      <div class="carousel-inner" data-interval="3000" style="font-size: 14px">
        <div class="carousel-item active">
          <div class="row">
            <?php
            $contador = 1;
            $limit = 4;
            $conn = connect_db();
            $qry = "SELECT id_producto, producto, preven, imagen, categoria FROM t_productos NATURAL JOIN t_categorias WHERE id_producto != $id ORDER BY rand()";
            $result = mysqli_query($conn, $qry);
            $qty = mysqli_num_rows($result);
            while($row=mysqli_fetch_assoc($result)){
              if($contador % $limit == 0){
            ?>
            </div>
          </div>
          <div class="carousel-item">
            <div class="row">
            <?php
              } else {
            ?>
              <div class="col-4 text-center">
                <img src="../<?= $row['imagen'] ?>" class="img-fluid" style="height: 100px;">
                <p>
                  <small><b><a style="color:#BAB9B8;" href="product.php?id=<?=$row['id_producto'] ?>"><?= $row['producto'] ?></a></b><br></small>
                  <small><b>$<?= $row['preven'] ?></b><br></small>
                  <small><span style="color: blue"><?= $row['categoria'] ?></span></small>
                </p>
              </div>
            <?php
              }
              $contador += 1;
            }
            ?>
            </div>
          </div>
        </div>

        <a class="carousel-control-prev" href="#slider" role="button" data-slide="prev" style="color:gray">
            <span class="oi oi-chevron-left" style="color:gray"></span>
            <span class="sr-only" style="color:gray">Previous</span>
        </a>
        <a class="carousel-control-next" href="#slider" role="button" data-slide="next" style="color:gray">
            <span class="oi oi-chevron-right" style="color:gray"></span>
            <span class="sr-only" style="color:gray">Next</span>
        </a>
      </div>
      <br><br>
      <?php require "./../php/footer.php" ?>
</body>

</html>
