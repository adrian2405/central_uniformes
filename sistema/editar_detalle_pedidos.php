<?php

session_start();

if ($_SESSION['rol'] != 1 && $_SESSION['rol'] != 3) {

    header("location: ./");
}


include "../conexion.php";



if (!empty($_REQUEST)) {

    $alert = '';

    if (empty($_REQUEST['pedido'])) {
    } else {

        $idDetalle = $_GET['id'];
        $pedidos = $_POST['pedido'];
        $producto = $_POST['producto'];
        $proveedor = $_POST['proveedor'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        $total = $_POST['total'];

        $query = mysqli_query($conection, "SELECT * FROM detallespedidos
                                            WHERE idDetalle = $idDetalle");

        $result_detalle = mysqli_num_rows($query);


        if ($result_detalle > 0) {


            while ($data = mysqli_fetch_array($query)) {

                //se recuperan el id del prodcuto y proveedor para poder almacenar
                $idProducto = $data['idProducto'];
                $idProveedor = $data['idProveedor'];
                $totalAnterior = $data['precioTotal'];
            }


            $sql_update = mysqli_query($conection, "UPDATE detallespedidos
                                                    SET idPedido='$pedidos', idProducto='$idProducto', 
                                                    idProveedor='$idProveedor', cantidad='$cantidad', precio='$precio',precioTotal='$total'                                                                       
                                                    WHERE idDetalle = $idDetalle");

            if ($sql_update) {

                //si el detalle se actualiza correctamente hay que pasar el total al pedido
                $query_pedidos = mysqli_query($conection, "SELECT * FROM pedidos
                                                WHERE id_pedido = $pedidos");

                $result_pedido = mysqli_num_rows($query_pedidos);


                if ($result_pedido > 0) {

                    while ($data = mysqli_fetch_array($query_pedidos)) {

                        //se recuperan el id del prodcuto y proveedor para poder almacenar
                        $idCliente = $data['id_cliente'];
                        $idUsuario = $data['id_usuario'];
                        $fechaR = $data['fecha_registro'];
                        $fechaE = $data['fecha_entrega'];
                        $dirEntrega = $data['dir_entrega'];
                        $observacion = $data['observacion'];
                        $entrega = $data['entrega'];
                        $precioTotal = $data['precio_total'];

                        $precioTotal = ($precioTotal - $totalAnterior) + $total;

                        $estado = $data['estado'];

                    }
                    $sql_update_pedidos = mysqli_query($conection, "UPDATE pedidos
                                                            SET id_cliente='$idCliente', id_usuario='$idUsuario', 
                                                            fecha_registro='$fechaR', fecha_entrega='$fechaE', 
                                                            dir_entrega='$dirEntrega',observacion='$observacion',
                                                            entrega='$entrega',entrega='$entrega',precio_total='$precioTotal',
                                                            estado='$estado'                                                                       
                                                            WHERE id_pedido = $pedidos");

                    if ($sql_update_pedidos) {

                        header('location: detalles_pedidos.php');
                    } else {
                        $alert = '<div class="alert alert-danger" role="alert">
        Error al actualizar los detalles.
    </div>';
                    }
                }
            }
        }
    }
}

if (empty($_REQUEST['id'])) {
}

$id_detalle = $_REQUEST['id'];


$query_pedido = mysqli_query($conection, "SELECT d.idDetalle,d.idPedido,d.idProducto,
                                d.idProveedor,d.cantidad,d.precio,d.precioTotal,p.DESCRIPCION,pr.proveedor
                                FROM detallespedidos d
                                INNER JOIN historial_productos p
                                ON d.idProducto=p.ID
                                INNER JOIN proveedores pr
                                ON d.idProveedor=pr.idproveedor
                                WHERE idDetalle=$id_detalle");



$result_pedido = mysqli_num_rows($query_pedido);

if ($result_pedido == 0) {
} else {

    while ($data = mysqli_fetch_array($query_pedido)) {

        $pedido = $data['idPedido'];
        $idProducto = $data['idProducto'];
        $producto = $data['DESCRIPCION'];
        $proveedor = $data['proveedor'];
        $idProveedor = $data['idProveedor'];
        $cantidad = $data['cantidad'];
        $precio = $data['precio'];
        $total = $data['precioTotal'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <?php include "includes/script.php"; ?>

    <title>Actualizar Detalle</title>

</head>

<body class="text-center bg-light">
    <?php include "includes/header.php"; ?>
    <br><br><br>
    <section id="container">

        <div class="form_register">

            <br>

            <h1>Actualizar Detalle</h1>

            <hr><br>

            <form action="" method="post" style="padding:10px; border-radius: 15px">

                <label for="Pedido">Pedido</label>
                <input type="text" name="pedido" readonly="true" id="pedido" placeholder="Pedido" value="<?php echo $pedido; ?>">

                <label for="Producto">Producto</label>
                <input type="text" name="producto" readonly="true" id="producto" placeholder="producto" value="<?php echo $producto; ?>">

                <label for="Proveedor">Proveedor</label>
                <input type="text" name="proveedor" readonly="true" id="proveedor" placeholder="proveedor" value="<?php echo $proveedor; ?>">

                <label for="Cantidad">Cantidad</label>
                <input type="text" name="cantidad" id="cantidad" placeholder="cantidad" value="<?php echo $cantidad; ?>">

                <label for="Precio">Precio</label>
                <input type="text" name="precio" readonly="true" id="precio" placeholder="precio" value="<?php echo $precio; ?>">

                <label for="Total">Total</label>
                <input type="text" name="total" readonly="true" id="total" placeholder="Total" value="<?php echo $total; ?>" value="<?php echo $total; ?>">

                <input type="submit" value="Actualizar Detalle" class="btn_save" name="actualizar" id="actualizar">
                <?php echo isset($alert) ? $alert : ''; ?>
            </form>

            <script type="text/javascript">
                document.getElementById('cantidad').onkeyup = function() {

                    cantidad = document.getElementById('cantidad').value;

                    entero = /^\d*$/;


                    if (cantidad > 0 && cantidad.length < 5 && entero.test(cantidad) == true) {

                        precio = document.getElementById('precio').value;

                        total = cantidad * precio;

                        document.getElementById('total').value = total;

                        document.getElementById('actualizar').style.background = '#12a4c6';

                        document.getElementById('actualizar').disabled = false;


                    } else {

                        if (isNaN("cantidad")) {
                            document.getElementById('cantidad').value = "";
                        }

                        document.getElementById('actualizar').style.background = "red";

                        document.getElementById('actualizar').disabled = true;


                    }

                }
            </script>


        </div>

    </section>

    <?php include "includes/footer.php" ?>

</body>

</html>