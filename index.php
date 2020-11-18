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
    <link rel="icon" href="favicon.ico">
    <title>Inicio</title>

    <?php require "php/cdn.php" ?>
</head>

<body class="container">
    <script>
        "user strict";

        function go(route) {
            let host = `http://${window.location.hostname}`;

            if (host == "http://localhost") {
                host = host + "/web-app-project";
            }

            if (route == "\/") {
                window.location = host;
                return;
            }

            window.location = `${host}/pages${route}.php`;
            return;
        }
    </script>
    <header style="background: #154a87 url(https://themes.laborator.co/aurum/tech/wp-content/uploads/2014/11/map.png) no-repeat 5% 50% !important;">
        <nav class="navbar flex-row container" >


        </nav>
        <nav class="navbar navbar-expand-lg navbar-light text-white">
          <a class="navbar-brand" onclick="go('/')">
              <img src="https://themes.laborator.co/aurum/tech/wp-content/uploads/2016/04/techstore.png" alt="not found" style="display: block; max-width: 100%; height: 60px;">
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-between" id="navbarNavAltMarkup">
            <div class="navbar-nav">

              <a class="nav-item nav-link btn-outline-warning text-white" onclick="go('/');"> <em><u>INICIO</u></em></a>
              <a class="nav-item nav-link btn-outline-warning text-white" onclick="go('/product');"><em>PRODUCTOS</em></a>

              <?php
              if (isset($_SESSION["username"])) {

                if($_SESSION["username"] != "guest"){
              ?>

              <a class="nav-item nav-link btn-outline-warning text-white" href="php/logout.php?exit=true">SALIR</a>
              <?php
              } else { ?>
                <a class="nav-item nav-link btn-outline-warning text-white" onclick="go('/login');"><em>INGRESAR</em></a>
              <?php
                }
              } else {
              $_SESSION["username"] = "guest";
              ?>
                <a class="nav-item nav-link btn-outline-warning text-white" onclick="go('/login');"><em>INGRESAR</em></a>
              <?php
              }
              ?>

            </div>
            <form class="form-inline" action="index.php" method="post">
              <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search" id="dato" name="dato" required>
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit" id="buscar" name="buscar">Buscar</button>
            </form>
            <button type="button" class="btn btn-outline-ligth" onclick="go('/cart');">
                <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-cart4" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2h3V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2h3V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z" />
                </svg>
            </button>
          </div>
        </nav>
    </header>
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
