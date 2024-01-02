<?php

include_once "../data/meta.php";
date_default_timezone_set('America/Costa_Rica');

$dat = json_decode(file_get_contents("php://input"));
$meta = new meta();
if($dat){    
    echo json_encode($meta->detallarCompra($dat->no));
}else{
    $MINCOM  = 1000;
    $MAXCOM = 10000;
    $compra = array(
        "no" => ($meta->noCompra()->COMPRAS)+$MINCOM,
        "fecha" => date("d/m/Y"),
        "direccion" => "",
        "nit" => "",
        "pago" => "0",
        "monto" => 0,
        "bruto" => 0,
        "articulos" => array()
    );
    echo json_encode($compra); 
}

?>
