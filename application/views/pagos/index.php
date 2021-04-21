<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="container">
                <!-- CONTENIDO -->
                <form id="formPago">
                    <div class="row fonde-header-card pt-2 my-2">
                        <div class="col-md-12 text-center">
                            <h4>Pagos</h4>
                        </div>
                    </div>
                    
                    <div class="row fondo-menu py-1">
                        <div class="col-md-4">  
                            <div class="row mt-3">
                                <div class="col-md-4 text-right"><strong>Proveedor </strong><i class="fas fa-user"></i></div>
                                <div class="col-md-8"> 
                                    <input type="text" id="txt_cliente" class="form-control form-control-sm text-capitalize" placeholder="Nombre / Descripción">
                                </div>
                            </div>    
                            <div class="row mt-1">
                                <div class="col-md-4 text-right"><strong>Folio </strong><i class="fas fa-file-invoice"></i></div>
                                <div class="col-md-8"> 
                                    <input type="text" id="txt_direccion" class="form-control form-control-sm text-capitalize" placeholder="Número">
                                </div>
                            </div>    
                            <!-- <div class="row mt-1">
                                <div class="col-md-4 text-right"><strong>Importe </strong><i class="fas fa-dollar-sign"></i></div>
                                <div class="col-md-8"> 
                                    <input type="text" id="txt_telefono" class="form-control form-control-sm" placeholder="Neto pagado">
                                </div>
                            </div>                                                -->
                        </div>
                        <div class="col-md-3 offset-1">      
                            <div class="row mt-2">
                                <div class="col-md-12"> 
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitchPagos">
                                        <label class="custom-control-label" for="customSwitchPagos"><strong>Facturado</strong></label>
                                    </div>
                                </div>
                            </div>    
                            <div class="row mt-3">
                                    <div class="col-md-5"> 
                                        <select class="custom-select custom-select-sm" id="metodoPago">
                                            <option value="Efectivo" selected>Efectivo</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Tarjeta">Tarjeta</option>
                                            <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>
                                    <div class="col-md-7">
                                        <strong>Metodo Pago</strong>
                                    </div>
                                </div>                     
                        </div>
                        <div class="col-md-4">                    
                            <div class="row mt-2">
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
                        </div>
                    </div>


                    <div class="row mt-2">
                        <div class="col-md-6 offset-6 mb-2">
                            <div id="spinner_loading" class="spinner-border text-warning" role="status" style="display: none;">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card bg-white">
                                <div class="card-header fonde-header-card">
                                    <div class="row px-3">
                                        <div class="col-md-1 offset-1">Cantidad</div>
                                        <div class="col-md-7">Descripción</div>
                                        <div class="col-md-1">Precio</div>
                                        <div class="col-md-1">Total</div>
                                        <!-- <div class="col-md-1">Desc %</div> -->
                                    </div>
                                </div>
                                <div class="card-body bg-white" id="buildyourform">
                                    <div class="row">
                                        <div class="col-md-1 offset-1">
                                            <input type="number" autocomplete="off" data-regex="si" size="5" value="0" onclick="this.select()" class="form-control form-control-sm fieldnamePago" id="txtcantidad1" name="1" required="">
                                        </div>                                  
                                        <div class="col-md-7">
                                            <input type="text" name="txt_descripcion1" data-regex="no" size="55" class="form-control form-control-sm fieldnamePago text-capitalize" autocomplete="off" id="txtdescripcion1" required="">
                                        </div>
                                        <div class="col-md-1">
                                            <input type="number" size="5" value="0" autocomplete="off" data-regex="si" onclick="this.select()" class="form-control form-control-sm fieldnamePago" id="txtpunit1" name="1" required="">
                                        </div>
                                        <div class="col-md-1">
                                            <input type="text" class="form-control form-control-sm fieldnamePago" size="5" value="0" id="txttotal1" disabled="">
                                        </div>
                                        <!-- <div class="col-md-1">
                                            <input type="number" class="form-control form-control-sm fieldname" data-regex="si" onclick="this.select()" size="5" value="0" id="txtdescuento1" name="1">
                                        </div> -->
                                        <div class="col-md-1">
                                            <input type="hidden" value="0" class="total_hidden" id="txttotal_hidden1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 offset-8 text-right">
                                    <button type="button" id="addPago" class="btn btn-sm btn-info mt-1"><i class="fas fa-plus"></i> Producto</button>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-success btn-block mt-1" id="guardarPago">  
                                        Guardar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active show" id="pendientes_tab">
                            <!-- pendientes-tab -->
                            <div class="row">
                                <div class="col-md-10 offset-1">
                                    <hr class="bg-warning mt-2 mb-2">
                                    <div class="row mt-1">                                    
                                        <div class="col-md-1">
                                            <input type="text" name="folio" id="idFolio" class="form-control form-control-sm" placeholder="#Folio">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="cliente" id="idCliente" class="form-control form-control-sm text-capitalize" placeholder="Nombre de cliente">
                                        </div>
                                        <div class="col-md-1"><strong>Metodo</strong></div>
                                        <div class="col-md-1"><strong>Facturado</strong></div>
                                        <div class="col-md-1"><strong>Subtotal</strong></div>
                                        <div class="col-md-1"><strong>Iva</strong></div>
                                        <div class="col-md-1"><strong>Total</strong></div>
                                    </div>
                                    <!-- content-row -->
                                    <div class="row cart bg-white mt-1">
                                        <div class="col-md-12 ml-2 fondo-tables py-1">
                                            <?php 
                                            if (isset($pagos) && !empty($pagos)) :                              
                                                foreach ($pagos as $key=>$pago): ?>
                                                
                                                    <div class="row row-hover">
                                                        <div class="col-md-1 text-right">
                                                            <?php echo $pago['folio']; ?>
                                                        </div>
                                                        <div class="col-md-6 text-capitalize">
                                                            <?php echo $pago['proveedor']; ?>
                                                        </div>
                                                        <div class="col-md-1 text-right">
                                                            <?php echo $pago['metodo']; ?>
                                                        </div>
                                                        <div class="col-md-1 text-right">
                                                            <?php echo $pago['facturado']; ?>
                                                        </div>
                                                        <div class="col-md-1 text-right">
                                                        <?php echo $pago['subtotal']; ?>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <?php echo $pago['iva']; ?>
                                                        </div>
                                                        <div class="col-md-1 text-center">
                                                        <?php echo $pago['total']; ?>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                <?php endforeach; 
                                            endif;?>                         
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-right">
                                           <?php echo $this->pagination->create_links(); ?>
                                        </div>   
                                    </div>
                                    <!-- Modal Abonar -->
                                    <div class="modal fade" id="abonarModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title" id="tituloModalAbonar"></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mt-3">
                                                    <div class="col-md-4 text-center">
                                                        <strong>Saldo Restante</strong>
                                                    </div>
                                                    <div class="col-md-4"> 
                                                    <h5 id="saldor_restante">$</h5>
                                                    </div>
                                                </div> 
                                                <div class="row mt-3">
                                                    <div class="col-md-4 text-center">
                                                        <strong>Metodo Pago</strong>
                                                    </div>
                                                    <div class="col-md-4"> 
                                                        <select class="custom-select custom-select-sm" id="metodoPago">
                                                            <option value="Efectivo" selected>Efectivo</option>
                                                            <option value="Transferencia">Transferencia</option>
                                                            <option value="Tarjeta">Tarjeta</option>
                                                            <option value="Cheque">Cheque</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-4 text-center">
                                                        <strong>Importe Abono</strong>
                                                    </div>
                                                    <div class="col-md-4"> 
                                                        <input type="text" name="importe" id="importeAbono" class="form-control form-control-sm fieldname" autocomplete="off">
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                <input type="hidden" id="idVentaHide" value="">
                                                <button type="button" class="btn btn-success" onclick="store_abono();">Guardar</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div><!-- /Modal Abonar -->
                                    <!-- /content-row -->
                                </div>
                            </div>
                            <!-- /pendientes-tab -->
                        </div>
                        <div class="tab-pane fade" id="pagados_tab">
                            <!-- pagados-tab -->
                            <?php echo var_dump($clientes) ?>
                            <!-- /pagados-tab -->
                        </div>
                        <div class="tab-pane fade" id="cancelados_tab">
                            <!-- cancelados-tab -->
                            
                            <!-- /cancelados-tab -->
                        </div>
                        <div class="tab-pane fade" id="todos_tab">
                            <!-- todos-tab -->
                            
                            <!-- /todos-tab -->
                        </div>
                    </div>
                </form>
                <!-- /CONTENIDO -->
            </div>
        </div>
    </div>
</div>