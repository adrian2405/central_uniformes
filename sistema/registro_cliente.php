<?php

session_start();

if ($_SESSION['rol'] != 1 && $_SESSION['rol'] != 2) {
    header("location: ./");
}
include "../conexion.php";
if (!empty($_POST)) {

    if (
        empty($_POST['dni']) || empty($_POST['nombre']) || empty($_POST['correo']) ||
        empty($_POST['telefono']) || empty($_POST['isla']) || empty($_POST['direccion'])
    ) {

        $alert = '<div class="alert alert-warning" role="alert">
    Todos los campos son obligatorios.
</div>';
    } else {

        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $telefono = $_POST['telefono'];
        $isla = $_POST['isla'];
        $direccion = $_POST['direccion'];

        $query = mysqli_query($conection, "SELECT * FROM clientes WHERE nombre = '$nombre' OR correo =
            '$correo'");

        $result = mysqli_fetch_array($query);

        if ($result > 0) {
            $alert = '<div class="alert alert-warning" role="alert">
    El correo o cliente ya existe.
</div>';
        } else {

            $query_insert = mysqli_query($conection, "INSERT INTO clientes(dni,nombre,correo,telefono,isla,direccion,estatus)
                            VALUES('$dni','$nombre','$correo','$telefono','$isla', '$direccion', 1) ");

            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
    Cliente creado correctamente.
</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
    Error al crear el cliente.
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
    <title>Registro Cliente</title>

</head>

<body class="text-center bg-light">
    <?php include "includes/header.php"; ?>

    <section id="container">
        <div class="form_register">
            </br><br><br><br>
            <h1>Registro Cliente</h1>
            <hr><br>

            <form action="" method="post" style="padding:10px; border-radius: 15px">

                <fieldset>
                    <legend>Tipo de documento:</legend>
                    <div class="lg">

                        <input type="radio" id="cb-dni" name="document" value="DNI" class="dni_cif_input" checked>
                        <label for="cb-dni" class="dni_cif_label">DNI</label>

                        <input type="radio" id="cb-cif" name="document" class="dni_cif_input" value="CIF">
                        <label for="cb-cif" class="dni_cif_label">CIF</label>

                        <label for="dni"></label>
                        <input type="text" name="dni" value="" id="dni" placeholder="Documento" required>

                    </div>
                </fieldset>

                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">

                <label for="correo">Correo electronico</label>
                <input type="email" name="correo" id="correo" placeholder="Correo Electrónico">

                <label for="telefono">Teléfono</label>
                <input type="tel" name="telefono" id="telefono" placeholder="Teléfono de contacto" pattern="(6|7|8|9)[ -]*([0-9][ -]*){8}">

                <label for="isla">Isla</label>

                <?php

                $query_isla = mysqli_query($conection, "SELECT * FROM islas");
                mysqli_close($conection);

                $result_isla = mysqli_num_rows($query_isla);

                ?>

                <select name="isla" id="isla">

                    <?php
                    if ($result_isla > 0) {
                        while ($isla = mysqli_fetch_array($query_isla)) {

                    ?> <option value="<?php echo $isla['idisla'] ?>"><?php echo $isla['isla'] ?></option>
                    <?php
                        }
                    }

                    ?>

                </select>

                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" placeholder="Dirección">

                <input type="submit" value="Crear Cliente" class="btn btn-info" style="margin-top: 15px" id="crear" name="crear">
                <?php echo isset($alert) ? $alert : ''; ?>

            </form>

        </div>

        <script type="text/javascript">
            document.getElementById('dni').onblur = function() {

                dni = document.getElementById('dni').value.toUpperCase();
                document.getElementById('dni').value = dni;
                expresionDni = /^\d{8}[a-zA-Z]$/;
                expresionCif = /^[a-zA-Z]{1}\d{7}[a-zA-Z0-9]{1}$/;
                //expresionNie=/^[XxTtYyZz]{1}[0-9]{7}[a-zA-Z]{1}$/;
                // alert (" checked " );

                if (document.getElementById('cb-dni').checked) {


                    if (expresionDni.test(dni) == true) {
                        numero = dni.substr(0, dni.length - 1);
                        let = dni.substr(dni.length - 1, 1);
                        numero = numero % 23;

                        letra = 'TRWAGMYFPDXBNJZSQVHLCKET';
                        letra = letra.substring(numero, numero + 1);

                        document.getElementById('crear').style.background = "green";
                        document.getElementById("dni").style.border = "1px solid #aba8a8";
                        document.getElementById('crear').disabled = false;


                        if (letra !=
                            let.toUpperCase()) {

                            document.getElementById('crear').style.background = "red";
                            document.getElementById("dni").style.borderColor = "red";

                            document.getElementById('crear').disabled = true;
                        }

                    }


                } else if (document.getElementById('cb-cif').checked) {

                    if (expresionCif.test(dni) == true) {

                        var encontrado = false;

                        var v1 = new Array(0, 2, 4, 6, 8, 1, 3, 5, 7, 9);
                        var numero = new Array(1, 2, 3, 4, 5, 6, 7, 8, 9);
                        var letra = new Array("A", "B", "C", "D", "E", "F", "G", "H", "K", "L", "M", "N", "P", "Q", "S");
                        var codigo = new Array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J");

                        var temp = 0;
                        var temp1;

                        for (i = 2; i <= 6; i += 2) {
                            temp = temp + v1[parseInt(dni.substr(i - 1, 1))];
                            temp = temp + parseInt(dni.substr(i, 1));
                        };

                        temp = temp + v1[parseInt(dni.substr(7, 1))];

                        verification = dni.substr(8, 1);
                        verPrin = dni.substr(0, 1);

                        //se comprueba que exista una de las letras del principio
                        for (var i = 0; i < 15; i++) {


                            if (letra[i] == verPrin) {
                                encontrado = true;
                            }


                        }



                        temp = (10 - (temp % 10));

                        if (encontrado == true) {

                            if (temp == 10) {

                                if (verification == 0) {

                                    document.getElementById('crear').style.background = "green";
                                    document.getElementById("dni").style.border = "1px solid #aba8a8";
                                    document.getElementById('crear').disabled = false;

                                } else {


                                    document.getElementById('crear').style.background = "red";
                                    document.getElementById("dni").style.borderColor = "red";

                                    document.getElementById('crear').disabled = true;
                                }


                            } else {


                                if (isNaN("verification")) {
                                    if (verification == codigo[temp - 1]) {

                                        document.getElementById('crear').style.background = "green";
                                        document.getElementById("dni").style.border = "1px solid #aba8a8";
                                        document.getElementById('crear').disabled = false;

                                    } else if (verification == numero[temp - 1]) {

                                        document.getElementById('crear').style.background = "green";
                                        document.getElementById("dni").style.border = "1px solid #aba8a8";
                                        document.getElementById('crear').disabled = false;

                                    } else {

                                        document.getElementById('crear').style.background = "red";
                                        document.getElementById("dni").style.borderColor = "red";

                                        document.getElementById('crear').disabled = true;
                                    }



                                }






                            }



                        } else {

                            //$dni_codes = 'TRWAGMYFPDXBNJZSQVHLCKE';
                            document.getElementById("dni").style.borderColor = "red";
                            //document.getElementById("dni").focus();
                            document.getElementById('crear').style.background = "red";
                            document.getElementById('crear').disabled = true;
                            //alert('error ' + dni);


                        }

                    }
                } else {
                    //$dni_codes = 'TRWAGMYFPDXBNJZSQVHLCKE';
                    document.getElementById("dni").style.borderColor = "red";
                    //document.getElementById("dni").focus();
                    document.getElementById('crear').style.background = "red";
                    document.getElementById('crear').disabled = true;
                    //alert('error ' + dni);
                }



            }
        </script>

    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>