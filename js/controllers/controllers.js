app
    .controller("homeController", function ($scope) {
        $scope.pagina = "Home";

    })

.controller("facturacionController", function ($scope,$http,$location) {
    $scope.query = "";
    $scope.cantidad = 1;
    iniciarFactura();
    
    function iniciarFactura(){
        $http.get("core/iniciarFactura.php")
        .success(function(data){
            console.log(data);
            $scope.factura = data;
        })
        .error(function(err){
            console.log(err);
        })
    };
    
    
    function totales(){
        $scope.factura.bruto = 0;
        $scope.factura.monto = 0;
        for(i=0;i<$scope.factura.articulos.length;i++){
            $scope.factura.bruto = $scope.factura.bruto + $scope.factura.articulos[i].cantidad;
            $scope.factura.monto = $scope.factura.monto + $scope.factura.articulos[i].importe;
        }
    }
    
    $scope.addItem = function(item,cantidad){
        var p = true;
        var nItem = {
            codigo: item.CODIGO,
            cantidad: cantidad,
            descripcion: item.DESCRIPCION,
            precio: item.PRECIO,
            importe: item.PRECIO*cantidad
        };    
        for(i=0;i < ($scope.factura.articulos.length);i++){
            if(nItem.codigo == $scope.factura.articulos[i].codigo){
                $scope.factura.articulos[i].cantidad = $scope.factura.articulos[i].cantidad + nItem.cantidad;
                $scope.factura.articulos[i].importe = $scope.factura.articulos[i].importe + (nItem.cantidad*nItem.precio);
                p = false;
            }
        }
        if(p){
            $scope.factura.articulos.push(nItem);
        }
        totales();    
        $scope.query = "";
        $scope.items= [];
    };
    
    
    
    
    $scope.search = function () {
        if ($scope.query != "") {
            $http.post("core/busquedaArticulos.php",{query: $scope.query})
            .success(function(data){
                console.log(data);
                $scope.items = data;
                //console.log(data);
            })
            .error(function(err){
                
            })
        } else {
            $scope.items = [];
        }
    };
    
    $scope.comprobarNit = function(){
        /*if($scope.factura.nit != ""){
            $http.post("core/comprobarCliente.php",{nit: $scope.factura.nit})
            .success(function(data){
                console.log(data);
                $scope.factura = data;
            })
            .error(function(error){
                console.log(error);
                
            });
            
        }*/ 
    };
    
    $scope.addFactura = function (){
      if($scope.factura.articulos.length > 0){
          $http.post("core/generarFactura.php",$scope.factura)
              .success(function(data){
              console.log(data);
              $location.path("/facturacion/factura/"+$scope.factura.no);
              iniciarFactura();
          })
      .error(function(err){
          console.log(err);
      })
          
      }else
          $scope.error = true;
    };
    
})


///////////////COMPRAS ABRE ////

.controller("comprasController", function ($scope,$http,$location) {
    $scope.query = "";
    $scope.cantidad = 1;
    iniciarCompra();
    
    function iniciarCompra(){
        $http.get("core/iniciarCompra.php")
        .success(function(data){
            console.log(data);
            $scope.compra = data;
        })
        .error(function(err){
            console.log(err);
        })
    };
    
    
    function totales(){
        $scope.compra.bruto = 0;
        $scope.compra.monto = 0;
        for(i=0;i<$scope.compra.articulos.length;i++){
            $scope.compra.bruto = $scope.compra.bruto + $scope.compra.articulos[i].cantidad;
            $scope.compra.monto = $scope.compra.monto + $scope.compra.articulos[i].importe;
        }
    }
    
    $scope.addItem = function(item,cantidad){
        var p = true;
        var nItem = {
            codigo: item.CODIGO,
            cantidad: cantidad,
            descripcion: item.DESCRIPCION,
            precio: item.PRECIO,
            importe: item.PRECIO*cantidad
        };    
        for(i=0;i < ($scope.compra.articulos.length);i++){
            if(nItem.codigo == $scope.compra.articulos[i].codigo){
                $scope.compra.articulos[i].cantidad = $scope.compra.articulos[i].cantidad + nItem.cantidad;
                $scope.compra.articulos[i].importe = $scope.compra.articulos[i].importe + (nItem.cantidad*nItem.precio);
                p = false;
            }
        }
        if(p){
            $scope.compra.articulos.push(nItem);
        }
        totales();    
        $scope.query = "";
        $scope.items= [];
    };
    
    
    
    
    $scope.search = function () {
        if ($scope.query != "") {
            $http.post("core/busquedaArticulos.php",{query: $scope.query})
            .success(function(data){
                console.log(data);
                $scope.items = data;
                //console.log(data);
            })
            .error(function(err){
                
            })
        } else {
            $scope.items = [];
        }
    };
    
    $scope.comprobarNit = function(){
        /*if($scope.factura.nit != ""){
            $http.post("core/comprobarCliente.php",{nit: $scope.factura.nit})
            .success(function(data){
                console.log(data);
                $scope.factura = data;
            })
            .error(function(error){
                console.log(error);
                
            });
            
        }*/ 
    };
    
    $scope.addCompra = function (){
      if($scope.compra.articulos.length > 0){
          $http.post("core/generarCompra.php",$scope.compra)
              .success(function(data){
              console.log(data);
              $location.path("/compras/compra/"+$scope.compra.no);
              iniciarCompra();
          })
      .error(function(err){
          console.log(err);
      })
          
      }else
          $scope.error = true;
    };
    
})

////////////// COMPRAS CIERRA //////

.controller("verFacturasController", function ($scope, $http) {
    $http.get("core/verFacturas.php")
    .success(function(data){
        $scope.facturas = data;
       console.log(data); 
    })
    .error(function(err){
        console.log(data);
    });
    
})

.controller("verClientesController", function ($scope, $http) {
    $http.get("core/verClientes.php")
    .success(function(data){
        $scope.clientes = data;
       console.log(data); 
    })
    .error(function(err){
        console.log(data);
    });
    
})

.controller("verProveedoresController", function ($scope, $http) {
    $http.get("core/verProveedores.php")
    .success(function(data){
        $scope.proveedores = data;
       console.log(data); 
    })
    .error(function(err){
        console.log(data);
    });
    
})

.controller("verComprasController", function ($scope, $http) {
    $http.get("core/verCompras.php")
    .success(function(data){
        $scope.compras = data;
       console.log(data); 
    })
    .error(function(err){
        console.log(data);
    });
    
})

.controller("produccionController",function($scope,$http){
    $http.get("core/verArticulos.php")
    .success(function(data){
        console.log(data);
        $scope.articulos = data;
    })
    
    
})

.controller("nuevoArticuloController",function($scope,$http,$location){
    $scope.articulo = {};
    $scope.addArticulo = function(){
        /* Funcion para hacer llamado http  y enviar datos a agregar*/
        $http.post("core/insertArticulo.php",$scope.articulo)
        .success(function(data){
            console.log(data);
            $scope.articulo = {};
            $location.path('/produccion');
        })
        .error(function(err){
           console.log(err); 
        });
    }
})

.controller("eliminarArticuloController",function($scope,$http,$location){
    $scope.articulo = {};
    $scope.deleteArticulo = function(){
        /* Funcion para hacer llamado http  y enviar datos a agregar*/
        $http.post("core/deleteArticulo.php",$scope.articulo)
        .success(function(data){
            console.log(data);
            $scope.articulo = {};
            $location.path('/produccion');
        })
        .error(function(err){
           console.log(err); 
        });
    }
})



.controller("detalleFacturaController",function($scope,$http,$routeParams,$location){
    //$scope.factura = [];
    $http.post("core/iniciarFactura.php",{no: $routeParams.id})
    .success(function(data){
       
        $scope.factura = data;
        
    console.log($scope.factura);
    })
    .error(function(err){
        console.log(data);
    });
})

.controller("detalleCompraController",function($scope,$http,$routeParams,$location){
    //$scope.compra = [];
    $http.post("core/iniciarCompra.php",{no: $routeParams.id})
    .success(function(data){
       
        $scope.compra = data;
        
    console.log($scope.compra);
    })
    .error(function(err){
        console.log(data);
    });
})