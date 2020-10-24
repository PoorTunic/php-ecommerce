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
            <div class="card col-xs-3 col-md-3">
                <img class="card-img-top" src="https://www.teknofilo.com/wp-content/uploads/2020/05/iPhone-12.jpg" alt="Card image cap">
                <div class="card-body">
                    <h3 class="card-title no-right-margin" style="font-size: 20px;"><a class="text-dark" href="./pages/product.php?product_id=<?= $results->data[$i]["id_producto"] ?>"><?php echo $results->data[$i]['producto']; ?></a></h3>
                    <p class="card-text text-muted" style="font-size: 13px;">TVS</p>
                    <p style="color: #FF9600; font-size: 18px; font-style: italic; font-weight: bold;" class="card-text">&dollar;<?php echo $results->data[$i]['preven']; ?></p>
                </div>
            </div>
        <?php endfor; ?>
    </div>

    <?php echo $Paginator->createLinks('pagination pagination-sm'); ?>


    <?php require "php/footer.php" ?>

</body>

</html>