<?php

session_start();

if ($_SESSION['rol'] != 1) {
    header("location: ./");
}
include "../conexion.php";
if (!empty($_POST)) {
                
    if (empty($_POST['proveedor'])) {

        $alert= '<div class="alert alert-warning" role="alert">
    Todos los campos son obligatorios.
</div>';
    } else {

        $proveedor = $_POST['proveedor'];

        $query = mysqli_query($conection, "SELECT * FROM proveedores WHERE proveedor = '$proveedor'");

        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert= '<div class="alert alert-warning" role="alert">
    El proveedor ya existe.
</div>';
        } else {

            $alert= "Se ha añadido proveedor " . $proveedor;

            $query_insert = mysqli_query($conection, "INSERT INTO proveedores(proveedor)
                            VALUES('$proveedor') ");

            if ($query_insert) {
                $alert= '<div class="alert alert-success" role="alert">
    Proveedor creado correctamente.
</div>';
            } else {
                $alert= '<div class="alert alert-danger" role="alert">
    Error al crear el proveedor.
</div>';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/script.php"; ?>
    <title>Registro Proveedor</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php"; ?>

    <section id="container">
        <div class="form_register">
            </br><br><br><br>
            <h1>Registro Proveedor</h1>
            <hr><br>

            <form action="" method="post" style="padding:10px; border-radius: 15px">

                <label for="proveedor">Proveedor</label>
                <input type="text" name="proveedor" id="proveedor" placeholder="Nombre Proveedor">

                </select>

                <input type="submit" value="Crear Proveedor" class="btn btn-info" style="margin-top: 15px">
                <?php echo isset($alert) ? $alert : ''; ?>
            </form>

        </div>
    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>