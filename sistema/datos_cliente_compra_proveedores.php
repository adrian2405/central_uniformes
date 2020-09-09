<?php

session_start();


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







//SE EJECUTA AL PULSAR EL BOTON FINALIZAR
if (!empty($_POST['finalizar'])) {

    //RECOGE LOS DATOS DEL FORMULARIO
    $pedi = $_POST['pedido'];
    $fecha_entrega = $_POST['fechaEntrega'];
    $estatus = $_POST['estado'];
    $obsEntrega = $_POST['entrega'];
    $dire = $_POST['direccion'];

    //ACTUALIZA EL REGISTRO
    $query_update = mysqli_query($conection, "UPDATE pedidos 
    SET fecha_entrega='$fecha_entrega', entrega = '$obsEntrega', estado='$estatus', dir_entrega = '$dire'
    WHERE id_pedido=$pedi");

    if ($query_update) {

        header('location: lista_pedidos_proveedores.php');
    } else {

        $alert = '<div class="alert alert-danger" role="alert">
    Error al modificar los datos del pedido.
</div>';
    }
}










//consulta para comprobar si hay pedidos
if (empty($_REQUEST['pedido'])) {

    header('location: lista_pedidos_proveedores.php');
}



//OBTIENE EL ID DEL PEDIDO
$idPedido = $_REQUEST['pedido'];

//BUSCA EL PEDIDO EN LA BBDD
$query_pedido = mysqli_query($conection, "SELECT p.id_pedido,p.id_cliente,p.id_usuario,p.fecha_registro,
                            p.fecha_entrega,p.fecha_entrega,p.dir_entrega,p.observacion,p.entrega,
                            p.precio_total,(p.estado) as idEstado, (e.estados)as estado
                            FROM pedidos p
                            INNER JOIN estado e
                            ON p.estado = e.idEstado
                            WHERE id_pedido=$idPedido");

$result_pedido = mysqli_num_rows($query_pedido);


//SI NO ENCUENTRA EN PEDIDO VUELVE A LA LISTA DE PEDIDOS
if ($result_pedido == 0) {

    header('location: lista_pedidos_proveedores.php');


    //SI LO ENCUENTRA OBTIENE LOS DATOS 
} else {

    $option = '';

    while ($data = mysqli_fetch_array($query_pedido)) {

        $idpedido = $data['id_pedido'];
        $idCliente = $data['id_cliente'];
        $idUsuario = $data['id_usuario'];
        $fechaRegistro = $data['fecha_registro'];
        $fechaEntrega = $data['fecha_entrega'];
        $dirEntrega = $data['dir_entrega'];
        $observacion = $data['observacion'];
        $entrega = $data['entrega'];
        $precioTotal = $data['precio_total'];
        $idEstado = $data['idEstado'];
        $Estado = $data['estado'];


        if ($idEstado == 1) {

            $option = ' <option value="' . $idEstado . '"select>' . $Estado . '</option> ';
        } else if ($idEstado == 2) {

            $option = ' <option value="' . $idEstado . '"select>' . $Estado . '</option>  ';
        } else if ($idEstado == 3) {

            $option = ' <option value="' . $idEstado . '"select>' . $Estado . '</option>  ';
        } else if ($idEstado == 4) {

            $option = ' <option value="' . $idEstado . '"select>' . $Estado . '</option>  ';
        }
    }
}








//consulta para mostrar el nombre del usuario(tienda) que realiza la compra
$query_user = mysqli_query($conection, "SELECT *
                                    FROM usuario 
                                    WHERE idusuario='$idUsuario'");

$result_user = mysqli_num_rows($query_user);

if ($result_user == 0) {
} else {

    while ($data = mysqli_fetch_array($query_user)) {

        $nombre = $data['nombre'];
    }
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



    $result_cliente = mysqli_num_rows($query_cliente);

    //SI NO ENCUENTRA NADA DEJARA LOS CAMPOS DEL FORM VACIOS
    if ($result_cliente == 0) {

        $dni = "";
        $nombre = "";
        $correo = "";
        $telefono = "";
        $isla = "";
        $direccion  = "";

        // SI LO ENCUENTRA RELLENA LOS CAMPOS CON LOS DATOS
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
    <br><br></br>
    <section id="container">
        <div class="formPedido">
        <br>
            
            <h1>Datos Pedido</h1>
            <br>

            <form action="" method="post" id="form" name="form" class="form_datosPedidos">

                <!--se enlaza al name nombre-->
                <label for="pedido">Pedido </label>
                <input type="text" name="pedido" readonly="true" id="pedido" value="<?php echo $idPedido; ?>">

                <label for="usuario">Usuario </label>
                <input type="text" name="usuario" readonly="true" id="usuario" value="<?php echo $nombre; ?>">

                <label for="fechaRegistro">Fecha registro </label>
                <input type="text" name="fechaRegistro" readonly="true" id="fechaRegistro" value="<?php echo $fechaActual; ?>">

                <?php

                if ($fechaEntrega == "0000-00-00") {
                ?>
                    <label for="fechaEntrega">Fecha entrega </label>
                    <input type="date" step="1" name="fechaEntrega" id="fechaEntrega" value="<?php echo $fechaActual; ?>">
                <?php
                } else {
                ?>
                    <label for="fechaEntrega">Fecha entrega </label>
                    <input type="date" step="1" name="fechaEntrega" id="fechaEntrega" value="<?php echo $fechaEntrega; ?>">


                <?php
                }
                
                ?>

                <label for="total">Precio total </label>
                <input type="text" name="total" readonly="true" id="total" value="<?php echo $precioTotal ?>">

                <label for="observaciones">observaciones </label>
                <input type="text" name="observaciones" id="observaciones" readonly="true" value="<?php echo $observacion; ?>">

                <label for="entrega">Comentario entrega </label>
                <input type="text" name="entrega" id="entrega" value="<?php echo $entrega; ?>">

                <label for="estado">Estado de entrega </label>




                <?php

                $query_estado = mysqli_query($conection, "SELECT * FROM estado");

                $result_estado = mysqli_num_rows($query_estado);

                ?>





                <select name="estado" id="estado">

                    <?php

                    echo $option;

                    if ($result_estado > 0) {

                        while ($estado = mysqli_fetch_array($query_estado)) {

                    ?>

                            <option value="<?php echo $estado['idEstado'] ?>"><?php echo $estado['estados'] ?></option>

                    <?php

                        }
                    }

                    ?>

                </select>








                <label for="dni">DNI Cliente</label>
                <input type="text" name="dni" id="dni" readonly="true" value="<?php echo $idCliente; ?>">

                <label for="direccion">Dirección</label>
                <input type="textarea" name="direccion" id="direccion" placeholder="direccion" value="<?php echo $dirEntrega; ?>">

                <input type="submit" value="Finalizar Pedido" class="btn_save" id="finalizar" name="finalizar">
                <?php echo isset($alert) ? $alert : ''; ?>


            </form>



        </div>







        <!--Formulario Detalles-->
        <div class="formDetalle">
        <br>
            <h1>Detalles</h1>
            <br>

            <form action="" method="post" class="form_detalles">

                <table id="detalles_compras" class="table-striped table-bordered" style="width:100%">

                    <thead class="text-center">

                        <th>ID Producto</th>
                        <th>Referencia</th>
                        <th>Producto</th>
                        <th>Talla</th>
                        <th>color</th>
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

                        $result_detalles = mysqli_num_rows($query_detalles);

                        if ($result_detalles == 0) {
                        } else {

                            while ($data = mysqli_fetch_array($query_detalles)) {

                                $idProducto = $data['idProducto'];

                                $query_prov = mysqli_query($conection, "SELECT p.ID,p.PROVEEDOR,
                                                p.TALLA,p.COLOR,p.DESCRIPCION, p.REFERENCIA,pr.proveedor
                                                FROM historial_productos p
                                                INNER JOIN proveedores pr
                                                ON p.PROVEEDOR = pr.idproveedor
                                                WHERE p.ID = $idProducto");

                                $result_prov = mysqli_num_rows($query_prov);

                                if ($result_prov != 0) {

                                    if ($data2 = mysqli_fetch_array($query_prov)) {

                                        $proveedor = $data2['proveedor'];
                                        $referencia = $data2['REFERENCIA'];
                                        $talla = $data2['TALLA'];
                                        $color = $data2['COLOR'];
                                        $descripcion = $data2['DESCRIPCION'];
                                    }
                                } else {

                                    $idProveedor = $data['idProveedor'];
                                }

                                $idPedido = $data['idPedido'];
                                $idProducto = $data['idProducto'];
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

        //SI EL ARCHIVO ADJUNTO NO ESTA VACIO MUESTRA LA VENTANA
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