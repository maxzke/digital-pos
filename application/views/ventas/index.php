<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="container">
                <!-- CONTENIDO -->
                <div class="row fonde-header-card pt-2 my-2">
                    <div class="col-md-12 text-center">
                        <h4>Registro de Ventas</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 offset-8">
                        <ul class="nav nav-pills">
                            <li class="nav-item mr-1">
                                <a class="btn btn-sm btn-warning" data-toggle="tab" href="#pendientes_tab">Pendientes</a>
                            </li>
                            <li class="nav-item mr-1">
                                <a class="btn btn-sm btn-success" data-toggle="tab" href="#pagados_tab">Pagados</a>
                            </li>
                            <li class="nav-item mr-1">
                                <a class="btn btn-sm btn-danger" data-toggle="tab" href="#cancelados_tab">Cancelados</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-sm btn-primary" data-toggle="tab" href="#todos_tab">Todos</a>
                            </li>
                        </ul>
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
                                    <div class="col-md-1"><strong>Total</strong></div>
                                    <div class="col-md-1"><strong>Abono</strong></div>
                                    <div class="col-md-1"><strong>Resta</strong></div>
                                    <div class="col-md-1"><strong>Fecha</strong></div>
                                    <div class="col-md-1"><strong>Abonar</strong></div>
                                </div>
                                <!-- content-row -->
                                <div class="row cart bg-white mt-1">
                                    <div class="col-md-12 ml-2">
                                        <?php 
                                        if (isset($clientes) && !empty($clientes)) : 
                                            $contador = 0;                                      
                                            foreach ($clientes as $key=>$cliente): $contador++;?>
                                            
                                                <div class="row row-hover">
                                                    <div class="col-md-1">
                                                        <?php echo $cliente['folio']; ?>
                                                    </div>
                                                    <div class="col-md-6 text-capitalize">
                                                        <?php echo $cliente['cliente']."zke"; ?>
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
                                <div class="row">
                                    <?php echo "CONT_>".$contador.$this->pagination->create_links(); ?>   
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