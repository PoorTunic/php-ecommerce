<?php
if (!function_exists("connect_db")) {
    function connect_db()
    {
        //Remote
        //$conn = mysqli_connect("sql203.epizy.com", "epiz_26945682", "8sSXrvIeafh", "epiz_26945682_web_project_completed");
        //$conn = mysqli_connect("localhost", "id15237595_root", "VaML991219!!", "id15237595_web_project_completed");
        //Local
        $conn = mysqli_connect("localhost:3306", "root", "", "web_project");
        if ($conn->set_charset("utf8")){
          if (!$conn) {
              echo "Error connecting to DB";
              exit;
          }
        } else {
          echo "No utf8";
        }

        return $conn;
    }
}

if (!function_exists("login")) {
    function login($conn, $username, $password)
    {
        $qry = "SELECT * FROM t_usuarios WHERE correo='$username' AND contrapass='$password'";
        $result = mysqli_query($conn, $qry);
        $rows = mysqli_num_rows($result);
        if ($rows > 0) {
            return mysqli_fetch_assoc($result);
        } else {
            return false;
        }
    }
}
