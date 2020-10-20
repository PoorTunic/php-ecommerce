<?php

require_once "database.php";

function get_random_id()
{
    $conn = connect_db();
    $qry = "SELECT id_producto FROM t_producto";
    $result = mysqli_query($conn, $qry);
    $rows = mysqli_num_rows($result);
    if ($rows > 0) {
        $indexes = array();
        while ($row = $result->fetch_assoc()) {
            array_push($indexes, $row['id_producto']);
        }
        return intval($indexes[array_rand($indexes)]);
    } else {
        return false;
    }
}

function get_product_by_id($id)
{
    $conn = connect_db();
    $qry = "SELECT pro.id_producto, pro.producto, pro.preven, pro.descripcion, pro.imagen, cat.id_categoria, cat.categoria FROM t_producto pro NATURAL JOIN t_categoria cat WHERE pro.id_producto=$id";
    $result = mysqli_query($conn, $qry);
    $rows = mysqli_num_rows($result);
    if ($rows > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}
