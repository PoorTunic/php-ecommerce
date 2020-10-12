<?php

include "../php/database.php";

session_start();

if (isset($_POST["submit"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];
    if ($username != "" and $password != "") {
        $conn = connect_db();
        $result = login($conn, $username, $password);
        if (is_array($result)) {
            $_SESSION["username"] = $username;
            $_SESSION["logged"] = true;

            $_SESSION["level"] = $result["nivel"];

            if ($result["nivel"] == 1 || $result["nivel"] == 2) {
                header("Location: ../pages/control.php");
            } else if ($result["nivel"] == 3) {
                header("Location: ../index.php");
            }
        } else {
            header("Location: ../pages/login.php?err=Usuario/Contraseña incorrectos");
        }
    }
}
if (isset($_POST["reset"])) {
    header("Location: ../pages/restore_password.php");
}
