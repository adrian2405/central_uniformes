<?php

session_start();

if ($_SESSION['rol'] != 1 && $_SESSION['rol'] != 2) {

    header("location: ./");
}

include "../conexion.php";



//SE EJECUTA AL PULSAR FINALIZAR
if (!empty($_POST)) {

    //INFORMA SI HA OCURRIDO UN ERROR O TODO FUE EXITOSO
    $alert = '';

    //SI FALTA ALGUN CAMPO DEL FORMULARIO POR RELLENAR MUESTRA UN ERROR
    if (

        empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['telefono']) || empty($_POST['isla'])
        || empty($_POST['direccion'])

    ) {

        $alert = '<div class="alert alert-warning" role="alert">
    Todos los campos son obligatorios.
</div>';


        //SI TODO ESTA COMPLETADO INTENTA EDITAR AL CLIENTE
    } else {

        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $isla = $_POST['isla'];
        $direccion = $_POST['direccion'];


        //CONPRUEBA SI EXISTE REPETIDO
        $query = mysqli_query($conection, "SELECT * FROM clientes
                                           WHERE (dni = '$dni' AND correo != $correo)");

        $result = mysqli_fetch_array($query);




        if ($result > 0) {

            $alert = '<div class="alert alert-warning" role="alert">
    El correo o cliente ya existe.
</div>';

            //SI NO EXISTE REPETIDO 
        } else {


            $sql_update = mysqli_query($conection, "UPDATE clientes
                                                    SET nombre='$nombre', correo='$correo', telefono='$telefono', isla='$isla', direccion='$direccion'
                                                    WHERE dni='$dni'");


            //INFORMA SI SE ACTUALIZO CON EXITO O NO
            if ($sql_update) {

                $alert = '<div class="alert alert-success" role="alert">
    Cliente actualizado correctamente.
</div>';
            } else {

                $alert = '<div class="alert alert-danger" role="alert">
    Error al actualizar el cliente.
</div>';
            }
        }
    }

    mysqli_close($conection);
}






if (empty($_GET['id'])) {

    header('location: lista_cliente.php');
}




//MEDIANTE EL ID QUE SE ENVIA DESDE LA LISTA DE CLIENTES BUSCA LOS DATOS 
//PARA RELLENAR EL FORMULARIO
//------------------------------------------------------------------------------------------
$id_cliente = $_GET['id'];

$query_cliente = mysqli_query($conection, "SELECT u.dni, u.nombre,u.correo,u.telefono,u.direccion,(u.isla)
                        as idisla,(r.isla) as isla 
                        FROM clientes u 
                        INNER JOIN islas r 
                        ON u.isla = r.idisla
                        WHERE dni='$id_cliente'");

mysqli_close($conection);

$result_cliente = mysqli_num_rows($query_cliente);
//---------------------------------------------------------------------------------------------






if ($result_cliente == 0) {

    header('location: lista_cliente.php');
} else {

    $option = '';

    //OBTIENE LOS DATOS Y RELLENA EL FORMULARIO
    while ($data = mysqli_fetch_array($query_cliente)) {

        $dni = $data['dni'];
        $nombre = $data['nombre'];
        $correo = $data['correo'];
        $telefono = $data['telefono'];
        $direccion = $data['direccion'];
        $idisla = $data['idisla'];
        $isla = $data['isla'];

        //OBTIENE EL DATO DE LA ISLA
        if ($idisla == 1) {
            $option = ' <option value="' . $idisla . '"select>' . $isla . '</option> ';
        } else if ($idisla == 2) {
            $option = ' <option value="' . $idisla . '"select>' . $isla . '</option>  ';
        } else if ($idisla == 3) {
            $option = ' <option value="' . $idisla . '"select>' . $isla . '</option>  ';
        } else if ($idisla == 4) {
            $option = ' <option value="' . $idisla . '"select>' . $isla . '</option>  ';
        } else if ($idisla == 5) {
            $option = ' <option value="' . $idisla . '"select>' . $isla . '</option>  ';
        } else if ($idisla == 6) {
            $option = ' <option value="' . $idisla . '"select>' . $isla . '</option>  ';
        } else if ($idisla == 7) {
            $option = ' <option value="' . $idisla . '"select>' . $isla . '</option>  ';
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/script.php"; ?>
    <title>Actualizar Cliente</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php"; ?>

    </br><br><br><br>
    <section id="container">
        <div class="form_register">
            <br>
            <h1>Actualizar Cliente</h1>
            <hr><br>

            <form action="" method="post" style="padding:10px; border-radius: 15px">

                <label for="dni">DNI</label>
                <input type="text" name="dni" readonly="true" value="<?php echo $dni; ?>">

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>">

                <label for="correo">Email</label>
                <input type="email" name="correo" id="correo" placeholder="Email" value="<?php echo $correo; ?>">

                <label for="telefono">Teléfono</label>
                <input type="tel" name="telefono" id="telefono" placeholder="Teléfono" value="<?php echo $telefono; ?>" pattern="(6|7|8|9)[ -]*([0-9][ -]*){8}">

                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección" value="<?php echo $direccion; ?>">

                <label for="isla">Isla</label>

                <?php

                include "../conexion.php";

                $query_isla = mysqli_query($conection, "SELECT * FROM islas");

                mysqli_close($conection);

                $result_isla = mysqli_num_rows($query_isla);

                ?>

                <select name="isla" id="isla" class="notItemOne">

                    <?php

                    echo $option;

                    //CARGA LAS ISLAS EN EL SELECTOR
                    if ($result_isla > 0) {

                        while ($isla = mysqli_fetch_array($query_isla)) {

                    ?>


                            <option value="<?php echo $isla['idisla'] ?>"><?php echo $isla['isla'] ?></option>


                    <?php

                        }
                    }

                    ?>

                </select>

                <input type="submit" value="Actualizar cliente" class="btn btn-info" style="margin-top: 15px">
                <?php echo isset($alert) ? $alert : ''; ?>

            </form>

        </div>
    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>