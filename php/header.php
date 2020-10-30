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
<header class="header-custom">
    <nav class="navbar navbar-expand-md flex-row container">
        <a class="navbar-brand mr-auto" onclick="go('/')">
            <img src="https://themes.laborator.co/aurum/tech/wp-content/uploads/2016/04/techstore.png" class="img-fluid" alt="not found">
        </a>
        <button type="button" class="btn btn-outline-ligth" onclick="go('/cart');">
            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-cart4" fill="white" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2h3V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2h3V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z" />
            </svg>
        </button>
    </nav>
    <nav class="navbar p-0 navbar-expand-md container">
        <div class="collapse navbar-collapse px-3" id="navbarContent">
            <ul class="navbar-nav mx-0 w-50 align-items-start nav-fill">
                <li class="nav-item">
                    <a class="nav-link btn-outline-warning text-white" onclick="go('/');"> <em><u>INICIO</u></em></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-outline-warning text-white" onclick="go('/product');"><em>PRODUCTOS</em></a>
                </li>
                <?php
                if (isset($_SESSION["username"])) {

                  if($_SESSION["username"] != "guest"){
                ?>

                    <li class="nav-item">
                        <a class="nav-link btn-outline-warning text-white" onclick="go('/cart');"><em>CARRITO</em></a>
                    </li>
                    <li class="nav-item">
                        <form action="php/logout.php" method="GET">
                            <button class="nav-link btn-outline-warning text-white" name="exit" value="logout" type="submit">SALIR</a>
                        </form>
                    </li>
                <?php
              } else { ?>
                <li class="nav-item">
                    <a class="nav-link btn-outline-warning text-white" onclick="go('/login');"><em>INGRESAR</em></a>
                </li>
                <?php
                }
              } else {
                $_SESSION["username"] = "guest";
                ?>

                    <li class="nav-item">
                        <a class="nav-link btn-outline-warning text-white" onclick="go('/login');"><em>INGRESAR</em></a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </nav>
</header>
