<?php

include_once "../data/meta.php";
date_default_timezone_set('America/Costa_Rica');

$factura = json_decode(file_get_contents("php://input"));
$meta = new meta();
if($meta->cliente($factura->nit)){
    $meta->facturaConCliente(
        $factura->nit,
        $factura->no,
        $factura->fecha,
        $factura->pago,
        $factura->monto,
        $factura->articulos);
    //echo "cliente exite";
}else
    $meta->facturaSinCliente(
    $factura->no,
    $factura->fecha,
    strtoupper($factura->nombre),
    $factura->nit,
    strtoupper($factura->direccion),
    $factura->pago,
    $factura->monto,
    $factura->articulos);
?>