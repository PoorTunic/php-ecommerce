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
    <link rel="stylesheet" href="css/style.css">

    <?php require "php/cdn.php" ?>
</head>

<body class="container">
    <?php require "php/header.php" ?>

    <br>
    <h2>Bienvenido a nuestra tienda</h2>
    <br>
    <div class="card-deck">
        <?php
        require_once "php/pagination.php";
        require "php/database.php";

        $conn = connect_db();

        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;

        $query = "SELECT id_producto, producto, preven, descripcion, imagen, id_categoria, categoria FROM t_productos NATURAL JOIN t_categorias";

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
    <h3>Impresoras</h3>
    <br>
    <div class="card-group">
      <div class="card bg-warning col-xs-4 col-md-4">
          <div class="card-body">
              <h3 class="card-title no-right-margin"><a class="text-dark">¿Buscas impresora?</a></h3>
              <br>
              <p class="card-text text-dark text-justify" style="font-size: 24px;">Encuenta la impresora que quieres al mejor precio, sólo en TechStore.</p>
          </div>
      </div>
    <?php
      $conn = connect_db();
      $impr = mysqli_query($conn, "SELECT id_producto, producto, preven, descripcion, imagen, categoria FROM t_productos NATURAL JOIN t_categorias WHERE categoria = 'Impresoras'");
      while($row = mysqli_fetch_assoc($impr)){
    ?>
      <div class="card col-xs-4 col-md-4">
          <img class="card-img-top" src="<?= $row['imagen'] ?>" alt="Card image cap" style="max-height: 200px;">
          <div class="card-body">
              <h3 class="card-title no-right-margin" style="font-size: 20px;"><a class="text-dark" href="./pages/product.php?id=<?= $row['id_producto']?>"><?php echo $row['producto'] ?></a></h3>
              <p class="card-text text-muted" style="font-size: 13px;"><?= $row['categoria'] ?></p>
              <p style="color: #FF9600; font-size: 18px; font-style: italic; font-weight: bold;" class="card-text">$<?php echo $row['preven']; ?></p>
          </div>
      </div>
  <?php } ?>

  </div>
  <br>
  <br>
    <?php require "php/footer.php" ?>

</body>

</html>
