<?php

session_start();



include "../conexion.php";

$dni = "";
$nombreClie = "";
$correo = "";
$telefono = "";
$isla = "";
$dirEntrega  = "";
$observacion = "";
$entrega = "";
$estado = "";
$sumaTotal = "";

//contador registros array
$cont = 0;

//fecha del sistema
$fechaActual = date('Y-m-d');


//consulta para comprobar si hay pedidos
$query = mysqli_query($conection, "SELECT * FROM pedidos ");


$result = mysqli_num_rows($query);


if ($result > 0) {


    //realizar ultimo id y se suma uno al último
    $pedido = mysqli_query($conection, "SELECT MAX(id_pedido) AS id FROM pedidos");

    if ($row = mysqli_fetch_row($pedido)) {

        $idPedido = trim($row[0]);
    }

    $idPedido++;
    $usuario = $_SESSION['idUser'];
} else {

    $idPedido = 1;
    $usuario = $_SESSION['idUser'];
}



//consulta para mostrar el nombre del usuario(tienda) que realiza la compra
$query_user = mysqli_query($conection, "SELECT *
                                    FROM usuario 
                                    WHERE idusuario='$usuario'");



$result_user = mysqli_num_rows($query_user);

if ($result_user == 0) {
} else {

    $option = '';

    while ($data = mysqli_fetch_array($query_user)) {

        $nombre = $data['nombre'];
    }
}




//se busca al cliente y se almacenan los datos
if (isset($_POST['buscar'])) {

    $dni = $_POST['dni'];
    $observacion = $_POST['observaciones'];
    $estado = 1;



    $query_cliente = mysqli_query($conection, "SELECT * FROM clientes u 
                INNER JOIN islas r 
                ON u.isla = r.idisla
                WHERE dni='$dni' ");




    $result_cliente = mysqli_num_rows($query_cliente);



    if ($result_cliente == 0) {


        $dni = "";
        $nombre = "";
        $correo = "";
        $telefono = "";
        $dirEntrega = "";
        $isla = "";
        $direccion  = "";
    } else {


        while ($data = mysqli_fetch_array($query_cliente)) {

            $dni = $data['dni'];
            $nombreClie = $data['nombre'];
            $correo = $data['correo'];
            $dirEntrega = $data['direccion'];
            $telefono = $data['telefono'];
            $isla = $data['isla'];
            $direccion = $data['direccion'];
            $estatus = $data['estatus'];

            if ($estatus == 0) {

                $dni = '';
                $nombreClie = '';
                $dirEntrega = '';
                $telefono = '';

                $alert = '<div class="alert alert-warning" role="alert">
                            El cliente está dado de baja.
                        </div>';
            }
        }
    }
}

//se inserta el pedido
if (isset($_POST['finalizar'])) {

    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $dirEntrega = $_POST['dirEntrega'];
    $observacion = $_POST['observaciones'];
    $estado = 1;
    $totalpedidos = $_SESSION['arra']['total'];



    //ALGORITMO PARA SUBIR EL ARCHIVO AL SERVIDOR Y A LA BBDD
    //----------------------------------------------

    if (!empty($_FILES)) {

        $directory = "archivos/".mt_rand(100000000,900000000)."_";

        $subir_archivo = $directory . basename($_FILES['subir']['name']);

        if (move_uploaded_file($_FILES['subir']['tmp_name'], $subir_archivo)) {

            //archivo subido
            $adjunto = $subir_archivo;
        } else {

            //archivo no subido
            $adjunto = "";
        }
    }



    //SI NO SE HA ADJUNTADO UN NUEVO PDF 
    if ($adjunto == "") {

        $query_pedido = "INSERT INTO pedidos(id_pedido,
                                id_cliente,id_usuario,fecha_registro,fecha_entrega,dir_entrega,telefono,observacion,entrega,
                                precio_total,estado)
                                VALUES ($idPedido,'$dni',$usuario,'$fechaActual','','$dirEntrega','$telefono','$observacion','$entrega',$totalpedidos,'$estado')";

        //SI SE ADJUNTO UNO NUEVO
    } else {

        $query_pedido = "INSERT INTO pedidos(id_pedido,
                                id_cliente,id_usuario,fecha_registro,fecha_entrega,dir_entrega,telefono,observacion,entrega,
                                precio_total,estado,adjunto)
                                VALUES ($idPedido,'$dni',$usuario,'$fechaActual','','$dirEntrega','$telefono','$observacion','$entrega',$totalpedidos,'$estado','$adjunto')";
    }

    //----------------------------------------------





    if (mysqli_query($conection, $query_pedido)) {



        foreach ($_SESSION['arra'] as $key => $value) {

            //se realiza consulta para obtener los datos del producto
            $query_producto = mysqli_query($conection, "SELECT * FROM historial_productos where ID='$key'");

            $result = mysqli_num_rows($query_producto);


            if ($result > 0) {

                //si todo es correcto se obtienen los datos del producto
                if ($data = mysqli_fetch_array($query_producto)) {

                    $cantidad = $_SESSION['arra'][$key]['cantidad'];
                    $idProducto = $data['ID'];
                    $precio = $data['PRECIO'];
                    $idProveedor = $data['PROVEEDOR'];

                    $query = mysqli_query($conection, "SELECT * FROM detallespedidos ");

                    $result = mysqli_num_rows($query);

                    if ($result > 0) {

                        //realizar ultimo id y se suma uno al último
                        $detalles = mysqli_query($conection, "SELECT MAX(idDetalle) AS id FROM detallespedidos");

                        if ($row = mysqli_fetch_row($detalles)) {

                            $idDetalle = trim($row[0]);
                        }

                        $idDetalle++;
                    } else {

                        $idDetalle = 1;
                    }



                    $precioTotal = $_SESSION['arra'][$key]['precio'];


                    $query_detalles = "INSERT INTO detallespedidos(idDetalle,idPedido,
                                idProducto,idProveedor,cantidad, precio, precioTotal) 
                                VALUES($idDetalle,$idPedido,$idProducto,$idProveedor,$cantidad,$precio,$precioTotal)";

                    if (mysqli_query($conection, $query_detalles)) {

                        header('location: lista_pedidos.php');
                    } else {

                        $alert = '<div class="alert alert-danger" role="alert">
    Error al almacenar los detalles.
</div>';
                    }
                }
            }
        }


        $dni = "";
        $telefono = "";
        $observacion = "";
        $dirEntrega = "";


        foreach ($_SESSION['arra'] as $key => $value) {

            if ($key != 'total') {

                unset($_SESSION['arra'][$key]);
            } else {

                $_SESSION['arra']['total'] = 0.00;
            }
        }
    } else {

        $alert = '<p class="msg_error">Error al almacenar el pedido.</p>';
    }

    mysqli_close($conection);
}

?>








<!DOCTYPE html>
<html lang="en" id="html">

<head>
    <meta charset="UTF-8">
    <?php include "includes/script.php"; ?>
    <title>Datos del Cliente</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php"; ?>
    <br><br><br>
    <section id="container">

        <div class="formPedido">
            <br>
            <h1>Datos Pedido</h1>
            <br>
            <!-- isset if simpficado-->
            <div class="alert"> <?php echo isset($alert) ? $alert : ''; ?></div>

            <form action="" method="post" id="form" name="form" enctype="multipart/form-data" class="form_datosPedidos">

                <!--se envlaza al name nombre-->
                <label for="pedido">Pedido </label>
                <input type="text" name="pedido" readonly="true" id="pedido" value="<?php echo $idPedido; ?>">

                <label for="fechaRegistro">Fecha registro </label>
                <input type="text" name="fechaRegistro" readonly="true" id="fechaRegistro" value="<?php echo $fechaActual; ?>">

                <label for="total">Precio total </label>
                <input type="text" name="total" readonly="true" id="total" value="<?php echo $_SESSION['arra']['total'] ?>">

                <label for="observaciones">observaciones </label>
                <input type="text" name="observaciones" id="observaciones" value="<?php echo $observacion; ?>">

                <label for="estado">Estado de entrega </label>

                <input type="text" name="estado" readonly="true" id="estado" value="Pendiente">

                <label for="dni">DNI Cliente</label>
                <input type="text" name="dni" id="dni" value="<?php echo $dni; ?>">
                <input type="submit" value="Buscar Cliente" class="btn_save" id="buscar" name="buscar">

                <label for="nombre">Nombre Cliente</label>
                <input type="text" name="nombre" readonly="true" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombreClie; ?>">

                <label for="dirEntrega">Dirección Entrega</label>
                <input type="text" name="dirEntrega" id="dirEntrega" placeholder="Dirección Entrega" value="<?php echo $dirEntrega; ?>">

                <label for="Telefono">Telefono</label>
                <input type="text" name="telefono" id="telefono" placeholder="telefono" value="<?php echo $telefono; ?>">

                <label for="entrega">Archivo Adjunto </label>
                <input type="file" name="subir" id="up">

                <input type="submit" value="Finalizar Pedido" disabled="true" class="btn_fin" id="finalizar" name="finalizar">
                <?php echo isset($alert) ? $alert : ''; ?>


            </form>



            <!--si el nombre del cliente essta vacio no se puede continuar-->
            <script>
                window.onload = function() {

                    nombre = document.getElementById('nombre').value;

                    if (nombre.length != 0) {

                        document.getElementById('finalizar').style.background = "green";
                        document.getElementById('finalizar').disabled = false;

                    } else {

                        document.getElementById('finalizar').style.background = "red";

                    }

                }
            </script>

        </div>


        <!--Formulario Detalles-->
        <div class="formDetalle">
            <br>
            <h1>Detalles</h1>
            </br>

            <!--style="width:50%"-->
            <form action="" method="post" class="form_detalles">

                <table id="detalles_compras" class="table-striped table-bordered" style="width:100%">

                    <thead class="text-center">

                        <th>Id Producto</th>
                        <th>Referencia</th>
                        <th>Producto</th>
                        <th>Proveedor</th>
                        <th>Talla</th>
                        <th>Color</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>

                    </thead>

                    <tbody>

                        <?php

                        foreach ($_SESSION['arra'] as $key => $value) {

                            if ($key != 'total') {

                                //se realiza consulta para obtener los datos del producto
                                $query_producto = mysqli_query($conection, "SELECT p.ID,p.PROVEEDOR,p.TALLA,p.COLOR,p.DESCRIPCION,p.REFERENCIA,
                                                                            p.PRECIO,pr.proveedor 
                                                                            FROM historial_productos p
                                                                            INNER JOIN proveedores pr
                                                                            ON p.PROVEEDOR=pr.idproveedor
                                                                            where ID='$key'");

                                $result = mysqli_num_rows($query_producto);

                                if ($result > 0) {

                                    //si todo es correcto se obtienen los datos del producto
                                    if ($data = mysqli_fetch_array($query_producto)) {

                                        $cantidad = $_SESSION['arra'][$key]['cantidad'];
                                        $idProducto = $data['ID'];
                                        $referencia = $data['REFERENCIA'];
                                        $Producto = $data['DESCRIPCION'];
                                        $proveedor = $data['proveedor'];
                                        $idProveedor = $data['PROVEEDOR'];
                                        $talla = $data['TALLA'];
                                        $color = $data['COLOR'];
                                        $precio = $data['PRECIO'];
                                        $total = $_SESSION['arra'][$key]['precio'];
                                    }
                                }

                        ?>


                                <tr>
                                    <td><?php echo $idProducto ?></td>
                                    <td><?php echo $referencia ?></td>
                                    <td><?php echo $Producto ?></td>
                                    <td><?php echo $proveedor ?></td>
                                    <td><?php echo $talla ?></td>
                                    <td><?php echo $color ?></td>
                                    <td><?php echo $cantidad ?></td>
                                    <td><?php echo $precio ?></td>
                                    <td><?php echo $total ?></td>
                                <tr>

                            <?php

                            }
                        }

                            ?>

                    </tbody>

                </table>

            </form>

        </div>

    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>