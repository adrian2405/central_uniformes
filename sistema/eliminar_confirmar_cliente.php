<?php

session_start();

if ($_SESSION['rol'] != 1 && $_SESSION['rol'] != 2) {
    header("location: ./");
}

include "../conexion.php";

if (!empty($_REQUEST['dni'])) {

    if ($_POST['dni'] == 1) {
        header("location: lista_cliente.php");
        mysqli_close($conection);
        exit;
    }

    $dni = $_POST['dni'];
    $query_delete = mysqli_query($conection, "UPDATE clientes SET estatus = 0 WHERE dni='$dni'");

    if ($query_delete) {
        header("location: lista_cliente.php");
    } else {

        $alert= '<div class="alert alert-danger" role="alert">
        Error al suspender el cliente, tiene pedidos realizados.
</div>';
    }
}

if (empty($_REQUEST['id']) || $_REQUEST['id'] == 1) {
    mysqli_close($conection);
    header("location: lista_cliente.php");
} else {

    $dni = $_REQUEST['id'];

    $query = mysqli_query($conection, "SELECT * FROM clientes u 
                                                INNER JOIN islas r 
                                                ON u.isla = r.idisla
                                                WHERE dni='$dni' ");
    mysqli_close($conection);

    $result = mysqli_num_rows($query);

    if ($result > 0) {

        while ($data = mysqli_fetch_array($query)) {
            $dni = $data['dni'];
            $nombre = $data['nombre'];
            $correo = $data['correo'];
            $telefono = $data['telefono'];
            $isla = $data['isla'];
            $direccion = $data['direccion'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/script.php"; ?>
    <title>Eliminar Cliente</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php" ?>
    <section id="container">
        <div class="data_delete">
            <br><br><br>
            <br>
            <h1>¿Está seguro de suspender el siguiente cliente?</h1>
            <br>
            <p>DNI: <span><?php echo $dni; ?></span></p>
            <p>Nombre: <span><?php echo $nombre; ?></span></p>
            <p>correo: <span><?php echo $correo; ?></span></p>
            <p>Teléfono: <span><?php echo $telefono; ?></span></p>
            <p>Isla: <span><?php echo $isla; ?></span></p>
            <p>Direccion: <span><?php echo $direccion; ?></span></p>

            <form method="post" action="">
                <input type="hidden" name="dni" value="<?php echo $dni; ?>">
                <a href="lista_cliente.php" class="btn btn-light">Cancelar</a>
                <input type="submit" value="Suspender" class="btn btn-danger" style="width: 100px; margin-left: 5px;">
                <?php echo isset($alert) ? $alert : ''; ?>
            </form>

        </div>
    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>