<ul class="nav nav-tabs bg-light">
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'initial-page' ? 'active disabled' : '' ?>" href="?content=initial-page">Inicio</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'administrator' ? 'active disabled' : '' ?>" href="?content=administrator">Administradores</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'client' ? 'active disabled' : '' ?>" href="?content=client">Clientes</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'category' ? 'active disabled' : '' ?>" href="?content=category">Categorías</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'provider' ? 'active disabled' : '' ?>" href="?content=provider">Proveedores</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'product' ? 'active disabled' : '' ?>" href="?content=product">Productos</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'purchase' ? 'active disabled' : '' ?>" href="?content=purchase">Compras</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'storage' ? 'active disabled' : '' ?>" href="?content=storage">Almacén</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'slider' ? 'active disabled' : '' ?>" href="?content=slider">Slider</a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php echo $load == 'logout' ? 'active disabled' : '' ?>" value="logout" href="../../php/logout.php?exit=true">Salir</a>
  </li>
</ul>
