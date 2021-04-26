<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="container">
                <!-- CONTENIDO -->
                <form id="formVenta">
                    <div class="row mt-2 fonde-header-card pt-2">
                        <div class="col-md-12 text-center text-dark">                    
                        <h4>Corte</h4>                    
                        </div>
                    </div>
                    <div class="row fondo-menu py-2">
                        <div class="col-md-4">  
                            <div class="row mt-2">
                                <div class="col-md-6 text-right"><strong>Depósito a cuenta </strong><i class="fas fa-dollar-sign"></i></div>
                                <div class="col-md-6"> 
                                    <input type="text" id="txt_cliente" class="form-control form-control-sm text-capitalize" placeholder="$">
                                </div>
                            </div> 
                            <hr class="mt-2">   
                            <div class="row mt-2">
                                <div class="col-md-6 text-right"><strong>Desglozar corte </strong><i class="fas fa-random"></i></div>
                                <div class="col-md-6"> 
                                    <button type="button" class="btn btn-sm btn-success">Enviar</button>
                                </div>
                            </div>                                          
                        </div>
                        <div class="col-md-4">      
                            <div class="row mt-2">
                                <div class="col-md-1"> 
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input select_facturar" id="customSwitch1">
                                        <label class="custom-control-label" for="customSwitch1"><i class="fas fa-lock"></i></label>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="custom-control custom-switch">
                                        <button type="button" class="btn btn-sm btn-success" disabled>Enviar</button>
                                    </div>
                                </div>
                            </div>               
                        </div>
                        
                        <div class="col-md-4">                    
                            <div class="row mt-2">
                                <div class="col-md-6 text-right"><strong>Caja / Efectivo </strong></div>
                                <div class="col-md-4"> 
                                    <input type="text" id="txt_subtotal" value="0" class="form-control form-control-sm" readonly>
                                </div>
                            </div>    
                            <div class="row mt-2">
                                <div class="col-md-6 text-right"><strong>Cuenta / Banco </strong></div>
                                <div class="col-md-4"> 
                                    <input type="text" id="txt_iva" value="19,536.00" class="form-control form-control-sm" readonly>
                                </div>
                            </div>                  
                        </div>
                    </div>
                                    
                    <div class="row">
                        <div class="col-md-6 offset-6 mb-2">
                            <div id="spinner_loading" class="spinner-border text-warning" role="status" style="display: none;">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
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
                                            <input type="number" autocomplete="off" data-regex="si" size="5" value="0" onclick="this.select()" class="form-control form-control-sm fieldname" id="txtcantidad1" name="1" required="">
                                        </div>                                  
                                        <div class="col-md-7">
                                            <input type="text" name="txt_descripcion1" data-regex="no" size="55" class="form-control form-control-sm fieldname text-capitalize" autocomplete="off" id="txtdescripcion1" required="">
                                        </div>
                                        <div class="col-md-1">
                                            <input type="number" size="5" value="0" autocomplete="off" data-regex="si" onclick="this.select()" class="form-control form-control-sm fieldname" id="txtpunit1" name="1" required="">
                                        </div>
                                        <div class="col-md-1">
                                            <input type="text" class="form-control form-control-sm fieldname" size="5" value="0" id="txttotal1" disabled="">
                                        </div>
                                        <div class="col-md-1">
                                            <input type="number" class="form-control form-control-sm fieldname" data-regex="si" onclick="this.select()" size="5" value="0" id="txtdescuento1" name="1">
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
                                    <button type="button" class="btn btn-sm btn-success btn-block mt-1" id="cobrarNota">  
                                        Cobrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- /CONTENIDO -->
            </div>
        </div>
    </div>
</div>

