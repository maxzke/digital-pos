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
                                    <input type="number" id="txt_deposito_a_cuenta" class="form-control form-control-sm text-capitalize" placeholder="importe">
                                </div>
                            </div> 
                            <hr class="mt-2">   
                            <div class="row mt-2">
                                <div class="col-md-6 text-right"><strong>Desglozar corte </strong><i class="fas fa-random"></i></div>
                                <div class="col-md-6"> 
                                    <button type="button" id="btnDesglozarCorte" class="btn btn-sm btn-success">Enviar <i class="fas fa-random"></i></button>
                                </div>
                            </div>                                          
                        </div>
                        <div class="col-md-4">      
                            <div class="row mt-2">
                                <div class="col-md-2"> 
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="switchDeposito">
                                        <label class="custom-control-label" for="switchDeposito"><i class="fas fa-unlock-alt"></i></label>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <button type="button" class="btn btn-sm btn-success" id="btnEnviarDeposito" disabled>Enviar <i class="fas fa-dollar-sign"></i></button>                                    
                                </div>
                            </div>               
                        </div>                        
                        <div class="col-md-4">                    
                            <div class="row mt-2">
                                <div class="col-md-6 text-right"><strong>Caja / Efectivo </strong></div>
                                <div class="col-md-4"> 
                                    <input type="text" id="txt_subtotal" value="<?php echo "$ ".$caja; ?>" class="form-control form-control-sm bg-white" readonly>                                    
                                </div>
                            </div>    
                            <div class="row mt-2">
                                <div class="col-md-6 text-right"><strong>Cuenta / Banco </strong></div>
                                <div class="col-md-4"> 
                                    <input type="text" id="txt_iva" value="<?php echo "$ ".$cuenta_banco ?>" class="form-control form-control-sm" readonly>
                                </div>
                            </div>                  
                        </div>
                    </div>

                    <div id="desglozarCorteDiv" style="display: none;">
                        <div class="row mt-2 fonde-header-card pt-2">
                            <div class="col-md-3 text-right mt-1">
                                <span><strong>Desgloce por fecha</strong></span>
                            </div>
                            <div class="col-md-3">
                                <div class="form-row">
                                    <label for="desde" class="col-md-6 text-right mt-1">Inicio</label>
                                    <input id="desde" class="form-control form-control-sm col-md-6" type="date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-row">
                                    <label for="hasta" class="col-md-3 text-right mt-1">Final</label>
                                    <input id="hasta" class="form-control form-control-sm col-md-6" type="date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="btnDesglozar" class="btn btn-sm btn-info"><i class="fas fa-angle-double-down"></i> Aceptar</button>
                            </div>
                        </div>

                        <div class="row fondo-menu py-2">
                            <div class="col-md-6">
                                <div class="row mt-1">
                                    <div class="col-md-12 text-center">
                                        <h5>Ingresos ( ventas )</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 offset-3">
                                        <!-- tabla ingresos -->
                                        <div class="row fonde-header-card py-2 mt-2">
                                            <div class="col-md-6 text-right"><strong>Folio</strong></div>
                                            <div class="col-md-6 text-center"><strong>Total</strong></div>
                                        </div>
                                        <div class="row cart bg-white">
                                            <div class="col-md-12 fondo-tables py-1" id="pintaPagos">
                                                <?php 
                                                // if (isset($pagos) && !empty($pagos)) :                              
                                                //     foreach ($pagos as $key=>$pago): ?>
                                                    
                                                        <div class="row row-hover">
                                                            <div class="col-md-6 text-right">
                                                                001
                                                            </div>
                                                            <div class="col-md-6 text-right">
                                                                3,245.00
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    <?php //endforeach; 
                                                //endif;?>                         
                                            </div>
                                        </div>  
                                        <!-- /tabla ingresos -->
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-5 offset-2 text-right border-bottom"><strong>Total</strong></div>
                                    <div class="col-md-2 text-right border-bottom"><strong>723,548.15</strong></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 text-right"><strong>Total cobrado en efectivo</strong></div>
                                    <div class="col-md-2 text-right"><strong>548.15</strong></div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-7 text-right"><strong>Total cobrado en transferencia</strong></div>
                                    <div class="col-md-2 text-right"><strong>2,548.15</strong></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 text-right"><strong>Total cobrado en tarjeta</strong></div>
                                    <div class="col-md-2 text-right"><strong>2,548.15</strong></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 text-right"><strong>Total cobrado en cheque</strong></div>
                                    <div class="col-md-2 text-right"><strong>2,548.15</strong></div>
                                </div>
                            </div>
                            <div class="col-md-6 border-left border-secondary">
                                <div class="row mt-1">
                                    <div class="col-md-12 text-center">
                                        <h5>Egresos ( pagos )</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 offset-3">
                                        <!-- tabla ingresos -->
                                        <div class="row fonde-header-card py-2 mt-2">
                                            <div class="col-md-6 text-right"><strong>Folio</strong></div>
                                            <div class="col-md-6 text-center"><strong>Total</strong></div>
                                        </div>
                                        <div class="row cart bg-white">
                                            <div class="col-md-12 fondo-tables py-1" id="pintaPagos">
                                                <?php 
                                                // if (isset($pagos) && !empty($pagos)) :                              
                                                //     foreach ($pagos as $key=>$pago): ?>
                                                    
                                                        <div class="row row-hover">
                                                            <div class="col-md-6 text-right">
                                                                001
                                                            </div>
                                                            <div class="col-md-6 text-right">
                                                                3,245.00
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    <?php //endforeach; 
                                                //endif;?>                         
                                            </div>
                                        </div>  
                                        <!-- /tabla ingresos -->
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-5 offset-2 text-right border-bottom"><strong>Total pagado en efectivo</strong></div>
                                    <div class="col-md-2 text-right border-bottom"><strong>723,548.15</strong></div>
                                </div>
                            </div>                        
                        </div>

                        <div class="row fondo-menu py-2">
                            <div class="col-md-10 border-top offset-1"></div>
                            <div class="col-md-6">
                                <div class="row mt-2">
                                    <div class="col-md-6 offset-3 text-center">
                                        <h5>Depósito a cuenta</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 offset-3">
                                        <!-- tabla ingresos -->
                                        <div class="row fonde-header-card py-2 mt-2">
                                            <div class="col-md-6 text-right"><strong>Folio</strong></div>
                                            <div class="col-md-6 text-center"><strong>Total</strong></div>
                                        </div>
                                        <div class="row cart bg-white">
                                            <div class="col-md-12 fondo-tables py-1" id="pintaPagos">
                                                <?php 
                                                // if (isset($pagos) && !empty($pagos)) :                              
                                                //     foreach ($pagos as $key=>$pago): ?>
                                                    
                                                        <div class="row row-hover">
                                                            <div class="col-md-6 text-right">
                                                                001
                                                            </div>
                                                            <div class="col-md-6 text-right">
                                                                3,245.00
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    <?php //endforeach; 
                                                //endif;?>                         
                                            </div>
                                        </div>  
                                        <!-- /tabla ingresos -->
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-5 offset-2 text-right"><strong>Total</strong></div>
                                    <div class="col-md-2 text-right"><strong>723,548.15</strong></div>
                                </div>
                            </div>
                            <div class="col-md-6">                            
                                <div class="row mt-5">
                                    <div class="col-md-5 text-right"><h5>Caja / Efectivo</h5></div>
                                    <div class="col-md-2 text-right"><h5>723,548.15</h5></div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-5 text-right"><h5>Cuenta / Banco</h5></div>
                                    <div class="col-md-2 text-right"><h5>723,548.15</h5></div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-2 offset-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input select_facturar" id="switchAceptarCorte">
                                            <label class="custom-control-label" for="switchAceptarCorte">Acepto</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="btnAceptarCorte" class="btn btn-sm btn-success" disabled>Enviar <i class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </div>                        
                        </div>
                    </div>
                           
                    <br><br><br>
                </form>
                <!-- /CONTENIDO -->
            </div>
        </div>
    </div>
</div>

