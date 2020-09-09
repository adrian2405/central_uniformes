<?php
session_start();

include "../conexion.php";

$consulta = "SELECT p.dni, p.nombre,p.correo,p.telefono,
    pr.isla, p.direccion
    FROM clientes p
    INNER JOIN islas pr
    ON p.isla = pr.idisla
    WHERE p.estatus = 1";

$resultado = mysqli_query($conection, $consulta);
$clientes = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

$consulta_suspendidos = "SELECT p.dni, p.nombre,p.correo,p.telefono,
    pr.isla, p.direccion
    FROM clientes p
    INNER JOIN islas pr
    ON p.isla = pr.idisla
    WHERE p.estatus = 0";

$resultado_suspendidos = mysqli_query($conection, $consulta_suspendidos);
$clientes_suspendidos = mysqli_fetch_all($resultado_suspendidos, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "includes/script.php"; ?>
    <title>Lista Clientes</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php" ?>
    <section>
    <br><br><br><br><br>
    <div class="container">
        <h1>Lista de clientes activos</h1>
        <br>
        <a href="registro_cliente.php" class="btn btn-primary"> Crear Cliente</a>

        <table id="clientes" class="table-striped table-hover">
            <thead class="text-center">
                <th>DNI</th>
                <th>Cliente</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Isla</th>
                <th>Dirección</th>
                <th>Opciones</th>
            </thead>
            <tbody>
                <?php
                foreach ($clientes as $cliente) {
                ?>
                    <tr>
                        <td><?php echo $cliente['dni'] ?></td>
                        <td><?php echo $cliente['nombre'] ?></td>
                        <td><?php echo $cliente['correo'] ?></td>
                        <td><?php echo $cliente['telefono'] ?></td>
                        <td><?php echo $cliente['isla'] ?></td>
                        <td><?php echo $cliente['direccion'] ?></td>
                        <td><a class="link_edit" href="editar_cliente.php? id=<?php echo $cliente["dni"]; ?>
                        ">Editar</a>
                            <a class="link_delete" href="eliminar_confirmar_cliente.php? id=<?php echo $cliente["dni"]; ?>
                        "> Suspender</a></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <br>
        <h1>Lista de clientes suspendidos</h1>

        <table id="clientes_suspendidos" class="table-striped table-hover">
            <thead class="text-center">
                <th>DNI</th>
                <th>Cliente</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Isla</th>
                <th>Dirección</th>
                <th>Opciones</th>
            </thead>
            <tbody>
                <?php
                foreach ($clientes_suspendidos as $cliente_suspendido) {
                ?>
                    <tr>
                        <td><?php echo $cliente_suspendido['dni'] ?></td>
                        <td><?php echo $cliente_suspendido['nombre'] ?></td>
                        <td><?php echo $cliente_suspendido['correo'] ?></td>
                        <td><?php echo $cliente_suspendido['telefono'] ?></td>
                        <td><?php echo $cliente_suspendido['isla'] ?></td>
                        <td><?php echo $cliente_suspendido['direccion'] ?></td>
                        <td><a class="link_edit" href="editar_cliente.php? id=<?php echo $cliente_suspendido["dni"]; ?>
                        ">Editar</a>
                            <a class="link_delete" href="activar_cliente.php? id=<?php echo $cliente_suspendido["dni"]; ?>
                        "> Activar</a></td>
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
            // Script para mostrar la tabla con búsqueda en tiempo real, paginación y selector en campo isla
            $('#clientes, #clientes_suspendidos').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ],
                "ordering": false,
                initComplete: function() {
                    this.api().columns([4]).every(function() {
                        var column = this;
                        var select = $('<select><option value="">Isla</option></select>')
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