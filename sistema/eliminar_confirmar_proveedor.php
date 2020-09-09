<?php

session_start();

if ($_SESSION['rol'] != 1 && $_SESSION['rol'] != 3) {
    header("location: ./");
}

include "../conexion.php";

if (!empty($_POST)) {

    $idproveedor = $_POST['idproveedor'];

    $query_delete = mysqli_query($conection, "DELETE FROM productos WHERE PROVEEDOR=$idproveedor");

    if ($query_delete) {
        header("location: lista_proveedor.php");
    } else {
        $alert= '<div class="alert alert-danger" role="alert">
        Error al eliminar los productos.
</div>';
    }
}

if (empty($_REQUEST['id'])) {
    header("location: lista_proveedor.php");
} else {

    $idproveedor = $_REQUEST['id'];

    $query_proveedor = mysqli_query($conection, "SELECT idproveedor,proveedor
                        FROM proveedores 
                        WHERE idproveedor='$idproveedor'");

    mysqli_close($conection);

    $result_proveedor = mysqli_num_rows($query_proveedor);

    if ($result_proveedor == 0) {
        header('location: lista_proveedor.php');
    } else {
        $option = '';
        while ($data = mysqli_fetch_array($query_proveedor)) {

            $idproveedor = $data['idproveedor'];
            $proveedor = $data['proveedor'];
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

    </br><br><br><br>
    <section id="container">
        <div class="container" style="text-align: center; margin-top:50px;">

            <br><br><br>
            <br>
            <h1>¿Está seguro de eliminar los productos del siguiente proveedor?</h1>
            <br>
            <h2><strong>* Nota: Se eliminarán los productos, no el proveedor. *</strong></h2>
            <br>
            <br>
            <p>ID: <span><?php echo $idproveedor; ?></span></p>
            <p>Nombre: <span><?php echo $proveedor; ?></span></p>

            <form method="post" action="" style="border:none">
                <input type="hidden" name="idproveedor" value="<?php echo $idproveedor; ?>">
                <a href="lista_proveedor.php" class="btn btn-light">Cancelar</a>
                <input type="submit" value="Eliminar" class="btn btn-danger" style="width: 100px; margin-left: 5px;">
                <?php echo isset($alert) ? $alert : ''; ?>
            </form>

        </div>
    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>