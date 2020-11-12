<?php
session_start();
require "php/auth.php";
recognize();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>

    <?php require "php/cdn.php" ?>
</head>

<body class="container">
    <?php require "php/header.php" ?>
    <?php
    require "php/database.php";

    $conn = connect_db();
    ?>

    <br>
    <h2>Bienvenido a nuestra tienda</h2>
    <br>
    <?php
    if(isset($_POST['buscar'])){
      $dato = $_POST['dato'];
    ?>
    <div class="card-group">
    <?php
        $busq = mysqli_query($conn, "SELECT id_producto, producto, preven, descripcion, imagen, categoria FROM t_productos NATURAL JOIN t_categorias WHERE producto LIKE '%".$dato."%'");
        while($row = mysqli_fetch_assoc($busq)){
    ?>
        <div class="col-sm-3">
          <div class="card">
              <img class="card-img-top" src="<?= $row['imagen'] ?>" alt="Card image cap" style="max-height: 200px;">
              <div class="card-body">
                  <h3 class="card-title no-right-margin" style="font-size: 20px;"><a class="text-dark" href="./pages/product.php?id=<?= $row['id_producto']?>"><?php echo $row['producto'] ?></a></h3>
                  <p class="card-text text-muted" style="font-size: 13px;"><?= $row['categoria'] ?></p>
                  <p style="color: #FF9600; font-size: 18px; font-style: italic; font-weight: bold;" class="card-text">$<?php echo $row['preven']; ?></p>
              </div>
          </div>
        </div>
    <?php
          }
    ?>
    </div>

    <?php
    } else {
    ?>
    <br>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      <div class="container">
        <ol class="carousel-indicators">
          <?php
            $conn = connect_db();
            $counter = 0;
            $indicators = mysqli_query($conn, "SELECT id_producto, imagen FROM t_imagenes WHERE status = 1 and tipo = 2 ORDER by imagen DESC ");
            while($row = mysqli_fetch_assoc($indicators)){
              if($counter == 0){
          ?>
            <li data-target="#carouselExampleIndicators" data-slide-to="<?=$counter?>" class="active"></li>
          <?php
              } else {
          ?>
            <li data-target="#carouselExampleIndicators" data-slide-to="<?=$counter?>"></li>
          <?php
              }
              $counter += 1;
            }
          ?>
        </ol>

        <div class="carousel-inner">
          <?php
            $conn = connect_db();
            $counter = 0;
            $images = mysqli_query($conn, "SELECT id_producto, imagen FROM t_imagenes WHERE status = 1 and tipo = 2 ORDER by imagen DESC ");
            while($row = mysqli_fetch_assoc($images)){
              if($counter == 0){
          ?>
          <div class="carousel-item active">
            <img class="d-block w-100" src="<?= $row['imagen']?>" style="max-height: 800px;" onclick="location.href='pages/product.php?id=<?= $row['id_producto']?>'">
          </div>
          <?php
              } else {
          ?>
          <div class="carousel-item">
            <img class="d-block w-100" src="<?= $row['imagen']?>" style="max-height: 800px;" onclick="location.href='pages/product.php?id=<?= $row['id_producto']?>'">
          </div>
          <?php
              }
              $counter += 1;
            }
          ?>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>

    <div class="card-deck">
        <?php
        require_once "php/pagination.php";


        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $categoria = "";

        if(isset($_GET['categoria']))
          $categoria = " WHERE categoria = '".$_GET['categoria']."'";


        $query = "SELECT id_producto, producto, preven, descripcion, imagen, id_categoria, categoria FROM t_productos NATURAL JOIN t_categorias".$categoria;

        $Paginator = new Paginator($conn, $query);

        $results = $Paginator->getData($page);

        ?>

        <?php for ($i = 1; $i < count($results->data); $i++) : ?>
            <div class="card col-xs-4 col-md-4">
                <img class="card-img-top" src="<?= $results->data[$i]['imagen'] ?>" alt="Card image cap">
                <div class="card-body">
                    <h3 class="card-title no-right-margin" style="font-size: 20px;"><a class="text-dark" href="./pages/product.php?id=<?= $results->data[$i]["id_producto"] ?>"><?php echo $results->data[$i]['producto']; ?></a></h3>
                    <p class="card-text text-muted" style="font-size: 13px;"><?= $results->data[$i]['categoria'] ?></p>
                    <p style="color: #FF9600; font-size: 18px; font-style: italic; font-weight: bold;" class="card-text">&dollar;<?php echo $results->data[$i]['preven']; ?></p>
                </div>
            </div>
        <?php endfor; ?>
    </div>
    <br>
    <div class="d-flex justify-content-center">
        <?php echo $Paginator->createLinks('pagination pagination-sm'); ?>
    </div>
    <br>
    <h3>Tendencias de moda</h3>
    <br>
    <div class="card-group">
    <?php
      $conn = connect_db();
      $prod = mysqli_query($conn, "SELECT id_producto, producto, preven, descripcion, imagen, categoria FROM t_productos NATURAL JOIN  t_categorias WHERE id_producto = 26 or id_producto = 27");
      while($row = mysqli_fetch_assoc($prod)){
    ?>
    <div class="card col-xs-6 col-md-6">
        <img class="card-img-top" src="<?= $row['imagen'] ?>" alt="Card image cap">
        <div class="card-body">
            <h3 class="card-title no-right-margin" style="font-size: 20px;"><a class="text-dark" href="./pages/product.php?id=<?= $row['id_producto']?>"><?php echo $row['producto'] ?></a></h3>
            <p class="card-text text-muted" style="font-size: 13px;"><?= $row['categoria'] ?></p>
            <p style="color: #FF9600; font-size: 18px; font-style: italic; font-weight: bold;" class="card-text">$<?php echo $row['preven']; ?></p>
        </div>
    </div>
    <?php
      }
    ?>
    </div>
    <br>
    <h3>Categorías</h3>
    <br>
    <div class="row">
      <div class="col">
        <div class="list-group">
          <button type="button" class="list-group-item list-group-item-action <?= ($_GET['categoria'] == "ALL" || $_GET['categoria'] == "")? "active" : "" ?>" onclick="location.href = 'index.php'">VER TODO</button>
          <?php
            $conn = connect_db();
            $items = mysqli_query($conn, "SELECT * FROM t_categorias");
            while($resultset = mysqli_fetch_assoc($items)){
          ?>
          <button type="button" class="list-group-item list-group-item-action <?= $resultset['categoria'] == $_GET['categoria']? "active" : "" ?>" onclick="location.href = '?categoria=<?= $resultset['categoria']?>'"><?= $resultset['categoria']?></button>
          <?php
            }
          ?>
        </div>
      </div>
      <div class="card-group col-9">
      <?php
        $conn = connect_db();
        $catcoin = 0;
        $coinc = mysqli_query($conn, "SELECT count(*) as coin, categoria FROM t_productos NATURAL JOIN t_categorias".(isset($_GET['categoria'])? (" WHERE categoria = '".$_GET['categoria']."'") : ""));
        while($rcoin = mysqli_fetch_assoc($coinc)){
          $catcoin = $rcoin['coin'];
        }

        if($catcoin == 0){
    ?>
      <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <p style="color: #FF9600; font-size: 18px; font-style: italic; font-weight: bold;" class="card-text">No hay resultados de esta categoría</p>
            </div>
        </div>
      </div>


    <?php
        } else {
    ?>

    <?php
        $impr = mysqli_query($conn, "SELECT id_producto, producto, preven, descripcion, imagen, categoria FROM t_productos NATURAL JOIN t_categorias".(isset($_GET['categoria'])? (" WHERE categoria = '".$_GET['categoria']."'") : ""));
        while($row = mysqli_fetch_assoc($impr)){
    ?>
        <div class="col-sm-3">
          <div class="card">
              <img class="card-img-top" src="<?= $row['imagen'] ?>" alt="Card image cap" style="max-height: 200px;">
              <div class="card-body">
                  <h3 class="card-title no-right-margin" style="font-size: 20px;"><a class="text-dark" href="./pages/product.php?id=<?= $row['id_producto']?>"><?php echo $row['producto'] ?></a></h3>
                  <p class="card-text text-muted" style="font-size: 13px;"><?= $row['categoria'] ?></p>
                  <p style="color: #FF9600; font-size: 18px; font-style: italic; font-weight: bold;" class="card-text">$<?php echo $row['preven']; ?></p>
              </div>
          </div>
        </div>

    <?php
          }
        }
    ?>
    </div>
  </div>

    <?php } ?>
    <br>
    <br>
    <?php require "php/footer.php" ?>

</body>

</html>
