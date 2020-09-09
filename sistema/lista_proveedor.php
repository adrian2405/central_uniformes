<?php
session_start();

include "../conexion.php";

$consulta = "SELECT * FROM proveedores";

$resultado = mysqli_query($conection, $consulta);
$proveedores = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "includes/script.php"; ?>
    <title>Lista Proveedores</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php" ?>
    <section>
    <br><br><br><br><br>
    <div class="container">
        <h1>Lista de Proveedores</h1>
        <br>
        <a href="registro_proveedor.php" class="btn btn-primary"> Crear Proveedor</a>

        <table id="proveedores" class="table-striped table-hover">
            <thead class="text-center">
                <th>Proveedor</th>
                <th>Opciones</th>
            </thead>
            <tbody>
                <?php
                foreach ($proveedores as $proveedor) {
                ?>
                    <tr>
                        <td><?php echo $proveedor['proveedor'] ?></td>
                        <td><a class="link_edit" href="editar_proveedor.php? id=<?php echo $proveedor["idproveedor"]; ?>
                        ">Editar</a><a class="link_delete" href="eliminar_confirmar_proveedor.php? id=<?php echo $proveedor["idproveedor"]; ?>
                        "> Eliminar</a></td>
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
            // Script para mostrar la tabla con búsqueda en tiempo real y paginación

            var table = $('#proveedores').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ],
                "ordering": false,
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