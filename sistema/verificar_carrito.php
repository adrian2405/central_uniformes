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

$sinCantidad = FALSE;


if (count($_SESSION['arra']) > 1) {

    foreach ($_SESSION['arra'] as $key => $value) {

        if ($key != 'total') {

            if ($_SESSION['arra'][$key]['cantidad'] == 0) {

                $sinCantidad = TRUE;
            }
        }
    }

    if ($sinCantidad) {

        header('Location: confirmar_pedido.php?verificar=cero');
    } elseif (!$sinCantidad) {

        header('Location: datos_cliente_compra.php');
    }
} else {

    header('Location: confirmar_pedido.php?verificar=vacio');
}