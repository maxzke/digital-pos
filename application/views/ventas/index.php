<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="container">
                <!-- CONTENIDO -->
                <div class="row mt-1">
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
                                        <?php foreach ($clientes as $cliente): ?>
                                            <div class="row row-hover">
                                                <div class="col-md-1">
                                                    <?php echo $cliente['folio']; ?>
                                                </div>
                                                <div class="col-md-6 text-capitalize">
                                                    <?php echo $cliente['cliente']; ?>
                                                </div>
                                                <div class="col-md-1">
                                                    $ <?php echo number_format($cliente['total'][0]['importe'],1,'.',','); ?>
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
                                                    <i class="fas fa-plus-circle incrementa" onclick="abonar(<?php echo $cliente['folio']; ?>);"></i>
                                                </div>
                                            </div>
                                            <hr>
                                        <?php endforeach; ?>                                        
                                    </div>
                                </div>
                                <!-- Modal Abonar -->
                                <div class="modal fade" id="abonarModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            ...
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
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