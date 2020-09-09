<?php

session_start();

include "../conexion.php";

foreach ($_SESSION['arra'] as $key => $value) {

    if ($key != 'total') {

        unset($_SESSION['arra'][$key]);
    } elseif ($key == 'total') {


        $_SESSION['arra']['total'] = 0.00;
    }
}

header('Location: confirmar_pedido.php');
