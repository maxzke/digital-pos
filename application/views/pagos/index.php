<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="container">
                <!-- CONTENIDO -->
                <div class="row fonde-header-card pt-2 my-2">
                    <div class="col-md-12 text-center">
                        <h4>Pagos</h4>
                    </div>
                </div>
                
                <div class="row fondo-menu py-1">
                    <div class="col-md-4 offset-2">  
                        <div class="row mt-2">
                            <div class="col-md-4 text-right"><strong>Proveedor </strong><i class="fas fa-user"></i></div>
                            <div class="col-md-8"> 
                                <input type="text" id="txt_cliente" class="form-control form-control-sm text-capitalize" placeholder="Nombre">
                            </div>
                        </div>    
                        <div class="row mt-1">
                            <div class="col-md-4 text-right"><strong>Folio </strong><i class="fas fa-file-invoice"></i></div>
                            <div class="col-md-8"> 
                                <input type="text" id="txt_direccion" class="form-control form-control-sm text-capitalize" placeholder="Lugar, calle">
                            </div>
                        </div>    
                        <div class="row mt-1">
                            <div class="col-md-4 text-right"><strong>Importe </strong><i class="fas fa-dollar-sign"></i></div>
                            <div class="col-md-8"> 
                                <input type="text" id="txt_telefono" class="form-control form-control-sm" placeholder="10 digitos">
                            </div>
                        </div>                                               
                    </div>
                    <div class="col-md-3 offset-1">      
                        <div class="row mt-2"><div class="col-md-12"> 
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input select_facturar" id="customSwitch1">
                                    <label class="custom-control-label" for="customSwitch1"><strong>Facturado</strong></label>
                                </div>
                            </div>
                        </div>    
                        <div class="row mt-3">
                            <div class="col-md-12"> 
                                <button type="button" class="btn btn-sm btn-success">Guardar</button>
                            </div>
                        </div>                     
                    </div>
                </div>
                
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active show" id="pendientes_tab">
                        <!-- pendientes-tab -->
                        <div class="row">
                            <div class="col-md-10 offset-1">
                                <!-- content-row -->
                                <div class="row cart bg-white mt-1">
                                    <div class="col-md-12 ml-2">
                                        <?php 
                                        if ($clientes) :                                        
                                            foreach ($clientes as $cliente): ?>
                                                <div class="row row-hover">
                                                    <div class="col-md-1">
                                                        <?php echo $cliente['folio']; ?>
                                                    </div>
                                                    <div class="col-md-6 text-capitalize">
                                                        <?php echo $cliente['cliente']; ?>
                                                    </div>
                                                    <div class="col-md-1">
                                                        $ <?php echo number_format($cliente['total'][0]['importe']+$cliente['iva'],1,'.',','); ?>
                                                    </div>
                                                    <div class="col-md-1">
                                                        $ <?php echo number_format($cliente['abonos'][0]['importe'],1,'.',','); ?>
                                                    </div>
                                                    <div class="col-md-1">
                                                        $ <?php echo number_format($cliente['resta'],1,'.',','); ?>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <?php echo $cliente['fecha']; ?>
                                                    </div>
                                                    <div class="col-md-1 text-center">
                                                        <i class="fas fa-plus-circle incrementa" onclick="abonar(<?php echo $cliente['folio']; ?>,<?php echo number_format($cliente['resta'],1,'.',','); ?>);"></i> 
                                                    </div>
                                                </div>
                                                <hr>
                                            <?php endforeach; 
                                        endif;?>                                        
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
                <!-- /CONTENIDO -->
            </div>
        </div>
    </div>
</div>