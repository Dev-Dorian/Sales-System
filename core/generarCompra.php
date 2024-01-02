<?php

include_once "../data/meta.php";
date_default_timezone_set('America/Costa_Rica');

$compra = json_decode(file_get_contents("php://input"));
$meta = new meta();
if($meta->proveedor($compra->nit)){
    $meta->compraConProveedor(
        $compra->nit,
        $compra->no,
        $compra->fecha,
        $compra->pago,
        $compra->monto,
        $compra->articulos);
    //echo "proveedor exite";
}else
    $meta->compraSinProveedor(
    $compra->no,
    $compra->fecha,
    strtoupper($compra->nombre),
    $compra->nit,
    strtoupper($compra->direccion),
    $compra->pago,
    $compra->monto,
    $compra->articulos);
?>