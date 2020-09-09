<?php

session_start();

if ($_SESSION['rol'] != 1 && $_SESSION['rol'] != 3) {
    header("location: ./");
}

include "../conexion.php";

if (!empty($_POST)) {

    /*if($_POST['dni']==1){
            header("location: lista_cliente.php");
            mysqli_close($conection);
            exit;
        }*/
    $detalle = $_REQUEST['id'];

    echo "total " . $_SESSION['precioTotal'];

    $query_delete = mysqli_query($conection, "DELETE FROM detallespedidos WHERE idDetalle=$detalle");

    //$query_delete = mysqli_query($conection,"UPDATE usuario SET estatus =0 WHERE idusuario=$idusuario");

    if ($query_delete) {

        //se comprueba que no sea el último detalle si lo es se elimina el pedido tambien
        $idPedido = $_SESSION['pedido'];
        $query_detalle = mysqli_query($conection, "SELECT * FROM detallespedidos
            WHERE idPedido = $idPedido");

        $result_detalle = mysqli_fetch_array($query_detalle);

        //si el pedido sigue manteniendo detalles 
        if ($result_detalle > 0) {

            //se obtienen los datos del pedido
            $query_pedido = mysqli_query($conection, "SELECT * FROM pedidos
                                        WHERE id_pedido = $idPedido");

            $result_pedido = mysqli_num_rows($query_pedido);

            if ($result_pedido > 0) {

                if ($data = mysqli_fetch_array($query_pedido)) {

                    $totalPedido = $data['precio_total'];
                }
            }

            //se realiza el cálculo de la cantidad
            $totalDetalle = $_SESSION['precioTotal'];
            $actualizarPrecio = $totalPedido - $totalDetalle;

            //se actualiza el total en pedidos

            $sql_update = mysqli_query($conection, "UPDATE pedidos
                                                        SET precio_total='$actualizarPrecio'
                                                        WHERE id_pedido = $idPedido");

            if ($sql_update) {
                $alert= '<div class="alert alert-success" role="alert">
    Pedido actualizado correctamente.
</div>';
            } else {
                $alert= '<div class="alert alert-danger" role="alert">
    Error al actualizar detalles.
</div>';
            }

            header("location: detalles_pedidos.php");
        } else {

            //echo "<script>alert('Es el único detalle por tanto se borrará el pedido');</script>";

            $query_delete_pedido = mysqli_query($conection, "DELETE FROM pedidos WHERE id_pedido=$idPedido");
            header("location: lista_pedidos.php");
        }
    } else {
        $alert= '<div class="alert alert-danger" role="alert">
    Error al eliminar detalles.
</div>';
    }
    //header("location: detalles_pedidos.php");
}

// if(empty($_REQUEST['id']) || $_REQUEST['id'] == 1){
//mysqli_close($conection);   
//header("location: detalles_pedidos.php");

// }
//  else{

$detalle = $_REQUEST['id'];

$query = mysqli_query($conection, "SELECT * FROM detallespedidos 
                                                 WHERE idDetalle=$detalle ");

mysqli_close($conection);

$result = mysqli_num_rows($query);

if ($result > 0) {

    while ($data = mysqli_fetch_array($query)) {
        $idDetalle = $data['idDetalle'];
        $pedido = $data['idPedido'];
        $producto = $data['idProducto'];
        $proveedor = $data['idProveedor'];
        $cantidad = $data['cantidad'];
        $precio = $data['precio'];
        $_SESSION['precioTotal'] = $data['precioTotal'];
    }
} else {

    header("location: detalles_pedidos.php");
}

// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/script.php"; ?>
    <title>Eliminar detalle</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="data_delete">
        <br>
            <br>
            <br>
            <br>
            <h1>¿Está seguro de eliminar el siguiente registro?</h1>
            <br>
            <p>Id Detalle: <span><?php echo $idDetalle; ?></span></p>
            <p>Pedido: <span><?php echo $pedido; ?></span></p>
            <p>Producto: <span><?php echo $producto; ?></span></p>
            <p>Proveedor: <span><?php echo $proveedor; ?></span></p>
            <p>Cantidad: <span><?php echo $cantidad; ?></span></p>
            <p>Precio: <span><?php echo $precio; ?></span></p>
            <p>Precio Total: <span><?php echo $_SESSION['precioTotal']; ?></span></p>


            <form method="post" action="">
                <input type="hidden" name="pedido" value="<?php echo $pedido; ?>">
                <a href="detalles_pedidos.php" class="btn btn-light">Cancelar</a>
                <input type="submit" value="Eliminar" class="btn btn-danger" style="width: 100px; margin-left: 5px;">
                <?php echo isset($alert) ? $alert : ''; ?>
            </form>

        </div>
    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>