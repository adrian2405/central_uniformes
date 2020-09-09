<?php

session_start();

if ($_SESSION['rol'] != 1) {
    header("location: ./");
}
include "../conexion.php";

if (!empty($_POST)) {


    if (
        empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) ||
        empty($_POST['clave']) || empty($_POST['rol'])
    ) {

        $alert = '<div class="alert alert-warning" role="alert">
        Todos los campos son obligatorios.
    </div>';
    } else {

        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $user = $_POST['usuario'];
        $clave = md5($_POST['clave']);
        $rol = $_POST['rol'];

        $query = mysqli_query($conection, "SELECT * FROM usuario WHERE usuario = '$user' OR correo =
            '$email'");

        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert= '<div class="alert alert-warning" role="alert">
    El correo o usuario ya existe.
</div>';
        } else {

            $query_insert = mysqli_query($conection, "INSERT INTO usuario(nombre,correo,usuario,clave,rol,estatus)
                            VALUES('$nombre','$email','$user','$clave','$rol', '1') ");

            if ($query_insert) {
                $alert= '<div class="alert alert-success" role="alert">
    Usuario creado correctamente.
</div>';
            } else {
                $alert= '<div class="alert alert-danger" role="alert">
    Error al crear el usuario.
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
    <title>Registro Usuario</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php"; ?>

    <section id="container">
        <div class="form_register">
            </br><br><br><br>
            <h1>Registro Usuario</h1>
            <hr><br>

            <form action="" method="post" style="padding:10px; border-radius: 15px">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">

                <label for="correo">Correo electronico</label>
                <input type="email" name="correo" id="correo" placeholder="correo Electrónico">

                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario">

                <label for="clave">Clave</label>
                <input type="password" name="clave" id="clave" placeholder="Clave de acceso">

                <label for="rol">Tipo Usuario</label>

                <?php

                $query_rol = mysqli_query($conection, "SELECT * FROM rol");
                mysqli_close($conection);

                $result_rol = mysqli_num_rows($query_rol);

                ?>

                <select name="rol" id="rol">

                    <?php
                    if ($result_rol > 0) {
                        while ($rol = mysqli_fetch_array($query_rol)) {

                    ?> <option value="<?php echo $rol['idrol'] ?>"><?php echo $rol['rol'] ?></option>
                    <?php
                        }
                    }

                    ?>

                </select>

                <input type="submit" value="Crear Usuario" class="btn btn-info" style="margin-top: 15px">
                <?php echo isset($alert) ? $alert : ''; ?>
            </form>

        </div>
    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>