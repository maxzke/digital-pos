<div class="main-panel">
    <div class="content">
        <div class="page-inner">
            <div class="row">
				<div class="col-md-12">
				<!-- CONTENIDO -->
                <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-8" id="contenedorNotas">
              <div id="refrescarNota">
                  <form name="notaventa" action="#" id="formNotaVenta">
                  <div class="row my-1">                        
                        <div class="col-md-12 text-center bg-muted">
                          <h3 class="text-white">
                            Nota de Venta
                          </h3>
                        </div>
                        
                  </div>

                  <div class="row">

                       <div class="col-md-4">
                        <input id="txt_cliente" type="text" class="form-control form-control-sm text-uppercase" autocomplete="off" placeholder="Nombre del cliente" required>
                              
                              
                                  <input id="txt_direccion" type="text" class="form-control form-control-sm text-uppercase" autocomplete="off" placeholder="Direccion del cliente">
                              
                              
                                  <input id="txt_telefono" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="Telefono del cliente">
                                                    
                        </div>                                               
                        <div class="col-md-1">

                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"> Folio</i></span>
                                <input type="text" class="form-control" placeholder="" disabled>
                                <input type="hidden" id="txt_folio" value="">
                                <input type="hidden" id="txt_idUser" value="">
                                
                            </div>                                                
                             <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"> Fecha</i></span>
                                <input type="text" class="form-control" placeholder="<?php date_default_timezone_set('America/Mexico_City');
                                echo date("d/m/Y");?>" disabled>
                              </div>
                             <div class="input-group">
                                  <span class="input-group-addon"><i class="glyphicon glyphicon-edit"> Facturar</i></span>
                                  <select id="select_facturar" class="form-control" required>
                                      <option value="0">No</option> 
                                      <option value="1">Si</option>
                                  </select>                                  
                            </div>
                            <div class="input-group" title="Si se marca no entrara como venta">
                                  <span class="input-group-addon">
                                    <input type="checkbox" class="chkCotizacion">Marcar para guardar como cotizacion
                                  </span> 
                            </div>
                           
                        </div>

                        <div class="col-md-3" align="right">                        
                            <label for="txt_subtotal">Subtotal</label>
                            <input type="text" class="form-control form-control-sm" id="txt_subtotal" value="0" size="5"><br>
                            <label for="txt_iva">Iva</label>
                            <input type="text" id="txt_iva" size="5" disabled value="0"><br>
                            <label for="txt_total">Total</label>
                            <input type="text" id="txt_total" size="5" disabled value="0"><br>
                            <label for="txt_abono">Abono</label>
                            <input type="text" id="txt_abono" size="5" value="0" autocomplete="off"><br>
                            <label for="txt_resta">Resta</label>
                            <input type="text" id="txt_resta" size="5" disabled value="0"><br>                         
                      </div>
              </div>

              <div class="row">
                <div class="col-md-12">
<!-- -------------------------------------------------------------------------------------------------------------------- -->
                    <div class="row">
                      <div class="col-md-12">  
                            <!-- Div buildyourform genera articulos dinamicamente-->                              
                            <div class="panel panel-primary" id="buildyourform">
                                <div class="panel-heading">
                                    <h5 class="panel-tittle">
                                        <!---->  <div class="col-md-1"></div>                                                                         
                                        <div class="col-md-1" align="center">Cantidad</div>
                                        <!--<div class="col-md-1" align="center">Unidad</div>-->
                                        <div class="col-md-6" align="center">Descripcion</div>
                                        <div class="col-md-1" align="center">P/U</div>
                                        <div class="col-md-1" align="center">Total</div>                                    
                                        <div class="col-md-2" align="center">Descuento %</div>
                                        <div class="col-md-1">                                          
                                        </div><br>
                                    </h5>
                                </div><br>
                                <!--INICIO aqui se carga el producto inicial 1 -->
                                <div class="row">
                                      <div class="col-md-1">
                                        
                                      </div>
                                      <div class="col-md-1">
                                        <input type="text" autocomplete="off" size="5" value="0" class="fieldname" id="txtcantidad1" name="1" required>
                                      </div>                                  
                                      <div class="col-md-6">
                                        <input type="text" size="55" class="fieldname text-uppercase" autocomplete="off" id="txtdescripcion1" required>
                                      </div>
                                      <div class="col-md-1">
                                        <input type="text" size="5" value="0" autocomplete="off" class="fieldname" id="txtpunit1" name="1" required>
                                      </div>
                                      <div class="col-md-1">
                                        <input type="text" class="fieldname" size="5" value="0" id="txttotal1" disabled>
                                      </div>
                                      <div class="col-md-1">
                                        <input type="text" class="fieldname" size="5" value="0" id="txtdescuento1" name="1">
                                      </div>
                                      <div class="col-md-1">
                                        <input type="hidden" value="0" class="total_hidden" id="txttotal_hidden1">
                                      </div>
                                      <br><br>
                                </div>
                                <!--FIN aqui se carga el producto inicial 1 -->
                            </div>
                            <!-- Fin Div buildyourform genera articulos dinamicamente-->            
                            <a href="#">
                            <div class="btn btn-primary btn-sm" id="add" style="float:right"><i class="glyphicon glyphicon-plus"></i> Producto</div>
                            </a>
                      </div>                      
                    </div>
<!-- -------------------------------------------------------------------------------------------------------------------- -->
                  <div class="row">
                    <div class="col-md-4">                    
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                      <div class="row">
                                      <br><br>          
                        <div class="col-md-12" align="right">
                          <div class="btn btn-sm btn-success" id="cobrarNota"> COBRAR</div>
                        
                          </form>                     
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
                </div><!--DIV RefescaNota -->
            </div>
            <div class="col-md-2">
              
            </div>
          </div> 
            	<!-- /CONTENIDO -->
				</div>
            </div>
        </div>
    </div>
</div>