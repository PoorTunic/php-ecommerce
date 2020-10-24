<?php

require_once "../../php/database.php";

if (isset($_GET["estado"])) {
    $id_estado = intval($_GET["estado"]);

    $conn = connect_db();
    $qry = "SELECT id_municipio, municipio FROM t_municipios WHERE id_estado=$id_estado";
    $result = mysqli_query($conn, $qry);
    $rows = mysqli_num_rows($result);
    if ($rows > 0) {
        $html = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $html = $html . "<option value='" . $row['id_municipio'] . "'>" . $row['municipio'] . "</option>";
        }
        echo $html;
    } else {
        echo "<option>Error</option>";
    }
}

if (isset($_GET["municipio"])) {
    $id_municipio = intval($_GET["municipio"]);

    $conn = connect_db();
    $qry = "SELECT id_colonia, colonia FROM t_colonias WHERE id_municipio=$id_municipio";
    $result = mysqli_query($conn, $qry);
    $rows = mysqli_num_rows($result);
    if ($rows > 0) {
        $html = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $html = $html . "<option value='" . $row['id_colonia'] . "'>" . $row['colonia'] . "</option>";
        }
        echo $html;
    } else {
        echo "<option>Error</option>";
    }
}
