<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>REPORTE</title>
        <link href="../Estilo/ESTILO.css" rel="stylesheet" type="text/css"/>
        <link href="../Estilo/EstiloFecha.css" rel="stylesheet" type="text/css"/>
        <link href="../Estilo/Responsive.css" rel="stylesheet" type="text/css"/>
        <script src="../Script/Plugin/jquery-2.1.3.min.js" type="text/javascript"></script>
        <script src="../Script/Plugin/jquery-ui.js" type="text/javascript"></script>
        <script src="../Script/Plugin/HERRAMIENTAS.js" type="text/javascript"></script>
        <script src="../Script/Plugin/tableExport.min.js" type="text/javascript"></script>
        <script src="../Script/Plugin/FileSaver.min.js" type="text/javascript"></script>
        <script src="../Script/Reporte.js" type="text/javascript"></script>
    </head>
    <body id="blanco">
        <div id="formulario">
            <ul id="ListaMenu" data-tipo="0">
                <li onclick="cambioProceso('Morosos', 'cuerpoMoroso')">Reporte de Morosos</li>
                <li onclick="cambioProceso('Movimiento', 'cuerpoMovimiento')">Reporte de Movimiento</li>
            </ul>
            <h1>Reportes</h1>
            <div id="cuerpoMoroso">
                <div class='centrar'>
                    <input type='text' class='medio' name='buscarmoroso' placeholder="CRITERIO DE BUSQUEDA" onkeyup="buscarMorosos(event)"/>
                    <span class='negrilla'>Desde: </span>
                    <input type='text' class='normal fecha' name='fechademoroso'/>
                    <span class='negrilla'>Hasta: </span>
                    <input type='text' class='normal fecha' name='fechahastamoroso'/>
                    <button onclick="buscarMorosos('')" class='normal sinborde'>BUSCAR</button>
                </div>
                <table id='tablamoroso'>
                    <thead >
                        <th><div class='normal'>OT</div></th>
                        <th><div class='normal'>CI</div></th>
                        <th><div class='grande'>Nombre del cliente</div></th>
                        <th><div class='normal'>Fecha Ingreso</div></th>
                        <th><div class='normal'>Fecha Salida</div></th>
                        <th><div class='normal'>Monto</div></th>
                        <th><div class='normal'>Pagado</div></th>
                        <th><div class='normal'>Falta</div></th>
                        <th><div class='grande'>Mecánico</div></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <center><span class='minitext'>Al darle doble click a la tabla podra ver en detalle la reparación.</span></center>
                <div class='centrar'>
                    <button onclick='pagoReparacion(1)'  class='medio'>PAGAR</button>
                    <button onclick='exportar("tablamoroso","REPORTE_MOROSOS")' class='medio'>EXPORTAR EXCEL</button>
                    
                </div>
            </div>
            <div id="cuerpoImpresion">
                <div class='contenedor30 centrar'>
                    <img src="../Imagen/Logo_taller.png" alt="" id="logoimpresion"/>
                </div>
                <div class='contenedor70 centrar'>
                    <div id="tituloimpresion">COTIZACION DE REPARACION</div>
                    <span class='negrilla'>Dirección:</span>
                    <span id="direccionimpresion">Av. Bolivar # 560</span>
                    <span class='negrilla'>Telefono:</span>
                    <span id="telefonoimpresion" >3345100 - 75685675</span>
                    <span class='negrilla'>Correo:</span>
                    <span id="correoimpresion" >3345100 - 75685675</span>
                    <span class='negrillaenter' id="nombreimpresion">RONY TALLER</span>
                </div>
                <div id="cuerpoimpresiontable" class="clear">
                    <span class='negrilla'>Mecanico:</span>
                    <div id="mecanicoimpresion" ></div>
                    <span class='negrilla'>Vehiculo:</span>
                    <div id="vehiculoimpresion" ></div>
                    <span class='negrilla'>Marca:</span>
                    <div id="marcaimpresion" ></div>
                    <span class='negrilla'>Placa:</span>
                    <div id="placaimpresion" ></div>
                     <table id='tablaimpresion' cellspacing="0">
                        <thead >
                            <th><div class='chico'>Cant.</div></th>
                            <th><div class='grande'>Descripción de Trabajo</div></th>
                            <th><div class='pequeno'>Precio Unitario</div></th>
                            <th><div class='chico'>Descuento</div></th>
                            <th><div class='chico'>Total</div></th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class='alineacionIzquierda'>
                        <span class='negrilla'>Observación</span>
                        <div id="observacionimpresion"></div>
                    </div>
                    <div id="totalimpresion">
                        <span class='negrilla'>TOTAL: </span>
                        <div id="totalimpr" class="centrar">0.00</div>
                    </div>
                     <div class='centrar clear'>
                        <button onclick='cambioProceso("Reparacion", "cuerpoReparacion")' class='normal'>ATRAS</button>
                        <button onclick='imprimir()' class='normal' >IMPRIMIR</button>
                    </div>
                </div>
            </div>
            <div id="cuerpoMovimiento">
                <div class='centrar'>
                    <input type='text' class='medio' name='buscarMovimiento' placeholder="CRITERIO DE BUSQUEDA" onkeyup="buscarMovimiento(event)"/>
                    <span class='negrilla'>Desde: </span>
                    <input type='text' class='normal fecha' name='fechadeMovimiento'/>
                    <span class='negrilla'>Hasta: </span>
                    <input type='text' class='normal fecha' name='fechahastaMovimiento'/>
                    <button onclick="buscarMovimiento('')" class='normal sinborde'>BUSCAR</button>
                </div>
                <table id='tablaMovimiento'>
                    <thead >
                        <th><div class='normal'>OT</div></th>
                        <th><div class='normal'>CI</div></th>
                        <th><div class='grande'>Nombre del cliente</div></th>
                        <th><div class='normal'>Fecha Ingreso</div></th>
                        <th><div class='normal'>Fecha Salida</div></th>
                        <th><div class='normal'>Monto</div></th>
                        <th><div class='normal'>Pagado</div></th>
                        <th><div class='normal'>Falta</div></th>
                        <th><div class='grande'>Mecánico</div></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                 <div class='centrar'>
                     <input type='radio' value='reparacion' name='tipomovimiento' checked onchange="buscarMovimiento('')"/>
                    <span class='negrilla'>Reparación</span>
                    <input type='radio' value='sueldo' name='tipomovimiento' onchange="buscarMovimiento('')"/>
                    <span class='negrilla'>Sueldo</span>
                    <input type='radio' value='otros pagos' name='tipomovimiento' onchange="buscarMovimiento('')"/>
                    <span class='negrilla'>Otros</span>
                    <div id="totalreporte">
                        <span class='negrilla'>TOTAL: </span>
                        <span class='negrilla' id="totalmovimiento">0.00</span>
                    </div>
                </div>
                <div class='centrar'>
                    <button onclick='exportarMovimiento("tablaMovimiento","REPORTE_MOVIMIENTO")' class='medio'>EXPORTAR EXCEL</button>
                </div>
            </div>
            <div id="cuerpoReparacion">
                <div class='contenedor50'>
                    <span class='negrillaenter'>DATOS DEL AUTO</span>
                    <div class='contenedor50'>
                        <span class='negrillaenter'>Vehiculo</span>
                        <span id="vehiculoreparacion"></span>
                        <span class='negrillaenter'>Modelo</span>
                        <span id="modeloreparacion"></span>
                        <span class='negrillaenter'>Nro. Chasis</span>
                        <span id="chasisreparacion"></span>
                    </div>
                    <div class='contenedor50'>
                        <span class='negrillaenter'>Marca</span>
                        <span id="marcareparacion"></span>
                        <span class='negrillaenter'>Color</span>
                        <span id="colorreparacion"></span>
                        <span class='negrillaenter'>Placa</span>
                        <span id="placareparacion"></span>
                    </div>
                    <span class='negrillaenter'>Observacion</span>
                    <span id="obsreparacion"></span>
                    <br><br><span class='negrillaenter'>ACCESORIOS DEL AUTO</span>
                    <div id="contenedorAccesorios">
                        
                    </div>
                </div>
                <div class='contenedor50' id="repara2">
                    <span class='negrillaenter'>REGISTRO REPARACION</span>
                    <span class='negrillaenter'>Mecánico</span>
                    <select id='mecanico' class="grande2">
                        <option value='0'>--Seleccione un mecánico--</option>
                    </select>
                    <div class='contenedor50'>
                        <span class='negrillaenter'>Fecha Ingreso</span>
                        <input type='text' class='normal fecha' name='fechaIngresoReparacion'/>
                        <span class='negrillaenter'>Kilometro</span>
                        <input type='number' class='normal' step="0.5" min="0" name='kilometroReparacion'/>
                        <span class='negrillaenter'>O.T</span>
                        <input type='text' class='normal' name='otReparacion'/>
                        <span class='negrillaenter'>Nro. Recibo</span>
                        <input type='text' class='normal' name='reciboReparacion'/>
                    </div>
                    <div class='contenedor50' >
                        <span class='negrillaenter'>Fecha Salida</span>
                        <input type='text' class='normal' name='fechaSalidaReparacion' readonly/>
                        <span class='negrillaenter'>Combustible</span>
                        <input type='number' class='normal' step="0.5" min="0" name='combustibleReparacion'/>
                        <span class='negrillaenter'>Nro. Factura</span>
                        <input type='text' class='normal' name='facturaReparacion'/>
                    </div>
                    <div class="clear"></div>
                    <span class='negrillaenter'>TRABAJO</span>
                    <div id='contenedorTrabajo'>
                        
                    </div>
                    <div class='alineacionDerecha'>
                        <span class='negrilla'>TOTAL TRABAJO:</span>
                        <span id='totaltrabajo'>0.00</span>
                    </div>
                </div>
                <div class='centrar'>
                    <button onclick="cambioProceso('Morosos', 'cuerpoMoroso')" class='normal' id="atrasrepara">ATRAS</button>
                    <button onclick='cambioProceso("", "cuerpoImpresion")' class='normal' id="imprimirbtn" >IMPRIMIR</button>
                </div>
            </div>
            
            
        </div>
        <div class="background"></div>
        <div class='popup' id="poppago">
            <div class='tituloPop'>Pago</div>
            <div class='contenedor30'>
                <span class='negrillaenter'>Total</span>
                <input type='text' class='normal' name='totalrpago' readonly/>
                <span class='negrillaenter'>Total Pagado</span>
                <input type='text' class='normal' name='pagadopago' readonly/>
                <span class='negrillaenter'>Falta Pagar</span>
                <input type='text' class='normal' name='faltapago' readonly/>
                <span class='negrillaenter'>Fecha</span>
                <input type='text' class='normal fecha' name='fechapago'/>
                <span class='negrillaenter'>Monto</span>
                <input type='number' class='normal' name='montopago'/>
            </div>
            <div class='contenedor70'>
                <table id='tablaPago'>
                    <thead>
                        <th><div class='pequeno'>Nro</div></th>
                        <th><div class='normal'>Fecha</div></th>
                        <th><div class='normal'>Monto</div></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class='centrar'>
                <button onclick="pagoReparacion(2)" class='medio' >CERRAR</button>
                <button onclick='pagoReparacion(3)' class='medio'>PAGAR</button>
            </div>
        </div>
    </body>
</html>
