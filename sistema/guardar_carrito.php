<?php

session_start();

include "../conexion.php";

foreach ($_SESSION['arra'] as $key => $value) {

    if ($key != 'total') {

        $cantidad = $_GET[$key];

        $precio = $_GET['p' . $key];

        $_SESSION['arra'][$key]['cantidad'] = $cantidad;
        $_SESSION['arra'][$key]['precio'] = $precio;
    } elseif ($key == 'total') {

        $total = $_GET['total'];
        $_SESSION['arra']['total'] = $total;
    }
}

header('Location: confirmar_pedido.php');
