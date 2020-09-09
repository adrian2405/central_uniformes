<?php

session_start();

include "../conexion.php";

$user = $_SESSION['idUser'];

$connect = mysqli_connect("localhost", "root", "", "central_uniformes");
$column = array(
    "pedidos.id_pedido", "clientes.dni", "pedidos.id_cliente", "pedidos.fecha_registro", "pedidos.fecha_entrega",
    "pedidos.dir_entrega", "pedidos.observacion", "pedidos.precio_total", "pedidos.estado", "estado.estados"
);
$query = "
 SELECT * FROM pedidos 
 INNER JOIN clientes 
 ON clientes.dni = pedidos.id_cliente AND pedidos.id_usuario='$user'
 INNER JOIN estado 
 ON estado.idEstado = pedidos.estado
";

// Consulta para el selector en lista de pedidos
$query .= " WHERE ";
if (isset($_POST["pedt"])) {
    $query .= "estado.estados  = '" . $_POST["pedt"] . "' AND ";
}
if (isset($_POST["search"]["value"])) {
    $query .= '(pedidos.id_pedido LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR pedidos.id_cliente LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR pedidos.fecha_registro LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR pedidos.fecha_entrega LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR pedidos.dir_entrega LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR pedidos.observacion LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR pedidos.precio_total LIKE "%' . $_POST["search"]["value"] . '%") ';
}

if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $column[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY pedidos.id_pedido DESC ';
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
    $sub_array[] = $row["id_pedido"];
    $sub_array[] = $row["nombre"];
    $sub_array[] = $row["fecha_registro"];
    $sub_array[] = $row["fecha_entrega"];

    $sub_array[] = $row["observacion"];
    $sub_array[] = $row["estados"];
    $sub_array[] = $row["precio_total"];
    $sub_array[] = " <form style='border:none; padding: 0px;'>
 <input type='submit'  style='background: #65ADEC; color: white;' class='btnAdd' value='Detalles' name='btn_add' formaction='lectura_pedidos.php'>
 <input id='prodId' name='pedido' type='hidden' value='" . $row["id_pedido"] . "'></form>";
    $data[] = $sub_array;
}

function get_all_data($connect)
{
    $query = "SELECT * FROM pedidos";
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
