<?php

session_start();

if (isset($_POST['enviar'])) {

    $filename = $_FILES["file"]["name"];
    $info = new SplFileInfo($filename);
    $extension = pathinfo($info->getFilename(), PATHINFO_EXTENSION);

    if ($extension == 'csv') {
        #$filename = $_FILES['file']['tmp_name'];
        #$handle = fopen($filename, "r");

        $handle = fopen($_FILES['file']['tmp_name'], "r");

        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

            //si la referencia no esta vacia
            if ($data[0] != "") {

                require_once "../conexion.php";

                //busca repetidos en la bbdd                
                $query = mysqli_query($conection, "SELECT * FROM productos WHERE 
                                                        REFERENCIA = '$data[0]'
                                                        AND PROVEEDOR = '$data[2]'
                                                        AND PRECIO = '$data[7]'
                                                        ");

                $result = mysqli_num_rows($query);

                //si encuentra repetidos no hace nada
                if ($result > 0) {

                    $resultado = TRUE;

                    //si no hay repetidos inserta el nuevo registro
                } elseif ($result == 0) {

                    $q = "INSERT INTO productos values (
                            '$data[0]', 
                            '$data[1]',
                            '$data[2]',
                            '$data[3]',
                            '$data[4]',
                            '$data[5]',
                            '$data[6]',
                            '$data[7]'

                        )";

                    require_once "../conexion.php";

                    $resultado = mysqli_query($conection, $q);


                    //histoial
                    //-------------------------------------------------------

                    $query2 = mysqli_query($conection, "SELECT * FROM historial_productos WHERE 
                                REFERENCIA = '$data[0]'
                                AND PROVEEDOR = '$data[2]'
                                AND PRECIO = '$data[7]'
                                ");

                    $result2 = mysqli_num_rows($query2);



                    //si encuentra repetidos 
                    if ($result2 > 0) {



                        $row2 = mysqli_fetch_array($query2);
                        $viejoid = $row2['ID'];

                        mysqli_query($conection, "UPDATE productos
                                    SET ID= $viejoid  WHERE 
                                    REFERENCIA = '$data[0]'
                                    AND PROVEEDOR = '$data[2]'
                                    AND PRECIO = '$data[7]'");







                        //si no hay repetidos inserta el nuevo registro
                    } elseif ($result == 0) {



                        //busco el id del producto que acabo de meter
                        $result3 = mysqli_query($conection, "SELECT ID FROM productos WHERE 
                                                                    REFERENCIA = '$data[0]'
                                                                    AND PROVEEDOR = '$data[2]'
                                                                    AND PRECIO = '$data[7]'
                                                                    ");


                        $row3 = mysqli_fetch_array($result3);
                        $nuevoid = $row3['ID'];




                        $q = "INSERT INTO historial_productos values (
                                             NOW(),
                                            '$data[0]', 
                                            '$nuevoid',
                                            '$data[2]',
                                            '$data[3]',
                                            '$data[4]',
                                            '$data[5]',
                                            '$data[6]',
                                            '$data[7]'

                                        )";

                        require_once "../conexion.php";

                        $resultado = mysqli_query($conection, $q);
                    }

                    //--------------------------------------------------------











                }
            }
        }

        mysqli_close($conection);

        if ($resultado) {

            $alert = '<div class="alert alert-success" role="alert">
    Operación realizada con éxito.
</div>';
        } else {

            $alert = '<div class="alert alert-warning" role="alert">
            Puede que algo haya salido mal, revise el formato del csv o el proveedor.
</div>';
        }

        fclose($handle);
    } else {

        $alert = '<div class="alert alert-danger" role="alert">
        Error, solo se admiten archivos con extension .csv.
</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/script.php"; ?>
    <title>Registro Producto</title>
</head>

<body class="text-center bg-light">
    <?php include "includes/header.php"; ?>

    </br><br><br><br>
    <section id="container">
        <div class="form_register">
            </br>
            <h1>Registro Producto</h1>
            <hr><br>

            <form enctype="multipart/form-data" method="post" action="" style="padding:10px; border-radius: 15px">
                CSV File:<input type="file" name="file" id="file">
                <input type="submit" value="Enviar" name="enviar" class="btn btn-info" style="margin-top:10px">
                <?php echo isset($alert) ? $alert : ''; ?>
            </form>

        </div>
    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>