var app = angular.module("appIsertec",["ngRoute"])
.config(function($routeProvider){
    $routeProvider
    .when("/",{
        controller: "homeController",
        templateUrl: "views/home.html"
    })
    .when("/facturacion",{
        controller:"facturacionController",
        templateUrl: "views/facturar.html"
    })
    .when("/facturacion/listarFacturas",{
        controller: "verFacturasController",
        templateUrl: "views/facturas.html"
    })
        .when("/facturacion/listarClientes",{
        controller: "verClientesController",
        templateUrl: "views/clientes.html"
    })
    .when("/compras",{
        controller:"comprasController",
        templateUrl: "views/comprar.html"
    })
    .when("/compras/listarCompras",{
        controller: "verComprasController",
        templateUrl: "views/compras.html"
    })
        .when("/compras/listarProveedores",{
        controller: "verProveedoresController",
        templateUrl: "views/proveedores.html"
    })
    .when("/produccion",{
        controller: "produccionController",
        templateUrl: "views/produccion.html"
    })
    .when("/produccion/nuevoArticulo",{
        controller: "nuevoArticuloController",
        templateUrl: "views/addProducto.html"
    })
    .when("/facturacion/factura/:id",{
        controller: "detalleFacturaController",
        templateUrl: "views/detalleFactura.html"
    })
    .when("/produccion/deleteArticulo/:codigo",{
        controller: "detalleFacturaController",
        templateUrl: "views/detalleFactura.html"
    })
    .when("/compras/compra/:id",{
        controller: "detalleCompraController",
        templateUrl: "views/detalleCompra.html"
    })
    .otherwise({
        redirectTo: "/"
    })
    
});

$(document).ready(function() {

	$('#registro').submit(function(event) {
		load();
		$.post('servidor/servidor.php',$('#registro').serialize(), function(data, textStatus, xhr) {
			console.log(data);
			if(data.exito){
				$('#registro')[0].reset();
				unload();
				success(data.msg);
			}
			else{
				unload();
				error(data.msg);
			}
		});
		return false;
	});

	$('#login').submit(function(event) {
		load();
		$.post('servidor/servidor.php',$('#login').serialize(), function(data, textStatus, xhr) {
			console.log(data);
			if(data.exito){
				unload();
				location.href = "http://facebook.com";
			}
			else{
				unload();
				error(data.msg);
			}
		});
		return false;
	});
});

function load(){
	$('#mensajes').html('<div class="alert alert-success" role="alert" align="center"><img src="images/loader.gif"></div>');
	$('#btn-enviar').attr('disabled', 'disabled');
}
function unload(){
	$('#mensajes').empty();
	$('#btn-enviar').removeAttr('disabled');
}

function success(msg){
	$('#mensajes').html('<div class="alert alert-success" role="alert">'+msg+'</div>');
}

function error(msg){
	$('#mensajes').html('<div class="alert alert-danger" role="alert">'+msg+'</div>');
}