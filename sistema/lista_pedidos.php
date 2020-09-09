<?php
session_start();

include "../conexion.php";

$user = $_SESSION['idUser'];

$connect = mysqli_connect("localhost", "root", "", "central_uniformes");
$query = "SELECT * FROM estado ORDER BY estados ASC";
$result = mysqli_query($connect, $query);

if (!empty($_GET['pedido'])) {
    $pedido = $_GET['pedido'];
} else {
    $pedido = "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "includes/script.php"; ?>
    <title>Lista Pedidos</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php" ?>
    <section >
    <br><br><br><br><br>
    <div class="container">
        <h1>Lista de Pedidos</h1>
        <br>
        <a href="lista_producto.php" class="btn btn-primary"> Crear Pedido</a>

        <table id="pedidos" class="table-striped table-hover">
            <thead class="text-center">
                <th>Pedido</th>
                <th>Cliente</th>
                <th>Fecha Registro</th>
                <th>Fecha Entrega</th>
                <th>Observaciones</th>
                <th><select name="catped" id="catped" class="form-control">
                        <option value="">Estado</option>
                        <?php
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<option value="' . $row["estados"] . '">' . $row["estados"] . '</option>';
                        }
                        ?>
                    </select></th>
                <th>Total</th>
                <th>Opciones</th>

            </thead>

        </table>

    </section>

    <?php include "includes/footer.php" ?>
    <script type="text/javascript" language="javascript">
        $(document).ready(function() {

            load_data();

            function load_data(pedt) {
                var dataTable = $('#pedidos').DataTable({
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
                        url: "server-side-pedidos.php",
                        type: "POST",
                        data: {
                            pedt: pedt
                        }
                    },
                    "columnDefs": [{
                        "targets": [6],
                        "orderable": false,
                    }, ],
                    scrollY: '50vh',
                    scrollCollapse: true,
                    scroller: {
                        loadingIndicator: true
                    },
                });
            }

            $(document).on('change', '#catped', function() {
                var catped = $(this).val();
                $('#pedidos').DataTable().destroy();
                if (catped != '') {
                    load_data(catped);
                } else {
                    load_data();
                }
            });
        });
    </script>
</body>

</html>