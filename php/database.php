<?php
if (!function_exists("connect_db")) {
    function connect_db()
    {
        //Remote
        //$conn = mysqli_connect("sql203.epizy.com", "epiz_26945682", "8sSXrvIeafh", "epiz_26945682_web_project");
        //Local
        $conn = mysqli_connect("localhost:3306", "root", "", "web_project");
        if (!$conn) {
            echo "Error connecting to DB";
            exit;
        }
        return $conn;
    }
}

if (!function_exists("login")) {
    function login($conn, $username, $password)
    {
        $qry = "SELECT * FROM t_usuarios WHERE correo='$username' AND contrapass='$password';";
        $result = mysqli_query($conn, $qry);
        $rows = mysqli_num_rows($result);
        if ($rows > 0) {
            return mysqli_fetch_assoc($result);
        } else {
            return false;
        }
    }
}
