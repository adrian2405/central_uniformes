<?php

session_start();

if ($_SESSION['rol'] != 1) {
    header("location: ./");
}

include "../conexion.php";

if (!empty($_POST)) {

    $alert = '';

    if (
        empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) ||
        empty($_POST['rol'])
    ) {

        $alert = '<div class="alert alert-warning" role="alert">
        Todos los campos son obligatorios.
    </div>';
    } else {

        $idUsuario = $_POST['idUsuario'];
        $nombre = $_POST['nombre'];
        $email = $_POST['correo'];
        $user = $_POST['usuario'];
        $clave = md5($_POST['clave']);
        $rol = $_POST['rol'];

        $query = mysqli_query($conection, "SELECT * FROM usuario 
                                                       WHERE (usuario = '$user' AND idUsuario != $idUsuario)
                                                       OR (correo='$email' AND idUsuario != idUsuario)
                                                       ");

        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert= '<div class="alert alert-warning" role="alert">
    El correo o usuario ya existe.
</div>';
        } else {

            if (empty($_POST['clave'])) {

                $sql_update = mysqli_query($conection, "UPDATE usuario
                                                            SET nombre='$nombre', correo='$email',
                                                            usuario='$user', rol='$rol'
                                                            WHERE idusuario=$idUsuario");
            } else {

                $sql_update = mysqli_query($conection, "UPDATE usuario
                                                            SET nombre='$nombre', correo='$email',
                                                            usuario='$user', clave='$clave',rol='$rol'
                                                            WHERE idusuario=$idUsuario");
            }

            if ($sql_update) {
                $alert= '<div class="alert alert-success" role="alert">
    Usuario actualizado correctamente.
</div>';
            } else {
                $alert= '<div class="alert alert-danger" role="alert">
    Error al actualizar el usuario.
</div>';
            }
        }
    }

    mysqli_close($conection);
}
//mostrar datos

if (empty($_GET['id'])) {
    header('location: lista_usuario.php');
}
$id_user = $_GET['id'];

$query_usuario = mysqli_query($conection, "SELECT u.idusuario, u.nombre,u.correo,u.usuario,(u.rol)
                        as idrol,(r.rol) as rol 
                        FROM usuario u 
                        INNER JOIN rol r 
                        ON u.rol = r.idrol
                        WHERE idusuario=$id_user");

mysqli_close($conection);

$result_usuario = mysqli_num_rows($query_usuario);

if ($result_usuario == 0) {
    header('location: lista_usuario.php');
} else {
    $option = '';
    while ($data = mysqli_fetch_array($query_usuario)) {

        $iduser = $data['idusuario'];
        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $usuario = $data['usuario'];
        $idrol = $data['idrol'];
        $rol = $data['rol'];

        if ($idrol == 1) {
            $option = ' <option value="' . $idrol . '"select>' . $rol . '</option> ';
        } else if ($idrol == 2) {
            $option = ' <option value="' . $idrol . '"select>' . $rol . '</option>  ';
        } else if ($idrol == 3) {
            $option = ' <option value="' . $idrol . '"select>' . $rol . '</option>  ';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/script.php"; ?>
    <title>Actualizar Usuario</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php"; ?>

    </br><br><br><br>
    <section id="container">
        <div class="form_register">
            </br>
            <h1>Actualizar Usuario</h1>
            <hr><br>
            <!-- isset if simpficado-->

            <form action="" method="post" style="padding:10px; border-radius: 15px">
                <!--se envlaza al name nombre-->

                <input type="hidden" name="idUsuario" value="<?php echo $iduser; ?>">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" value="<?php echo $nombre; ?>">

                <label for="correo">Correo electronico</label>
                <input type="email" name="correo" id="correo" placeholder="correo Electrónico" value="<?php echo $correo; ?>">

                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $usuario; ?>">

                <label for="clave">Clave</label>
                <input type="password" name="clave" id="clave" placeholder="Clave de acceso">

                <label for="rol">Tipo Usuario</label>

                <?php
                include "../conexion.php";
                $query_rol = mysqli_query($conection, "SELECT * FROM rol");
                mysqli_close($conection);
                $result_rol = mysqli_num_rows($query_rol);

                ?>

                <select name="rol" id="rol" class="notItemOne">

                    <?php
                    echo $option;
                    if ($result_rol > 0) {
                        while ($rol = mysqli_fetch_array($query_rol)) {

                    ?> <option value="<?php echo $rol['idrol'] ?>"><?php echo $rol['rol'] ?></option>
                    <?php
                        }
                    }

                    ?>

                </select>

                <input type="submit" value="Actualizar usuario" class="btn btn-info" style="margin-top: 15px">
                <?php echo isset($alert) ? $alert : ''; ?>
            </form>


        </div>
    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>