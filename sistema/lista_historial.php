<?php
session_start();

include "../conexion.php";

$connect = mysqli_connect("localhost", "root", "", "central_uniformes");
$query = "SELECT * FROM proveedores ORDER BY proveedor ASC";
$result = mysqli_query($connect, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "includes/script.php"; ?>
    <title>Historial Productos</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php" ?>
    <section>

    <br><br><br><br><br>
    <div class="container">
        <h1>Historial de productos</h1>

        <table id="historial" class="table-striped table-hover">
            <thead class="text-center">

                <th>Fecha de Registro</th>
                <th>Referencia</th>
                <th>ID</th>
                <th><select name="catproved" id="catproved" class="form-control">
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

            </thead>
        </table>

    </section>

    <?php include "includes/footer.php" ?>

    <!-- Script que carga la tabla con búsqueda en tiempo real, paginación y selector desde server-side-historial.php  -->

    <script type="text/javascript" language="javascript">
        $(document).ready(function() {

            load_data();

            function load_data(histo) {
                var dataTable = $('#historial').DataTable({

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
                        url: "server-side-historial.php",
                        type: "POST",
                        data: {
                            histo: histo
                        }
                    },
                    "columnDefs": [{
                        "targets": [3],
                        "orderable": false,
                    }, ],
                    scrollY: '50vh',
                    scrollCollapse: true,
                    scroller: {
                        loadingIndicator: true
                    },
                });
            }

            $(document).on('change', '#catproved', function() {
                var catproved = $(this).val();
                $('#historial').DataTable().destroy();
                if (catproved != '') {
                    load_data(catproved);
                } else {
                    load_data();
                }
            });
        });
    </script>

</body>

</html>