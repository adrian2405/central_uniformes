<?php

session_start();

if ($_SESSION['rol'] != 1 && $_SESSION['rol'] != 3) {

    header("location: ./");
}

include "../conexion.php";


//SE EJECUTA AL PULSAR FINALIZAR
if (!empty($_POST)) {

    $alert = '';


    if (empty($_POST['proveedor'])) {

        $alert= '<div class="alert alert-warning" role="alert">
        Todos los campos son obligatorios.
    </div>';

    } else {

            $idproveedor = $_POST['idproveedor'];

            $proveedor = $_POST['proveedor'];

            $query = mysqli_query($conection, "SELECT * FROM proveedores
                                               WHERE (proveedor = '$proveedor' AND idproveedor != $idproveedor)");

            $result = mysqli_fetch_array($query);

            if ($result > 0) {

                $alert= '<div class="alert alert-warning" role="alert">
    El proveedor ya existe.
</div>';

            } else {

                $sql_update = mysqli_query($conection, "UPDATE proveedores
                                                        SET proveedor='$proveedor'
                                                        WHERE idproveedor=$idproveedor");

                if ($sql_update) {

                    $alert= '<div class="alert alert-success" role="alert">
    Proveedor actualizado correctamente.
</div>';

                } else {

                    $alert= '<div class="alert alert-danger" role="alert">
    Error al actualizar el proveedor.
</div>';

                }
            }
    }

    mysqli_close($conection);
}





if (empty($_GET['id'])) {

    header('location: lista_proveedor.php');
}


$id_prov = $_GET['id'];

$query_proveedor = mysqli_query($conection, "SELECT *
                                FROM proveedores
                                WHERE idproveedor=$id_prov");

mysqli_close($conection);

$result_proveedor = mysqli_num_rows($query_proveedor);




if ($result_proveedor == 0) {

    header('location: lista_proveedor.php');

} else {

    while ($data = mysqli_fetch_array($query_proveedor)) {

        $idproveedor = $data['idproveedor'];

        $proveedor = $data['proveedor'];

    }
}



?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/script.php"; ?>
    <title>Actualizar Proveedor</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php"; ?>

    </br><br><br><br>
    <section id="container">
        <div class="form_register">
            </br>
            <h1>Actualizar Proveedor</h1>
            <hr><br>

            <form action="" method="post" style="padding:10px; border-radius: 15px">

                <input type="hidden" name="idproveedor" value="<?php echo $idproveedor; ?>">

                <label for="proveedor">Proveedor</label>
                <input type="text" name="proveedor" id="proveedor" placeholder="Proveedor" value="<?php echo $proveedor; ?>">

                <input type="submit" value="Actualizar proveedor" class="btn btn-info" style="margin-top: 15px">
                <?php echo isset($alert) ? $alert : ''; ?>
            </form>


        </div>
    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>