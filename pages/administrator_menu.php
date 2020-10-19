<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'initial-page' ? 'active' : '' ?>" href="?content=initial-page">Inicio</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'administrator' ? 'active' : '' ?>" href="?content=administrator">Administradores</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'client' ? 'active' : '' ?>" href="?content=client">Clientes</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'category' ? 'active' : '' ?>" href="?content=category">Categorías</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'provider' ? 'active' : '' ?>" href="?content=provider">Proveedores</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'product' ? 'active' : '' ?>" href="?content=product">Productos</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'purchase' ? 'active' : '' ?>" href="?content=purchase">Compras</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'slider' ? 'active' : '' ?>" href="?content=slider">Slider</a>
  </li>
  <li class="nav-item">
    <form action="../php/logout.php" method="GET">
      <button class="nav-link" name="exit" type="submit" value="logout">Salir</button>
    </form>
  </li>
</ul>