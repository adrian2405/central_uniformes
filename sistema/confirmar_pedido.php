<?php

session_start();

include "../conexion.php";


//SI NO EXISTE EL ARRAY DEL CARRITO LO CREA
if (!isset($_SESSION['arra'])) {

    $_SESSION['arra'] = array();
    $_SESSION['arra']['total'] = 0.00;
}

?>







<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!--http-equiv=refresh content=2;lista_compra.php-->
    <?php include "includes/script.php"; ?>
    <title>Carrito</title>
</head>

<body class="text-center bg-light">



    <?php

    include "includes/header.php";

    $dato = "";
    $id = "";


    ?>




    <script>
        <?php


        //COMPRUEBA QUE EL CARRITO NO TENGA ARTICULOS CON CANTIDADES A CERO 
        //Y QUE NO ESTE VACIO
        if (isset($_GET['verificar'])) {

            $verificado = $_GET['verificar'];

            if ($verificado == "vacio") {

                $alert = '<div class="alert alert-warning" role="alert">
                            Agrega productos al carrito para continuar.
                        </div>';
            } elseif ($verificado == "cero") {

                $alert = '<div class="alert alert-warning" role="alert">
                            Asigna una cantidad a todos los productos.
                        </div>';
            }
        }





        ?>



        //ARRAYS DONDE ALMACENO LOS DATOS DE LOS PRODUCTOS DEL CARRITO
        //PARA PROCESARLOS MEJOR EN JS
        var array_js = new Array();
        var array_js2 = new Array();




        function evento(e) {

            //obtengo el id del elemento que lanzo el evento
            var evento = e || window.event;
            var id = evento.target.id;


            var monton = 0.0;


            <?php
            //consulto todos los precios del carrito y los almaceno en un array de javascript
            foreach ($_SESSION['arra'] as $key => $value) {


                //RECORRO TODAS LAS KEYS DEL ARRAY MENOS EL PRECIO TOTAL
                if ($key != 'total') {

                    $query_producto = mysqli_query($conection, "SELECT precio FROM `historial_productos` where id=$key ");

                    $result_producto = mysqli_num_rows($query_producto);

                    if ($result_producto > 0) {


                        foreach (mysqli_fetch_array($query_producto) as $key2 => $value2) {

                            //QUITO SIMBOLO DE MONEDA Y CAMBIO COMAS POR PUNTOS
                            $dato = str_replace("€", "", $value2);
                            $dato = (float) str_replace(",", ".", $dato);

                            //INSERTO DATOS EN EL ARRAY JS
                            echo "array_js['" . $key . "']='" . $dato . "';";
                            echo "array_js2['" . $key . "']='" . $_SESSION['arra'][$key]['cantidad'] . "';";
                        }
                    }
                }
            }

            ?>




            var precioU = 0;
            var precioT = 0;
            var cantidad = 0;



            // calculo el precio total
            for (var a in array_js) {

                if (document.getElementById(a) != null) {

                    var cantidad = parseFloat(document.getElementById(a).value);

                    if (cantidad > 0) {
                        var precioU = parseFloat(array_js[a]);
                        var precioT = parseFloat(cantidad * precioU);
                    } else {
                        var precioT = 0;
                    }

                    array_js[a] = precioT;

                    monton += parseFloat(array_js[a]);




                    //COMPARO LOS DATOS ALMACENADOS EN EL ARRAY CON LOS QUE HA PUESTO EL USUARIO
                    //EN EL FORMULARIO, SI SON DIFERENTES QUIERE DECIR QUE HAY CAMBIOS SIN GUARDAR
                    var sinGuardar = false;

                    for (var a in array_js2) {

                        if (document.getElementById(a) != null) {

                            var nueva_cantidad = parseFloat(document.getElementById(a).value);
                            var vieja_cantidad = parseFloat(array_js2[a]);


                            if (nueva_cantidad != vieja_cantidad) {

                                sinGuardar = true;

                            }
                        }

                    }

                    if (sinGuardar) {

                        document.getElementById('guardar').style.borderColor = "#E04F4F";
                        document.getElementById('guardar').style.backgroundColor = "#E04F4F";
                        document.getElementById('guardar').value = 'Cantidades sin guardar';

                    } else {

                        document.getElementById('guardar').style.borderColor = "#7BDB5D";
                        document.getElementById('guardar').style.backgroundColor = "#7BDB5D";
                        document.getElementById('guardar').value = 'Cantidades guardadas';
                    }



                }

                document.getElementById("p" + id).value = parseFloat(array_js[id]).toFixed(2);
                document.getElementById("monton").value = monton.toFixed(2);

            }

        }

        //RESTABLECE TODOS LOS VALORES DEL FORMULARIO A LOS QUE TENIA GUARDADO EL ARRAY 
        function restablecer(e) {

            document.getElementById('guardar').style.borderColor = "#7BDB5D";
            document.getElementById('guardar').style.backgroundColor = "#7BDB5D";
            document.getElementById('guardar').value = 'Cantidades guardadas';

        }


        //EVITA QUE EL FORMULARIO SE ENVIE AL PULSAR ENTER EN ALGUN INPUT DEL FORMULARIO
        function pulsar(e) {

            var evento = e || window.event;

            var tecla = (document.all) ? evento.keyCode : evento.which;
            return (tecla != 13);

        }
    </script>








    <section>
        <br><br><br>
        <div id="container">

            <form action="datos_cliente_compra.php" method="get">

                <br><br>

                <div style='display: flex; align-items: center;'>

                    <ul style='list-style:none; margin: auto;'>
                        <li style='float:left;margin-top:21px'> <input type="submit" id="guardar" value="Cantidades guardadas" name="guardar" class="btn btn-success" formaction="guardar_carrito.php"> </li>

                    </ul>

                    <ul style='list-style:none; margin: auto;'>
                        <li style='font-weight: bold;'> TOTAL </li>
                        <li style='float:left;'> <input type="text" onkeypress="return pulsar()" id="monton" name='total' value="<?php echo $_SESSION['arra']['total'] ?>" readonly> </li>
                        <li style='float:left; margin-left: 10px;'> <input type="submit" class="btn btn-primary" id="avanzar" value="Avanzar" name="avanzar" style="width:90px; " formaction="verificar_carrito.php"> </li>
                    </ul>

                    <ul style='list-style:none; margin: auto;'>

                        <li style='float:left;margin-top:21px'> <input type='reset' class="btn btn-success" onclick=restablecer()></li>
                        <li style='float:left; margin-left: 10px;margin-top:21px'> <input type="submit" value="Vaciar" name="vaciar" class="btn btn-secondary" formaction="vaciar_carrito.php"> </li>

                    </ul>

                </div>
                <?php echo isset($alert) ? $alert : ''; ?>

                <br>

                <?php

                echo '<br><br><br>';

                foreach ($_SESSION['arra'] as $key => $value) {

                    $query_producto = mysqli_query($conection, "SELECT * FROM historial_productos where ID='$key'");

                    $result = mysqli_num_rows($query_producto);

                    if ($result > 0) {

                        while ($data = mysqli_fetch_array($query_producto)) {

                            echo "<div style='display: flex; align-items: center;'>";

                            echo "<ul style='list-style:none; margin: 0 auto;'>";

                            echo "<li style='float:left;'> <label for='articulo' style='margin-left: 5px;'>Artículo </label> <input type='text'   onkeypress='return pulsar()'  style='margin-top: 25px; width:1000px;'  id='articulo' value='" . "Referencia: " . $data["REFERENCIA"] . " | ID: " . $data["ID"] . " | Talla: " . $data["TALLA"] .  " | Descripción: " . trim($data["DESCRIPCION"]) . " | Color: " . $data["COLOR"] . "' readonly></li>";

                            echo "<li style='float:left;'>  <label for=''" . $data["ID"] . "'' style='margin-left: 25px;'>Cantidad </label> <input type='number'   onkeypress='return pulsar()'   min=0 onchange='evento()' style='margin-top: 25px; margin-left: 25px; width:80px;'  id='" . $data["ID"] . "' value='" . $_SESSION['arra'][$key]['cantidad'] . "'  name='" . $data["ID"] . "' ></li>";

                            echo "<li style='float:left;'>  <label for=''p" . $data["ID"] . "'' style='margin-left: 25px;'>Precio </label> <input type='text'  onkeypress='return pulsar()'    style='margin-top: 25px; margin-left: 25px; width:80px;'  id='p" . $data["ID"] . "' value='" . $_SESSION['arra'][$key]['precio'] . "'  name='p" . $data["ID"] . "'  readonly >   </li>";

                            echo "<li style='float:left;'>  <label for=''b" . $data["ID"] . "'' style='margin-left: 25px;'>Acciones </label> <input type='submit' class='btn btn-danger' style='margin-top: 25px; margin-left: 25px;'  value='Eliminar' name='b" . $data["ID"] . "' formaction='eliminar_producto_carrito.php' > </li>";

                            echo "</ul>";

                            echo "</div>";
                        }
                    }
                }

                ?>

            </form>
        </div>
    </section>

    <?php include "includes/footer.php" ?>
</body>

</html>