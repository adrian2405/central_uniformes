<?php

session_start();

include "../conexion.php";

foreach ($_SESSION['arra'] as $key2 => $value2) {

    $keyfinal = $key2;

    $key2 = 'b' . $key2;

    if (isset($_GET[$key2])) {

        foreach ($_SESSION['arra'] as $key => $value) {

            if ($key == $keyfinal) {


                $restar1 = (float) $_SESSION['arra'][$key]['precio'];
                $restar2 = (float) $_SESSION['arra']['total'];
                $_SESSION['arra']['total'] = number_format((float) $restar2 - $restar1, 2);

                unset($_SESSION['arra'][$key]);
            }
        }
    }
}

header('Location:confirmar_pedido.php');
