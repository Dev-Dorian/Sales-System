<?php
include_once "../data/meta.php";
$proveedor = json_decode(file_get_contents("php://input"));

//echo $cliente->nit;

$meta = new meta();
if($c = $meta->proveedor($proveedor->nit))
    echo json_encode($c);

?>