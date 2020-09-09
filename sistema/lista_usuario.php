<?php
session_start();

if ($_SESSION['rol'] != 1) {
    header("location: ./");
}

include "../conexion.php";

$consulta = "SELECT p.idusuario, p.nombre,p.correo,p.usuario,
    pr.rol
    FROM usuario p
    INNER JOIN rol pr
    ON p.rol = pr.idrol
    WHERE p.estatus = 1";

$resultado = mysqli_query($conection, $consulta);
$usuarios = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

$consulta_suspendidos = "SELECT p.idusuario, p.nombre,p.correo,p.usuario,
    pr.rol
    FROM usuario p
    INNER JOIN rol pr
    ON p.rol = pr.idrol
    WHERE p.estatus = 0";

$resultado_suspendidos = mysqli_query($conection, $consulta_suspendidos);
$usuarios_suspendidos = mysqli_fetch_all($resultado_suspendidos, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include "includes/script.php"; ?>
    <title>Lista Usuarios</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php" ?>
    <section>
    <br><br><br><br><br>
    <div class="container">
        <h1>Lista de usuarios activos</h1>
        <br>
        <a href="registro_usuario.php" class="btn btn-primary"> Crear usuario</a>

        <table id="usuarios" class="table-striped table-hover">
            <thead class="text-center">
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Opciones</th>
            </thead>
            <tbody>
                <?php
                foreach ($usuarios as $usuario) {
                ?>
                    <tr>
                        <td><?php echo $usuario['nombre'] ?></td>
                        <td><?php echo $usuario['correo'] ?></td>
                        <td><?php echo $usuario['usuario'] ?></td>
                        <td><?php echo $usuario['rol'] ?></td>
                        <td>

                            <a class="link_edit" href="editar_usuario.php? id=<?php echo $usuario["idusuario"]; ?>
                        ">Editar</a>

                            <?php
                            if ($usuario['idusuario'] != 1) {
                            ?>

                                <a class="link_delete" href="eliminar_confirmar_usuario.php? id=<?php echo $usuario["idusuario"]; ?>
                        "> Suspender</a>

                            <?php } ?>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <br>
        <h1>Lista de usuarios suspendidos</h1>

        <table id="usuarios_suspendidos" class="table-striped table-hover">
            <thead class="text-center">
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Opciones</th>
            </thead>
            <tbody>
                <?php
                foreach ($usuarios_suspendidos as $usuario_suspendido) {
                ?>
                    <tr>
                        <td><?php echo $usuario_suspendido['nombre'] ?></td>
                        <td><?php echo $usuario_suspendido['correo'] ?></td>
                        <td><?php echo $usuario_suspendido['usuario'] ?></td>
                        <td><?php echo $usuario_suspendido['rol'] ?></td>
                        <td>

                            <a class="link_edit" href="editar_usuario.php? id=<?php echo $usuario_suspendido["idusuario"]; ?>
                        ">Editar</a>

                            <?php

                            ?>

                            <a class="link_delete" href="activar_usuario.php? id=<?php echo $usuario_suspendido["idusuario"]; ?>
                        "> Activar</a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </section>

    <?php include "includes/footer.php" ?>
    <script>
        $(document).ready(function() {
            // Script para mostrar la tabla con búsqueda en tiempo real, paginación y selector en rol
            $('#usuarios, #usuarios_suspendidos').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ],
                "ordering": false,
                initComplete: function() {
                    this.api().columns([3]).every(function() {
                        var column = this;
                        var select = $('<select><option value="">Rol</option></select>')
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