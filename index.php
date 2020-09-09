<?php

$alert = '';

session_start();

if (!empty($_SESSION['active'])) {
    header('location: sistema/');
} else {

    if (!empty($_POST)) {
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $alert = '<div class="alert alert-warning" role="alert">
            Ingrese su clave y usuario.
        </div>';
        } else {
            require_once "conexion.php";

            // evita caracteres raros real scape-->
            $user = mysqli_real_escape_string($conection, $_POST['usuario']);
            //md5 encripta contraseña y poner md5 en base datos
            $pass = md5(mysqli_real_escape_string($conection, $_POST['clave']));
            
            

            $query = mysqli_query($conection, "SELECT * FROM usuario WHERE usuario='$user' AND clave ='$pass' AND estatus=1");

            mysqli_close($conection);

            $result = mysqli_num_rows($query);
            if ($result > 0) {

                $data = mysqli_fetch_array($query);

                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $data['idusuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['user'] = $data['usuario'];
                $_SESSION['rol'] = $data['rol'];

                header('location: sistema/');
            } else {

                $alert = '<div class="alert alert-warning" role="alert">
        El usuario o la contraseña no son correctos.
    </div>';
                session_destroy();
            }
        }
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login | Central uniformes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="text-center bg-light">
    <br><br><br><br><br>
    <div class="container" style="width: 300px;">
        <form class="form-signin" action="" method="post">
            <img class="mb-4" src="sistema/img/user.png" alt="" width="150" height="150">
            <h1 class="h3 mb-3 font-weight-normal">Iniciar sesión</h1>
            <input type="text" name="usuario" class="form-control" placeholder="Usuario" required autofocus>
            <input type="password" name="clave" class="form-control" placeholder="Contraseña" required>
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Recordar
                </label>
            </div>
            <input class="btn btn-lg btn-primary btn-block" type="submit" value="Login">
            <br>
            <?php echo isset($alert) ? $alert : ''; ?>
            <p class="mt-5 mb-3 text-muted">&copy; 1987-2020</p>
        </form>
    </div>
</body>

</html>