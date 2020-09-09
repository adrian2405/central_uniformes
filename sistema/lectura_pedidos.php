<?php

session_start();

/*if($_SESSION['rol']!=1){
    header("location: ./");
}*/

include "../conexion.php";

$dni = "";
$nombreClie = "";
$correo = "";
$telefono = "";
$isla = "";
$direccion  = "";
$observacion = "";
$entrega = "";
$estado = "";
$sumaTotal = "";

//contador registros array
$cont = 0;

//fecha del sistema
$fechaActual = date('Y-m-d');

//se asigna la cantidad al producto 
/*foreach($_SESSION['arra'] as $key=>$value){
    $cantidad=$_GET[$key];
    $_SESSION['arra'][$key]=$cantidad;
    $cont++;
    //echo" session ".$_SESSION['arra'][$key];
}*/

//ALGORITMO LEER ARCHIVO ADJUNTO
//--------------------------------------------------------------------------------
$archivo = "";

$idPedido = $_REQUEST['pedido'];

$query_pedido = mysqli_query($conection, "SELECT adjunto from pedidos where id_pedido=$idPedido");

$result_pedido = mysqli_num_rows($query_pedido);

if ($result_pedido > 0) {

    $data = mysqli_fetch_array($query_pedido);

    $archivo = $data[0];

    if (!file_exists($archivo)) {
        $archivo = '';
    }
}

//--------------------------------------------------------------------------------

if (!empty($_POST['finalizar'])) {

    $pedi = $_POST['pedido'];

    //ALGORITMO PARA SUBIR EL ARCHIVO AL SERVIDOR Y A LA BBDD
    //----------------------------------------------

    if (!empty($_FILES)) {

        $directory = "archivos/".mt_rand(100000000,900000000)."_";

        $subir_archivo = $directory . basename($_FILES['subir']['name']);

        if (move_uploaded_file($_FILES['subir']['tmp_name'], $subir_archivo)) {

            //archivo subido
            #header("location: ejemploSubir1.php?archivo=$subir_archivo");
            $adjunto = $subir_archivo;
        } else {

            //archivo no subido
            $adjunto = "";
        }
    }

    if ($adjunto == "") {

        $query_update = TRUE;
    } else {

        $query_update = mysqli_query($conection, "UPDATE pedidos 
        SET  adjunto= '$adjunto'
        WHERE id_pedido=$pedi");
    }

    //----------------------------------------------

    if ($query_update) {
        header('location: lista_pedidos.php');
    } else {
        $alert= '<div class="alert alert-danger" role="alert">
                            Error al modificar datos del pedido.
                        </div>';
    }
}

//consulta para comprobar si hay pedidos
if (empty($_REQUEST['pedido'])) {
    header('location: lista_pedidos_proveedores.php');
}
$idPedido = $_REQUEST['pedido'];

$query_pedido = mysqli_query($conection, "SELECT p.id_pedido,p.id_cliente,p.id_usuario,p.fecha_registro,
                p.fecha_entrega,p.fecha_entrega,p.dir_entrega,p.telefono,p.observacion,p.entrega,
                p.precio_total,p.estado, e.idEstado,e.estados
                FROM pedidos p
                INNER JOIN estado e
                ON p.estado = idEstado
                WHERE id_pedido=$idPedido");

//mysqli_close($conection);

$result_pedido = mysqli_num_rows($query_pedido);

if ($result_pedido == 0) {
    header('location: lista_pedidos_proveedores.php');
} else {

    while ($data = mysqli_fetch_array($query_pedido)) {

        $idpedido = $data['id_pedido'];
        $idCliente = $data['id_cliente'];
        $idUsuario = $data['id_usuario'];
        $fechaRegistro = $data['fecha_registro'];
        $fechaEntrega = $data['fecha_entrega'];
        $dirEntrega = $data['dir_entrega'];

        if ($data['telefono'] == 0) {
            $telefono = "";
        } else {
            $telefono = $data['telefono'];
        }

        $observacion = $data['observacion'];
        $entrega = $data['entrega'];
        $precioTotal = $data['precio_total'];
        $estado = $data['estados'];
    }
}

//consulta para mostrar el nombre del usuario(tienda) que realiza la compra

$query_user = mysqli_query($conection, "SELECT *
                                    FROM usuario 
                                    WHERE idusuario='$idUsuario'");

//mysqli_close($conection);

$result_user = mysqli_num_rows($query_user);

if ($result_user == 0) {
    //header('location: lista_cliente.php');
} else {
    $option = '';
    while ($data = mysqli_fetch_array($query_user)) {

        $nombre = $data['nombre'];
    }
    //mysqli_close($conection);
}

//se busca al cliente y se almacenan los datos
if (isset($_POST['buscar'])) {

    $dni = $_POST['dni'];
    $observacion = $_POST['observaciones'];
    $entrega = $_POST['entrega'];
    $estado = $_POST['estado'];

    $query_cliente = mysqli_query($conection, "SELECT * FROM clientes u 
                INNER JOIN islas r 
                ON u.isla = r.idisla
                WHERE dni='$dni' ");

    //mysqli_close($conection);

    $result_cliente = mysqli_num_rows($query_cliente);

    if ($result_cliente == 0) {
        //header('location: lista_cliente.php');

        $dni = "";
        $nombre = "";
        $correo = "";
        $telefono = "";
        $isla = "";
        $direccion  = "";
    } else {

        while ($data = mysqli_fetch_array($query_cliente)) {

            $dni = $data['dni'];
            $nombreClie = $data['nombre'];
            $correo = $data['correo'];

            $telefono = $data['telefono'];

            $isla = $data['isla'];
            $direccion = $data['direccion'];
        }
    }
}

//se inserta el pedido

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
    </br><br><br>
    <section id="container">
        <div class="formPedido">

        </br>
            <h1>Datos Pedido</h1>
            <br>

            <form action="" method="post" id="form" name="form" enctype="multipart/form-data" class="form_datosPedidos">
                <!--se envlaza al name nombre-->

                <label for="pedido">Pedido </label>
                <input type="text" name="pedido" readonly="true" id="pedido" value="<?php echo $idPedido; ?>">

                <label for="usuario">Usuario </label>
                <input type="text" name="usuario" readonly="true" id="usuario" value="<?php echo $nombre; ?>">

                <label for="fechaRegistro">Fecha registro </label>
                <input type="text" name="fechaRegistro" readonly="true" id="fechaRegistro" value="<?php echo $fechaActual; ?>">

                <label for="fechaEntrega">Fecha entrega </label>
                <input type="date" step="1" name="fechaEntrega" id="fechaEntrega" readonly="true" value="<?php echo $fechaEntrega; ?>">

                <label for="total">Precio total </label>
                <input type="text" name="total" readonly="true" id="total" value="<?php echo $precioTotal ?>">

                <label for="observaciones">observaciones </label>
                <input type="text" name="observaciones" id="observaciones" readonly="true" value="<?php echo $observacion; ?>">

                <label for="entrega">Comentario entrega </label>
                <input type="text" name="entrega" id="entrega" readonly="true" value="<?php echo $entrega; ?>">

                <label for="entrega">Archivo Adjunto </label>
                <input type="file" name="subir" id="up">

                <label for="estado">Estado de entrega </label>
                <input type="text" name="estado" id="estado" readonly="true" value="<?php echo $estado; ?>">

                <label for="dni">DNI Cliente</label>
                <input type="text" name="dni" id="dni" readonly="true" value="<?php echo $idCliente; ?>">

                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" id="telefono" placeholder="telefono" readonly="true" value="<?php echo $telefono; ?>">

                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="direccion" readonly="true" value="<?php echo $dirEntrega; ?>">

                <!--<input type="submit" value="Finalizar Pedido" class="btn_save" id="finalizar" name="finalizar">-->

                <!--formaction="lista_producto.php"-->

                <input type="submit" value="Actualizar Pedido" class="btn_save" id="finalizar" name="finalizar">
                <?php echo isset($alert) ? $alert : ''; ?>

            </form>

            <!--si el nombre del cliente essta vacio no se puede continuar-->

        </div>

        <!--Formulario Detalles-->

        <div class="formDetalle">
            </br>

            <h1>Detalles</h1>
            </br>

            <!--style="width:50%"-->
            <form action="" method="post" class="form_detalles">

                <table id="detalles_compras" class="table-striped table-bordered" style="width:100%">
                    <thead class="text-center">

                        <th>ID Producto</th>
                        <th>Referencia</th>
                        <th>Producto</th>
                        <th>Talla</th>
                        <th>Color</th>
                        <th>Proveedor</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Precio Total</th>

                    </thead>
                    <tbody>
                        <?php

                        //se realiza consulta para obtener los datos del producto
                        $query_detalles = mysqli_query($conection, "SELECT *
                                                                FROM detallespedidos
                                                                WHERE idPedido=$idPedido");

                        //mysqli_close($conection);

                        $result_detalles = mysqli_num_rows($query_detalles);

                        if ($result_detalles == 0) {
                            //header('location: lista_pedidos_proveedores.php');
                        } else {

                            while ($data = mysqli_fetch_array($query_detalles)) {

                                //$prov = $data['idProveedor'];
                                $idProducto = $data['idProducto'];

                                $query_prov = mysqli_query($conection, "SELECT p.ID,p.PROVEEDOR,
                                                p.TALLA,p.COLOR,p.DESCRIPCION,p.REFERENCIA,pr.proveedor
                                                FROM historial_productos p
                                                INNER JOIN proveedores pr
                                                ON p.PROVEEDOR = pr.idproveedor

                                                WHERE p.ID = $idProducto");

                                $result_prov = mysqli_num_rows($query_prov);
                                if ($result_prov != 0) {

                                    if ($data2 = mysqli_fetch_array($query_prov)) {

                                        $proveedor = $data2['proveedor'];
                                        $talla = $data2['TALLA'];
                                        $color = $data2['COLOR'];
                                        $descripcion = $data2['DESCRIPCION'];
                                        $referencia = $data2['REFERENCIA'];
                                    }
                                } else {
                                    $idProveedor = $data['idProveedor'];
                                }

                                $idPedido = $data['idPedido'];
                                $idProducto = $data['idProducto'];
                                //$idProveedor=$data['idProveedor'];
                                $cantidad = $data['cantidad'];
                                $precio = $data['precio'];
                                $precioTotal = $data['precioTotal'];

                        ?>
                                <tr>
                                    <td><?php echo $idProducto ?></td>
                                    <td><?php echo $referencia ?></td>
                                    <td><?php echo $descripcion ?></td>
                                    <td><?php echo $talla ?></td>
                                    <td><?php echo $color ?></td>
                                    <td><?php echo $proveedor ?></td>
                                    <td><?php echo $cantidad ?></td>
                                    <td><?php echo $precio ?></td>
                                    <td><?php echo $precioTotal ?></td>
                                <tr>
                            <?php
                            }
                        }
                            ?>

                    </tbody>

                </table>

            </form>

        </div>

        </br>
        </br>

        <?php
        if ($archivo != '') {

        ?>

            <iframe id="visor" name="visor" src="<?php echo $archivo ?>" style="width: 500px; height: 600px; float: left; margin-left: 200px; margin-top: 20px"></iframe>

        <?php
        }
        ?>

    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>