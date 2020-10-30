<?php

include_once "database.php";

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST["submit"])) {
    if($_SESSION['realcode'] == $_POST['code']){
      $username = $_POST["username"];
      $password = $_POST["password"];
      if ($username != "" and $password != "") {
          $conn = connect_db();
          $result = login($conn, $username, $password);
          if (is_array($result)) {
              $_SESSION["username"] = $username;
              $_SESSION["logged"] = true;

              $_SESSION["level"] = $result["nevel"];

              if ($result["nevel"] == 1 || $result["nevel"] == 2) {
                  if ($result["nevel"] == 1) {
                      $_SESSION["role"] = "admin";
                  } else if ($result["nevel"] == 2) {
                      $_SESSION["role"] = "manager";
                  }
                  header("Location: ../pages/admin/control.php");
              } else if ($result["nevel"] == 3) {
                  header("Location: ../index.php");
              }
          } else {
              header("Location: ../pages/login.php?err=Usuario/Contraseña incorrectos");
          }
      }
  } else {
    header("Location: ../pages/login.php?err=Captcha incorrecto");
  }
}

if (isset($_POST["reset"])) {
    header("Location: ../pages/restore_password.php");
}

function recognize()
{
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        if($_SESSION['username'] != "guest"){
          if ($_SESSION["level"] == 1 || $_SESSION["level"] == 2) {
              header("Location: pages/admin/control.php");
          }
        }
    }
}
