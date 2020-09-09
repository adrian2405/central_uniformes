<?php

session_start();

include "../conexion.php";

if (!empty($_POST)) {

    $idusuario = $_POST['idusuario'];

    $query_delete = mysqli_query($conection, "UPDATE usuario SET estatus=0 WHERE idusuario=$idusuario");

    if ($query_delete) {
        header("location: lista_usuario.php");
    } else {
        
        $alert= '<div class="alert alert-warning" role="alert">
        Error al suspender el usuario, tiene pedidos realizados.
</div>';

        // echo "Error al eliminar el usuario tiene pedidos realizados";
    }
}

if (empty($_REQUEST['id'])) {
    header("location: lista_usuario.php");
} else {

    $idusuario = $_REQUEST['id'];

    $query_usuario = mysqli_query($conection, "SELECT *
                                                FROM usuario
                                                WHERE idusuario='$idusuario'");

    mysqli_close($conection);

    $result_usuario = mysqli_num_rows($query_usuario);

    if ($result_usuario == 0) {
        header('location: lista_usuario.php');
    } else {
        $option = '';
        while ($data = mysqli_fetch_array($query_usuario)) {

            $idusuario = $data['idusuario'];
            $nombre = $data['nombre'];
            $correo = $data['correo'];
            $usuario = $data['usuario'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/script.php"; ?>
    <title>Eliminar Proveedor</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="data_delete">
            <br><br>
            <br>
            <br>
            <h1>¿Está seguro de suspender este usuario?</h1>
            <br>
            <br>
            <br>
            <p>ID: <span><?php echo $idusuario; ?></span></p>
            <p>Nombre: <span><?php echo $nombre; ?></span></p>
            <p>ID: <span><?php echo $correo; ?></span></p>
            <p>Nombre: <span><?php echo $usuario; ?></span></p>

            <form method="post" action="">
                <input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>">
                <a href="lista_usuario.php" class="btn btn-light">Cancelar</a>
                <input type="submit" value="Suspender" class="btn btn-danger" style="width: 100px; margin-left: 5px;">
                <?php echo isset($alert) ? $alert : ''; ?>
            </form>

        </div>
    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>