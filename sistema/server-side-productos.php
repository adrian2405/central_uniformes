<?php
session_start();

include "../conexion.php";

if (!isset($_SESSION['arra'])) {

    $_SESSION['arra'] = array();
    $_SESSION['arra']['total'] = 0.00;
}

function arr()
{

    if (!empty($_GET['nombre'])) {
        $nombre = $_GET['nombre'];
        $repetido = FALSE;
        foreach ($_SESSION['arra'] as $key => $value) {
            if ($key != 'total') {
                if ($key == $nombre) {
                    $repetido = TRUE;
                }
            }
        }
        if (!$repetido) {
            $_SESSION['arra'][$nombre] = array('cantidad' => 0, 'precio' => 0.00);
        }
    }
}

$connect = mysqli_connect("localhost", "root", "", "central_uniformes");
$column = array("productos.REFERENCIA", "productos.ID", "proveedores.proveedor", "productos.TALLA", "productos.COLOR", "productos.IMAGEN", "productos.DESCRIPCION", "productos.PRECIO");
$query = "
 SELECT * FROM productos 
 INNER JOIN proveedores 
 ON proveedores.idproveedor = productos.proveedor
";
$query .= " WHERE ";
if (isset($_POST["prove"])) {
    $query .= "productos.proveedor = '" . $_POST["prove"] . "' AND ";
}
if (isset($_POST["search"]["value"])) {
    $query .= '(productos.REFERENCIA LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR productos.ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR proveedores.proveedor LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR productos.TALLA LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR productos.COLOR LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR productos.IMAGEN LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR productos.DESCRIPCION LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR productos.PRECIO LIKE "%' . $_POST["search"]["value"] . '%" ) ';
}

if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY productos.REFERENCIA DESC ';
}

$query1 = '';

if ($_POST["length"] != 1) {
    $query1 .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

$result = mysqli_query($connect, $query . $query1);

$data = array();

while ($row = mysqli_fetch_array($result)) {

    $sub_array = array();
    $sub_array[] = $row["REFERENCIA"];
    $sub_array[] = $row["ID"];
    $sub_array[] = $row["proveedor"];
    $sub_array[] = $row["TALLA"];
    $sub_array[] = $row["COLOR"];
    $sub_array[] = $row["IMAGEN"];
    $sub_array[] = $row["DESCRIPCION"];
    $sub_array[] = $row["PRECIO"];


    if (!empty($_SESSION['arra'])) {

        $diferente = 0;

        foreach ($_SESSION['arra'] as $key => $value) {

            if ($row["ID"] != $key) {

                $diferente = $diferente + 1;
            }
        }

        if ($diferente == count($_SESSION['arra'])) {

            $sub_array[] = " <form style='border:none; padding: 0px;'><input type='submit'  style='background: #65ADEC; color: white;' value='Agregar' name='btn_add' >
    <input id='prodId' name='nombre' type='hidden' value='" . $row["ID"] . "'></form>";
        } else {

            $sub_array[] = "<form style='border:none; padding: 0px;'><input type='submit'  style='background: #E04F4F; color: white;' value='Carrito' name='btn_add' >
    <input id='prodId' name='nombre' type='hidden' value='" . $row["ID"] . "'></form>";
        }
    } else {

        $sub_array[] = " <form style='border:none; padding: 0px;'><input type='submit' value='Agregar' name='btn_add' >
    <input id='prodId' name='nombre' type='hidden' value='" . $row["ID"] . "'></form>";
    }

    $data[] = $sub_array;
}

arr();

function get_all_data($connect)
{
    $query = "SELECT * FROM productos";
    $result = mysqli_query($connect, $query);
    return mysqli_num_rows($result);
}

$output = array(
    "draw"    => intval($_POST["draw"]),
    "recordsTotal"  =>  get_all_data($connect),
    "recordsFiltered" => $number_filter_row,
    "data"    => $data
);

echo json_encode($output);
