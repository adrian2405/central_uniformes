<?php

session_start();

include "../conexion.php";




if (isset($_GET['pedido'])) {

    $_SESSION['pedido'] = $_GET['pedido'];

}





if (isset($_SESSION['pedido'])) {

    $ped = $_SESSION['pedido'];

    $consulta = "SELECT d.idDetalle,d.idPedido,d.idProducto,d.idProveedor,d.cantidad,d.precio,d.precioTotal,
    p.DESCRIPCION,pr.proveedor
    FROM detallespedidos d 
    INNER JOIN historial_productos p
    ON d.idProducto = p.ID
    INNER JOIN proveedores pr
    ON d.idProveedor = pr.idproveedor
    WHERE idPedido=$ped";

    $query_pedido = mysqli_query($conection, "SELECT * FROM pedidos WHERE id_pedido=$ped");

    $result_pedido = mysqli_num_rows($query_pedido);

    if ($result_pedido == 0) {

        header('location: lista_pedidos_proveedores.php');

    } else {

        //OBTIENE EL ID DEL ESTADO DEL PEDIDO (PENDIENTE, TERMINADO..)
        while ($data = mysqli_fetch_array($query_pedido)) {

            $estado = $data['estado'];
            
        }

    }

}





if (isset($_REQUEST['id'])) {

        $i = $_REQUEST['id'];
        $_SESSION['pedido'] = $i;

        $consulta = "SELECT d.idDetalle,d.idPedido,d.idProducto,d.idProveedor,d.cantidad,d.precio,d.precioTotal,
                    p.DESCRIPCION,pr.proveedor
                    FROM detallespedidos d 
                    INNER JOIN historial_productos p
                    ON d.idProducto = p.ID
                    INNER JOIN proveedores pr
                    ON d.idProveedor = pr.idproveedor
                    WHERE idPedido=$i";





} else if (isset($_REQUEST['id']) && (isset($_SESSION['pedido']))) {


        $consulta = "SELECT d.idDetalle,d.idPedido,d.idProducto,d.idProveedor,d.cantidad,d.precio,d.precioTotal,
                    p.DESCRIPCION,pr.proveedor
                    FROM detallespedidos d 
                    INNER JOIN historial_productos p
                    ON d.idProducto = p.ID
                    INNER JOIN proveedores pr
                    ON d.idProveedor = pr.idproveedor";

}






//SE EJECUTA LA CONSULTA 
$resultado = mysqli_query($conection, $consulta);
$detalles = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

?>








<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "includes/script.php"; ?>
    <title>Detalles del Pedido</title>

</head>

<body class="text-center bg-light">

    <?php include "includes/header.php" ?>

    <section>

    <br><br><br><br><br>
    <div class="container" >
        <h1>Detalles del Pedido</h1>
        <br>
        <?php
        if($_SESSION['rol']!=3){
        echo '<a href="lista_producto.php" class="btn btn-primary"> Crear Pedido</a>';
        }
        ?>

        <table id="detalles" class="table-striped table-hover">

            <thead class="text-center">
                <th>Detalle</th>
                <th>Pedido</th>
                <th>Producto</th>
                <th>Proveedor</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>


                <?php

                if ($estado != 3) {

                ?>
                    <th>Opciones</th>

                <?php

                }

                ?>

            </thead>




            <tbody>

                <?php

                foreach ($detalles as $detalle) {

                ?>



                    <tr>

                        <td><?php echo $detalle['idDetalle'] ?></td>
                        <td><?php echo $detalle['idPedido'] ?></td>
                        <td><?php echo $detalle['DESCRIPCION'] ?></td>
                        <td><?php echo $detalle['proveedor'] ?></td>
                        <td><?php echo $detalle['cantidad'] ?></td>
                        <td><?php echo $detalle['precio'] ?></td>
                        <td><?php echo $detalle['precioTotal'] ?></td>

                        <?php
                        
                        //SI EL ESTADO NO ESTA FINALIZADO (3) HABILITA LOS BOTONES DE BORRAR Y EDITAR
                        if ($estado != 3) {

                        ?>



                        <td>

                            <a class="link_edit" name="modificar" id="modificar" href="editar_detalle_pedidos.php? id=<?php echo $detalle["idDetalle"]; ?>
                            ">Modificar</a> 
                            | 
                            <a class="link_delete" name="eliminar" id="eliminar" href="eliminar_confirmar_detalle.php? id=<?php echo $detalle["idDetalle"]; ?>
                            ">Eliminar</a>

                         </td>




                        <?php

                        }

                        ?>

                    </tr>




                <?php

                }

                ?>


            </tbody>


        </table>


    </section>

    <?php include "includes/footer.php" ?>




    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#detalles').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ],
                "ordering": false,
                initComplete: function() {
                    this.api().columns([3]).every(function() {
                        var column = this;
                        var select = $('<select><option value="">Proveedor</option></select>')
                            .appendTo($(column.header()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                },

                "pagingType": "full_numbers",
                "order": [
                    [1, "asc"]
                ],
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por pagina",
                    "info": "",
                    "decimal": ",",
                    "thousands": ".",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrada de _MAX_ registros)",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "next": ">>",
                        "previous": "<<",
                        "first": "<|",
                        "last": "|>"
                    },
                },
                scrollY: '50vh',
                scrollCollapse: true,
                scroller: {
                    loadingIndicator: true
                },
            });
        });
    </script>



</body>

</html>