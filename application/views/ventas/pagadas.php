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
                                <a class="btn btn-sm btn-warning" href="<?php echo site_url('ventas/index') ?>">Pendientes</a>
                            </li>
                            <li class="nav-item mr-1">
                                <a class="btn btn-sm btn-success" href="<?php echo site_url('ventas/pagados') ?>">Pagados</a>
                            </li>
                            <li class="nav-item mr-1">
                                <a class="btn btn-sm btn-danger" href="<?php echo site_url('ventas/cancelados') ?>">Cancelados</a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                
                <div class="row">
                    <div class="col-md-12">
                        <hr class="bg-success mt-2 mb-2">
                        <div class="row fonde-header-card py-2 mt-1">                                    
                            <div class="col-md-2">
                                <input type="text" name="folio" id="folioPagados" class="form-control form-control-sm" placeholder="#Folio">
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="cliente" id="clientePagados" class="form-control form-control-sm text-capitalize" placeholder="Nombre de cliente">
                            </div>
                            <div class="col-md-1 text-center"><strong>Total</strong></div>
                            <div class="col-md-1 text-center"><strong>Abono</strong></div>
                            <div class="col-md-1 text-center"><strong>Resta</strong></div>
                            <div class="col-md-1"><strong>Fecha</strong></div>
                            <div class="col-md-1"><strong>Opciones</strong></div>
                        </div>
                        <!-- content-row -->
                        <div class="row cart bg-white">
                            <div class="col-md-12 fondo-tables py-1" id="pintaVentas">
                                <?php 
                                if (isset($pagados) && !empty($pagados)) :                              
                                    foreach ($pagados as $key=>$cliente): ?>
                                    
                                        <div class="row row-hover">
                                            <div class="col-md-2 text-right">
                                                <?php echo $cliente['folio']; ?>
                                            </div>
                                            <div class="col-md-5 text-capitalize">
                                                <?php echo $cliente['cliente']; ?>
                                            </div>
                                            <div class="col-md-1 text-right">
                                                <?php echo number_format($cliente['total'][0]['importe']+$cliente['iva'],1,'.',','); ?>
                                            </div>
                                            <div class="col-md-1 text-right">
                                                <?php echo number_format($cliente['abonos'][0]['importe'],1,'.',','); ?>
                                            </div>
                                            <div class="col-md-1 text-right">
                                                <?php echo number_format($cliente['resta'],1,'.',','); ?>
                                            </div>
                                            <div class="col-md-1">
                                                <?php echo $cliente['fecha']; ?>
                                            </div>
                                            <div class="col-md-1 text-center">
                                                <i class="fas fa-ban decrementa ml-2" 
                                                    onclick="cancelarVenta(
                                                        <?php echo $cliente['folio']; ?>,
                                                        '<?php echo $cliente['cliente']; ?>',
                                                        '<?php echo number_format($cliente['total'][0]['importe']+$cliente['iva'],1,'.',','); ?>'                                                                
                                                    );">
                                                </i> 
                                                <i class="fas fa-download incrementa ml-2"
                                                <?php $titulo = "Abono ".$cliente['folio']; ?> 
                                                    onclick="getDetallesNota('<?php echo $cliente['folio']; ?>')"></i>
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
                                            <select class="custom-select custom-select-sm" id="metodoPagoModal">
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
                        <!-- Modal Cancelar Venta -->
                        <div class="modal fade" id="cancelarVentaModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h5 class="modal-title text-white">Cancelar Venta</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="bodyModalCancelarVenta">
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                    <input type="hidden" id="idVentaHide" value="">
                                    <button type="button" class="btn btn-success" onclick="store_cancelacion();">Guardar</button>
                                </div>
                                </div>
                            </div>
                        </div><!-- /Modal Cancelar Venta -->
                        <!-- /content-row -->
                    </div>
                </div>
                <!-- /pendientes-tab -->
                <!-- /CONTENIDO -->
            </div>
        </div>
    </div>
</div>