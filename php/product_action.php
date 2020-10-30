<?php

require_once "database.php";

function get_random_id()
{
    $conn = connect_db();
    $qry = "SELECT id_producto FROM t_productos";
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
    $qry = "SELECT * FROM t_productos NATURAL JOIN t_categorias WHERE id_producto=$id";
    $result = mysqli_query($conn, $qry);
    $rows = mysqli_num_rows($result);
    if ($rows > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}
