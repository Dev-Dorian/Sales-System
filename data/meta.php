<?php
    
include_once "dataBase.php";

class meta{
    private $conDB;
    
    public function __construct(){
        $this->conDB = new DataBase();
    }
    
    public function noFactura(){
        $query = "select count(*) facturas from factura";
        $this->conDB->consulta($query);
        return $this->conDB->obtenerData();
    }
    public function noCompra(){
        $query = "select count(*) compras from compra";
        $this->conDB->consulta($query);
        return $this->conDB->obtenerData();
    }
    public function facturaSinCliente(
        $codFactura,
        $fecha,
        $nombreCliente, 
        $nitCliente, 
        $direccionCliente,
        $tipoPago,
        $monto,
        $articulos
    ){
        $query = "BEGIN FacturarSinCliente(:nombre,:direccion,:nit,:facCodigo,:facfecha,:monto,:tipo); END;";
        $this->conDB->parse($query);
        $this->conDB->vincular(':nombre',$nombreCliente);
        $this->conDB->vincular(':direccion',$direccionCliente);
        $this->conDB->vincular(':nit',$nitCliente);
        $this->conDB->vincular(':facCodigo',$codFactura);
        $this->conDB->vincular(':facfecha',$fecha);
        $this->conDB->vincular(':monto',$monto);
        $this->conDB->vincular(':tipo',$tipoPago);
        $this->conDB->Ejecutar();
        $this->detalleFactura($codFactura,$articulos);
        $this->conDB->cerrarConexion();
        
    }

    //////////

        public function compraSinProveedor(
        $codCompra,
        $fecha,
        $nombreProveedor, 
        $nitProveedor, 
        $direccionProveedor,
        $tipoPago,
        $monto,
        $articulos
    ){
        $query = "BEGIN ComprarSinProveedor(:nombre,:direccion,:nit,:comCodigo,:comfecha,:monto,:tipo); END;";
        $this->conDB->parse($query);
        $this->conDB->vincular(':nombre',$nombreProveedor);
        $this->conDB->vincular(':direccion',$direccionProveedor);
        $this->conDB->vincular(':nit',$nitProveedor);
        $this->conDB->vincular(':comCodigo',$codCompra);
        $this->conDB->vincular(':comfecha',$fecha);
        $this->conDB->vincular(':monto',$monto);
        $this->conDB->vincular(':tipo',$tipoPago);
        $this->conDB->Ejecutar();
        $this->detalleCompra($codCompra,$articulos);
        $this->conDB->cerrarConexion();
        
    }

    /////////
    
    public function facturaConCliente(
        $nit,
        $codFac,
        $fecha,
        $tipo,
        $monto,
        $articulos
    ){
        $query = "BEGIN facturarConCliente(:nit,:facCodigo,:facfecha,:pago,:monto); END;";
        $this->conDB->parse($query);
        $this->conDB->vincular(':nit',$nit);
        $this->conDB->vincular(':facCodigo',$codFac);
        $this->conDB->vincular(':facfecha',$fecha);
        $this->conDB->vincular(':pago',$tipo);
        $this->conDB->vincular(':monto',$monto);
        $this->conDB->Ejecutar();
        $this->detalleFactura($codFac,$articulos);
        $this->conDB->cerrarConexion();
        
    }

    /////

    public function compraConProveedor(
        $nit,
        $codCom,
        $fecha,
        $tipo,
        $monto,
        $articulos
    ){
        $query = "BEGIN comprarConProveedor(:nit,:comCodigo,:comfecha,:pago,:monto); END;";
        $this->conDB->parse($query);
        $this->conDB->vincular(':nit',$nit);
        $this->conDB->vincular(':comCodigo',$codCom);
        $this->conDB->vincular(':comfecha',$fecha);
        $this->conDB->vincular(':pago',$tipo);
        $this->conDB->vincular(':monto',$monto);
        $this->conDB->Ejecutar();
        $this->detalleCompra($codCom,$articulos);
        $this->conDB->cerrarConexion();
        
    }

    /////
    
    public function cliente($nit){
        $query = "select * from cliente where nit = '$nit'";
        $this->conDB->consulta($query);
        $d = $this->conDB->obtenerData();
        if(isset($d->NIT)){
            return $d;
        }else
            return false;
    }

    /////

    public function proveedor($nit){
        $query = "select * from proveedor where nit = '$nit'";
        $this->conDB->consulta($query);
        $d = $this->conDB->obtenerData();
        if(isset($d->NIT)){
            return $d;
        }else
            return false;
    }

    ////

    
    public function detalleFactura($codFac,$articulos){
        foreach($articulos as $articulo){
            $query = "BEGIN detalleFactura(:facCodigo,:artCodigo,:cantidad);END;";
            $this->conDB->parse($query);
            $this->conDB->vincular('facCodigo',$codFac);
            $this->conDB->vincular('artCodigo',$articulo->codigo);
            $this->conDB->vincular('cantidad',$articulo->cantidad);
            $this->conDB->Ejecutar();
        }
    }

    //////

    public function detalleCompra($codCom,$articulos){
        foreach($articulos as $articulo){
            $query = "BEGIN detalleCompra(:comCodigo,:artCodigo,:cantidad);END;";
            $this->conDB->parse($query);
            $this->conDB->vincular('comCodigo',$codCom);
            $this->conDB->vincular('artCodigo',$articulo->codigo);
            $this->conDB->vincular('cantidad',$articulo->cantidad);
            $this->conDB->Ejecutar();
        }
    }

    /////
    
    
    public function obtenerFacturas(){
        $query = "select f.codigo, f.monto, f.fecha, c.nombre from factura f
                    join cliente c
                    on f.cliente_nit = c.nit order by f.codigo desc";
        $this->conDB->consulta($query);
        $factura = array();
        while($r = $this->conDB->obtenerData()){
            array_push($factura,$r);
        }
        return $factura;    
    }

    public function obtenerClientes(){
        $query = "select c.nit, c.nombre, c.direccion from cliente c order by c.nombre";
        $this->conDB->consulta($query);
        $cliente = array();
        while($r = $this->conDB->obtenerData()){
            array_push($cliente,$r);
        }
        return $cliente;    
    }

    public function obtenerProveedores(){
        $query = "select p.nit, p.nombre, p.direccion from proveedor p order by p.nombre";
        $this->conDB->consulta($query);
        $proveedor = array();
        while($r = $this->conDB->obtenerData()){
            array_push($proveedor,$r);
        }
        return $proveedor;    
    }

    public function obtenerCompras(){
        $query = "select c.codigo, c.monto, c.fecha, p.nombre from compra c
                    join proveedor p
                    on c.proveedor_nit = p.nit order by c.codigo desc";
        $this->conDB->consulta($query);
        $compra = array();
        while($r = $this->conDB->obtenerData()){
            array_push($compra,$r);
        }
        return $compra;    
    }
    
    public function detallarFactura($id){
        $query = "select f.codigo codigo, f.monto,f.fecha fecha, c.nit NIT, c.nombre                                     nombre,c.direccion, a.descripcion, df.cantidad, a.precio, f.pago,
            case f.pago 
            when 0 then 'Efectivo'
            when 1 then 'Cheque'
            else 'Tarjeta' end as pago
                 from factura f
                    join cliente c
                    on f.cliente_nit = c.nit
                    join detalle_factura df
                    on df.factura_codigo = f.codigo
                    join articulo a
                    on df.articulo_codigo = a.codigo where f.codigo = '$id'";
        $this->conDB->consulta($query);
        $factura = array();
        while($r = $this->conDB->obtenerData()){
            array_push($factura,$r);
        }
        return $factura;
    }

    /////////

    public function detallarCompra($id){
        $query = "select c.codigo codigo, c.monto,c.fecha fecha, p.nit NIT, p.nombre nombre,p.direccion, a.descripcion, dc.cantidad, a.precio, c.pago,
            case c.pago 
            when 0 then 'Efectivo'
            when 1 then 'Cheque'
            else 'Tarjeta' end as pago
                 from compra c
                    join proveedor p
                    on c.proveedor_nit = p.nit
                    join detalle_compra dc
                    on dc.compra_codigo = c.codigo
                    join articulo a
                    on dc.articulo_codigo = a.codigo where c.codigo =  '$id'";
        $this->conDB->consulta($query);
        $compra = array();
        while($r = $this->conDB->obtenerData()){
            array_push($compra,$r);
        }
        return $compra;
    }

    /////////
    
    public function busquedaArticulos($search){
        $query = "select * from articulo where codigo like '%".strtoupper($search[0])."%' or descripcion like '%".strtoupper($search[0])."%'";
        $this->conDB->consulta($query);
        $articulos = array();
        while($row = $this->conDB->obtenerData()){
            array_push($articulos,$row);
        }
        return $articulos;
    }
    
    
    public function insertarArticulo($codigo,$descripcion,$precio,$existencia){
        $query = "BEGIN nuevoArticulo(:codigo,:descripcion,:precio,:existencia); END;";
        $this->conDB->parse($query);
        $this->conDB->vincular(':codigo',$codigo);
        $this->conDB->vincular(':descripcion',$precio);
        $this->conDB->vincular(':precio',$descripcion);
        $this->conDB->vincular(':existencia',(int)$existencia);
        $this->conDB->Ejecutar();
        $this->conDB->cerrarConexion();
    }


    
    public function updateArticulo($codigo,$descripcion,$precio,$existencia){
        #$query = "insert into articulo values(incremento.NextVal,'$precio','$existencia')";
        
    }
    
    public function obtenerArticulos(){
        $query = "select * from articulo order by codigo";
        $this->conDB->consulta($query);
        $articulos = array();
        while($r = $this->conDB->obtenerData())
            array_push($articulos,$r);
        return $articulos;
    }
    
    public function actualizarExistenciaArticulo($codigo,$existencia){
        $query = "update articulo set existencia = $existencia where codigo = $codigo";
        $this->conDB->consulta($query);
    }
}

?>