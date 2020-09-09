<nav>

	<?php

	echo "<ul class='nav justify-content-center' style='background: #ABABAB;'>";

	//ADMINISTRADOR
	if ($_SESSION['rol'] == 1) {

		echo "<li class='nav-item'><a class='nav-link' href='index.php'>Inicio</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_usuario.php'>Usuarios</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_cliente.php'>Clientes</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_proveedor.php'>Proveedores</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_producto.php'>Productos</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_pedidos_proveedores.php'>Comercial</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_pedidos.php'>Pedidos</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_historial.php'>Historial</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='confirmar_pedido.php'><img src='img/cart-icon.png'></img></a></li>";
	}

	//VENDEDOR
	elseif ($_SESSION['rol'] == 2) {

		echo "<li class='nav-item'><a class='nav-link' href='index.php'>Inicio</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_cliente.php'>Clientes</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_producto.php'>Productos</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_pedidos.php'>Pedidos</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='confirmar_pedido.php'><img src='img/cart-icon.png'></img></a></li>";
	}

	//COMERCIAL
	elseif ($_SESSION['rol'] == 3) {

		echo "<li class='nav-item'><a class='nav-link' href='index.php'>Inicio</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_proveedor.php'>Proveedores</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_producto.php'>Productos</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_pedidos_proveedores.php'>Comercial</a></li>";
		echo "<li class='nav-item'><a class='nav-link' href='lista_historial.php'>Historial</a></li>";
	}

	echo "</ul>"

	?>

</nav>