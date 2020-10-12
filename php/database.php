<?php
function connect_db()
{
    $conn = mysqli_connect("localhost:3306", "root", "", "web_project");
    if (!$conn) {
        echo "Error connecting to DB";
        exit;
    }
    return $conn;
}

function login($conn, $username, $password)
{
    $qry = "SELECT * FROM t_usuarios WHERE correo='$username' AND pass='$password';";
    $result = mysqli_query($conn, $qry);
    $rows = mysqli_num_rows($result);
    if ($rows > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}

function getAll($conn)
{
    $res = mysqli_query($conn, "SELECT * FROM t_usuarios WHERE correo='daniel.clementea97@gmail.com' AND pass='Qwe123$$'");
    $assoc = mysqli_fetch_assoc($res);
    echo mysqli_num_rows($res);
    return $assoc;
}
