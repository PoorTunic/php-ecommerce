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

<body>
    <?php require "./../php/header.php" ?>

    <?php

    require_once "../php/product_action.php";

    $id_producto = -1;
    if (isset($_GET["product_id"])) {
        $id_producto = $_GET['product_id'];
    } else {
        $id_producto = get_random_id();
    }
    $result = get_product_by_id($id_producto);

    ?>
    <section class="container">
        <br>
        <br>
        <div class="card">
            <div class="card-img">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <?php
                        // for ($i = 0; $i < sizeof($result[$item]->media); $i++) {
                        ?>
                        <!-- <li data-target="#carouselExampleIndicators" data-slide-to="></li> -->
                        <?php
                        // }
                        ?>
                    </ol>
                    <div class="carousel-inner">
                        <?php
                        // for ($i = 0; $i < sizeof($result[$item]->media); $i++) {
                        ?>
                        <div class="carousel-item <?php if ($i == 0) echo 'active' ?>">
                            <!-- <img class="d-block w-100" src= alt="First slide"> -->
                        </div>
                        <?php
                        // }
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
            <div class="card-body">
                <h5 class="card-title"><?= $result['producto'] ?></h5>
                <p class="card-text"><?= $result['descripcion'] ?></p>
                <p class="card-text">
                    <p style="color: #FF9600; font-size: 18px; font-style: italic; font-weight: bold;" class="card-text">&dollar;<?= $result['preven'] ?></p>
                </p>
                <p class="card-link"><a href="./contact.php?product_id=<?= $result["id_producto"] ?>" class="btn text-primary font-weight-bold">COMPRAR</a></p>
            </div>
        </div>
    </section>
    <br>
    <br>
    <?php require "./../php/footer.php" ?>
</body>

</html>
