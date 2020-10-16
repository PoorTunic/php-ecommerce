<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>

    <?php require "php/cdn.php" ?>
</head>

<body>
    <?php require "php/header.php" ?>

    <?php
    require_once "php/pagination.php";
    require "php/database.php";

    $conn = connect_db();

    $page = (isset($_GET['page'])) ? $_GET['page'] : 1;

    $query = "SELECT id_producto, producto, preven, descripcion, imagen, id_categoria, categoria FROM t_producto NATURAL JOIN t_categoria";

    $Paginator = new Paginator($conn, $query);

    $results = $Paginator->getData($page);

    echo $Paginator->getQuery() . "<br>";
    ?>

    <?php for ($i = 1; $i < count($results->data); $i++) : ?>
        <tr>
            <td><?php echo $results->data[$i]['producto']; ?></td> <br>
        </tr>
    <?php endfor; ?>

    <?php echo $Paginator->createLinks('pagination pagination-sm'); ?>

    <?php require "php/footer.php" ?>
</body>

</html>