<?php
session_start();

include "../conexion.php";

$connect = mysqli_connect("localhost", "root", "", "central_uniformes");
$column = array(
    "historial_productos.FECHA_REGISTRO,historial_productos.REFERENCIA", "historial_productos.ID",
    "proveedores.proveedor", "historial_productos.TALLA", "historial_productos.COLOR", "historial_productos.IMAGEN",
    "historial_productos.DESCRIPCION", "historial_productos.PRECIO"
);
$query = "
 SELECT * FROM historial_productos 
 INNER JOIN proveedores 
 ON proveedores.idproveedor = historial_productos.proveedor
";
$query .= " WHERE ";
if (isset($_POST["histo"])) {
    $query .= "historial_productos.proveedor = '" . $_POST["histo"] . "' AND ";
}
if (isset($_POST["search"]["value"])) {
    $query .= '(historial_productos.FECHA_REGISTRO LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR historial_productos.REFERENCIA LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR historial_productos.ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR proveedores.proveedor LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR historial_productos.TALLA LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR historial_productos.COLOR LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR historial_productos.IMAGEN LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR historial_productos.DESCRIPCION LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR historial_productos.PRECIO LIKE "%' . $_POST["search"]["value"] . '%" ) ';
}

if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY historial_productos.REFERENCIA DESC ';
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
    $sub_array[] = $row["FECHA_REGISTRO"];
    $sub_array[] = $row["REFERENCIA"];
    $sub_array[] = $row["ID"];
    $sub_array[] = $row["proveedor"];
    $sub_array[] = $row["TALLA"];
    $sub_array[] = $row["COLOR"];
    $sub_array[] = $row["IMAGEN"];
    $sub_array[] = $row["DESCRIPCION"];
    $sub_array[] = $row["PRECIO"];

    $data[] = $sub_array;
}

function get_all_data($connect)
{
    $query = "SELECT * FROM historial_productos";
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
