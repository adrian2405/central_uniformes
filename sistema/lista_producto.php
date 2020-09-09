<?php
session_start();

include "../conexion.php";

if (!isset($_SESSION['arra'])) {

    $_SESSION['arra'] = array();
    $_SESSION['arra']['total'] = 0.00;
}

$connect = mysqli_connect("localhost", "root", "", "central_uniformes");
$query = "SELECT * FROM proveedores ORDER BY proveedor ASC";
$result = mysqli_query($connect, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<link rel="stylesheet" type="text/css" href="../css/style.css">-->
    <?php include "includes/script.php"; ?>
    <title>Lista Productos</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php" ?>
    <section>
        <br><br><br><br>
    <div class="container" >
    
        <?php
        function arr()
        {

            if (!empty($_GET['nombre'])) {
                $nombre = $_GET['nombre'];
                $repetido = FALSE;
                foreach ($_SESSION['arra'] as $key => $value) {
                    if ($key != 'total') {
                        if ($key == $nombre) {
                            $repetido = TRUE;
                        }
                    }
                }
                if (!$repetido) {
                    $_SESSION['arra'][$nombre] = array('cantidad' => 0, 'precio' => 0.00);
                }
            }
        }
        ?>
        <br>
        <h1>Lista de Productos</h1>

        <?php
        if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 3) {

        ?>
        <br>
            <a href="registro_producto.php" class="btn btn-primary"> Cargar productos (CSV)</a>

        <?php
        }
        ?>

        <table id="productos" class="table-striped table-hover">
            <thead class="text-center">
                <th>Referencia</th>
                <th>ID</th>
                <th><select name="catprov" id="catprov" class="form-control">
                        <option value="">Proveedor</option>
                        <?php
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<option value="' . $row["idproveedor"] . '">' . $row["proveedor"] . '</option>';
                        }
                        ?>
                    </select></th>
                <th>Talla</th>
                <th>Color</th>
                <th>Imagen</th>
                <th>Descripción</th>
                <th>Precio</th>
                <?php

                if ($_SESSION['rol'] != 3) {

                ?>
                    <th>Pedidos</th>

                <?php } ?>
            </thead>
        </table>
        <?php
        arr();
        ?>
    <div>
    </section>

    <?php include "includes/footer.php" ?>

    <!-- Script que carga la tabla con búsqueda en tiempo real, paginación y selector desde server-side-productos.php  -->

    <script type="text/javascript" language="javascript">
        $(document).ready(function() {

            load_data();

            function load_data(prove) {
                var dataTable = $('#productos').DataTable({

                    // Configuración de la tabla para mostrar en español y selector en campo proveedor

                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "Todos"]
                    ],
                    "ordering": false,
                    "pagingType": "full_numbers",
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
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    "ajax": {
                        url: "server-side-productos.php",
                        type: "POST",
                        data: {
                            prove: prove
                        }
                    },
                    "columnDefs": [{
                        "targets": [2],
                        "orderable": false,
                    }, ],
                    scrollY: '50vh',
                    scrollCollapse: true,
                    scroller: {
                        loadingIndicator: true
                    },
                });
            }

            $(document).on('change', '#catprov', function() {
                var catprov = $(this).val();
                $('#productos').DataTable().destroy();
                if (catprov != '') {
                    load_data(catprov);
                } else {
                    load_data();
                }
            });
        });
    </script>

</body>

</html>