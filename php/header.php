<script>
    "user strict";

    function go(route) {
        let host = `http://${window.location.hostname}`;

        if (host == "http://localhost") {
            host = host + "/web-app-project";
        }

        if (route == "\/") {
            window.location = host;
            return;
        }

        window.location = `${host}/pages${route}.php`;
        return;
    }
</script>
<header style="background: #154a87 url(https://themes.laborator.co/aurum/tech/wp-content/uploads/2014/11/map.png) no-repeat 5% 50% !important;">
    <nav class="navbar flex-row container" >


    </nav>
    <nav class="navbar navbar-expand-lg navbar-light text-white">
      <a class="navbar-brand" onclick="go('/')">
          <img src="https://themes.laborator.co/aurum/tech/wp-content/uploads/2016/04/techstore.png" alt="not found" style="display: block; max-width: 100%; height: 60px;">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-between" id="navbarNavAltMarkup">
        <div class="navbar-nav">

          <a class="nav-item nav-link btn-outline-warning text-white" onclick="go('/');"> <em><u>INICIO</u></em></a>
          <a class="nav-item nav-link btn-outline-warning text-white" onclick="go('/product');"><em>PRODUCTOS</em></a>

          <?php
          if (isset($_SESSION["username"])) {

            if($_SESSION["username"] != "guest"){
          ?>

          <a class="nav-item nav-link btn-outline-warning text-white" href="php/logout.php?exit=true">SALIR</a>
          <?php
          } else { ?>
            <a class="nav-item nav-link btn-outline-warning text-white" onclick="go('/login');"><em>INGRESAR</em></a>
          <?php
            }
          } else {
          $_SESSION["username"] = "guest";
          ?>
            <a class="nav-item nav-link btn-outline-warning text-white" onclick="go('/login');"><em>INGRESAR</em></a>
          <?php
          }
          ?>

        </div>
        <form class="form-inline" action="../index.php" method="post">
          <input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Search" id="dato" name="dato" required>
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit" id="buscar" name="buscar">Buscar</button>
        </form>
        <button type="button" class="btn btn-outline-ligth" onclick="go('/cart');">
            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-cart4" fill="white" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2h3V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2h3V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z" />
            </svg>
        </button>
      </div>
    </nav>
</header>
