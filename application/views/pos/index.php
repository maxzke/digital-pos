<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="container">
                <!-- CONTENIDO -->
                <div class="row my-2 fondo-accesorios pt-2">
                    <div class="col-md-12 text-center text-dark">                    
                    <h4>Nota de Venta</h4>                    
                    </div>
                </div>
                <div class="row fondo-menu py-1">
                    <div class="col-md-4">  
                        <div class="row mt-2">
                            <div class="col-md-4 text-right"><strong>Cliente </strong><i class="fas fa-user"></i></div>
                            <div class="col-md-8"> 
                                <input type="text" id="txt_cliente" class="form-control form-control-sm text-capitalize" placeholder="Nombre">
                            </div>
                        </div>    
                        <div class="row mt-1">
                            <div class="col-md-4 text-right"><strong>Dirección </strong><i class="fas fa-map-marker-alt"></i></div>
                            <div class="col-md-8"> 
                                <input type="text" id="txt_direccion" class="form-control form-control-sm text-capitalize" placeholder="Lugar, calle">
                            </div>
                        </div>    
                        <div class="row mt-1">
                            <div class="col-md-4 text-right"><strong>Telefono </strong><i class="fas fa-mobile-alt"></i></div>
                            <div class="col-md-8"> 
                                <input type="text" id="txt_telefono" class="form-control form-control-sm" placeholder="10 digitos">
                            </div>
                        </div>    
                        <div class="row mt-1">
                            <div class="col-md-4 text-right"><strong>Empresa </strong><i class="fas fa-building"></i></div>
                            <div class="col-md-8"> 
                                <input type="text" id="txt_empresa" class="form-control form-control-sm text-capitalize" placeholder="Nombre">
                            </div>
                        </div>                                            
                    </div>
                    <div class="col-md-3 offset-1">                    
                        <div class="row mt-1">
                            <div class="col-md-12"> 
                                <strong> Folio: 0001</strong>
                            </div>
                        </div>    
                        <div class="row mt-1">
                            <div class="col-md-12"> 
                                <strong>Fecha: </strong><strong>16/04/2021</strong>
                            </div>
                        </div>    
                        <div class="row mt-1"><div class="col-md-12"> 
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input select_facturar" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1"><strong>Facturar</strong></label>
                                </div>
                            </div>
                        </div>    
                        <div class="row mt-1"><div class="col-md-12"> 
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input chkCotizacion" id="customSwitch2">
                                    <label class="custom-control-label" for="customSwitch2"><strong>Guardar Cotización</strong></label>
                                </div>
                            </div>
                        </div>                    
                    </div>
                    <div class="col-md-4">                    
                        <div class="row">
                            <div class="col-md-4 text-right"><strong>Subtotal </strong></div>
                            <div class="col-md-3"> 
                                <input type="text" id="txt_subtotal" value="0" size="5" class="form-control form-control-sm" disabled="">
                            </div>
                        </div>    
                        <div class="row">
                            <div class="col-md-4 text-right"><strong>Iva </strong></div>
                            <div class="col-md-3"> 
                                <input type="text" id="txt_iva" size="5" class="form-control form-control-sm" disabled="" value="0">
                            </div>
                        </div>    
                        <div class="row">
                            <div class="col-md-4 text-right"><strong>Total </strong></div>
                            <div class="col-md-3"> 
                                <input type="text" id="txt_total" size="5" class="form-control form-control-sm" disabled="" value="0">
                            </div>
                        </div>    
                        <div class="row">
                            <div class="col-md-4 text-right"><strong>Abono </strong></div>
                            <div class="col-md-3"> 
                                <input type="text" id="txt_abono" size="5" class="form-control form-control-sm" value="0" autocomplete="off">
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-4 text-right"><strong>Restante </strong></div>
                            <div class="col-md-3"> 
                                <input type="text" id="txt_resta" size="5" class="form-control form-control-sm" disabled="" value="0">
                            </div>
                        </div>                   
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="card bg-white">
                            <div class="card-header fonde-header-card">
                                <div class="row px-3">
                                    <div class="col-md-1">Cantidad</div>
                                    <div class="col-md-7">Descripción</div>
                                    <div class="col-md-1">Precio</div>
                                    <div class="col-md-1">Total</div>
                                    <div class="col-md-1">Desc %</div>
                                </div>
                            </div>
                            <div class="card-body bg-white" id="buildyourform">
                            <div class="row">
                                      <div class="col-md-1">
                                        <input type="text" autocomplete="off" size="5" value="0" class="form-control form-control-sm fieldname" id="txtcantidad1" name="1" required="">
                                      </div>                                  
                                      <div class="col-md-7">
                                        <input type="text" size="55" class="form-control form-control-sm fieldname text-uppercase" autocomplete="off" id="txtdescripcion1" required="">
                                      </div>
                                      <div class="col-md-1">
                                        <input type="text" size="5" value="0" autocomplete="off" class="form-control form-control-sm fieldname" id="txtpunit1" name="1" required="">
                                      </div>
                                      <div class="col-md-1">
                                        <input type="text" class="form-control form-control-sm fieldname" size="5" value="0" id="txttotal1" disabled="">
                                      </div>
                                      <div class="col-md-1">
                                        <input type="text" class="form-control form-control-sm fieldname" size="5" value="0" id="txtdescuento1" name="1">
                                      </div>
                                      <div class="col-md-1">
                                        <input type="hidden" value="0" class="total_hidden" id="txttotal_hidden1">
                                      </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 offset-9 text-right">
                                <button type="button" id="add" class="btn btn-sm btn-info mt-1"><i class="fas fa-plus"></i> Producto</button>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-sm btn-success btn-block mt-1" id="cobrarNota">Cobrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /CONTENIDO -->
            </div>
        </div>
    </div>
</div>