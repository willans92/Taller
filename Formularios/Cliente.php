<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CLIENTE</title>
        <link href="../Estilo/ESTILO.css" rel="stylesheet" type="text/css"/>
        <link href="../Estilo/EstiloFecha.css" rel="stylesheet" type="text/css"/>
        <link href="../Estilo/Responsive.css" rel="stylesheet" type="text/css"/>
        <script src="../Script/Plugin/jquery-2.1.3.min.js" type="text/javascript"></script>
        <script src="../Script/Plugin/jquery-ui.js" type="text/javascript"></script>
        <script src="../Script/Plugin/HERRAMIENTAS.js" type="text/javascript"></script>
        <script src="../Script/Plugin/tableExport.min.js" type="text/javascript"></script>
        <script src="../Script/Plugin/FileSaver.min.js" type="text/javascript"></script>
        <script src="../Script/Cliente.js" type="text/javascript"></script>
    </head>
    <body id="blanco">
        <div id="formulario">
            <h1>Listado de clientes</h1>

            <div id="cuerpoListadoCliente">
                <div class='centrar'>
                    <input type='text' class='grande2' name='buscadorCliente' placeholder="CRITERIO DE BUSQUEDA" onkeyup="buscarClientes(event)"/>
                </div>
                <div id='contenedorCliente'>

                </div>
                <div id='mensajeAyuda'>Al darle doble click a cualquier cliente se puede ingresar a ver sus datos.</div>
                <div class='centrar clear'>
                    <button onclick='cambioProceso("Nuevo Cliente", "cuerpoNuevoCliente")' class='medio'>NUEVO CLIENTE</button>
                </div>
            </div>
            <div id="cuerpoHistorial">
                <div class='centrar'>
                    <span class='negrilla'>Desde: </span>
                    <input type='text' class='normal fecha' name='fechadeHistorial'/>
                    <span class='negrilla'>Hasta: </span>
                    <input type='text' class='normal fecha' name='fechahastaHistorial'/>
                    <button onclick='historial()' class='normal sinborde'>BUSCAR</button>
                </div>
                <table id='tablaHistorialRe'>
                    <thead >
                        <th><div class='normal'>Fecha Ingreso</div></th>
                        <th><div class='normal'>Fecha Salida</div></th>
                        <th><div class='normal'>Kilometro</div></th>
                        <th><div class='normal'>Combustible</div></th>
                        <th><div class='normal'>OT</div></th>
                        <th><div class='normal'>Total</div></th>
                        <th><div class='normal'>Estado</div></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <center><span class='minitext'>Al darle doble click a la tabla podra ver en detalle de la reparación.</span></center>
                <div class='centrar'>
                    <button onclick='cambioProceso("Cliente", "cuerpoNuevoCliente")'  class='medio'>ATRAS</button>
                    <button onclick='exportar("tablaHistorialRe")' class='medio'>EXPORTAR EXCEL</button>
                    
                </div>
            </div>
            <div id="cuerpoNuevoCliente">
                <div class='contenedor50'>
                    <span class='negrillaenter'>Foto</span>
                    <div id="fotoPerfil">
                        <img src="../Imagen/perfil.svg" alt="" class="point" onclick="cargarImagen(this, 1)"/>
                    </div>
                    <span class='negrillaenter'>Carnet</span>
                    <input type='text' class='medio' name='ci'/>
                    <span class='negrillaenter'>Nombre Completo</span>
                    <input type='text' class='grande2' name='nombre'/>
                    <span class='negrillaenter'>Dirección</span>
                    <input type='text' class='grande2' name='direccion'/>
                    <span class='negrillaenter'>Telefono Casa</span>
                    <input type='text' class='medio' name='telefonoCasa'/>
                    <span class='negrillaenter'>Telefono Oficina</span>
                    <input type='text' class='medio' name='telefonoOficina'/>
                    <span class='negrillaenter'>Telefono Celular</span>
                    <input type='text' class='medio' name='telefonoCelular'/>
                </div>
                <div class='contenedor50' id="contentVehiculo">
                    <span class='negrilla'>Autos</span><span class="mas" onclick="masVehiculo()">(+)</span><br>
                    <div id='contenedorVehiculo'>

                    </div>
                </div>
                <div class='centrar clear'>
                    <button onclick='cambioProceso("Listado de Clientes", "cuerpoListadoCliente")' class='normal' id='atrasnuevocliente'>ATRAS</button>
                    <button onclick='registroCliente(this)' class='normal' id="registroCliente">REGISTAR</button>
                    <button class='normal' onclick='cambioProceso("HISTORIAL", "cuerpoHistorial")'>HISTORIAL</button>
                    <button class='normal' onclick='crearVehiculo(0)'>VEHICULO</button>
                    <button class='normal' onclick='crearMarca(0)'>MARCA</button>
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
                    <br><br><span class='negrillaenter'>ACCESORIOS DEL AUTO <span class="mas" onclick="masAccesorios(3)">(+)</span></span>
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
                    </div>
                    <div class='contenedor50' >
                        <span class='negrillaenter'>Fecha Salida</span>
                        <input type='text' class='normal' name='fechaSalidaReparacion' readonly/>
                        <span class='negrillaenter'>Combustible</span>
                        <input type='number' class='normal' step="0.5" min="0" name='combustibleReparacion'/>
                    </div>
                    <div class="clear"></div>
                    <span class='negrillaenter'>TRABAJO <span class='mas' onclick="masTrabajo()">(+)</span></span>
                    <div id='contenedorTrabajo'>
                        
                    </div>
                    <div class='alineacionDerecha'>
                        <span class='negrilla'>TOTAL TRABAJO:</span>
                        <span id='totaltrabajo'>0.00</span>
                    </div>
                </div>
                <div class='centrar'>
                    <button onclick='cambioProceso("Cliente", "cuerpoNuevoCliente")' class='normal' id="atrasrepara">ATRAS</button>
                    <button onclick='registrarReparacion()' class='normal' >REGISTAR</button>
                    <button onclick='finalizarReparacion(1)' class='normal'>FINALIZAR</button>
                    <button onclick='pagoReparacion(1)' class='normal' >PAGO</button>
                </div>
            </div>
        </div>
        <div class="background"></div>
        <div class='popup' id="popMarca">
            <div class='tituloPop'>Nueva Marca</div>
            <span class='negrillaenter'>Descripcion</span>
            <input type='text' class='grande' name='marcaNueva'/>
            <div class='centrar'>
                <button onclick='crearMarca(1)' class='normal'>CREAR</button>
                <button onclick='crearMarca(2)' class='normal'>CANCELAR</button>
            </div>
        </div>
        <div class='popup' id="popVehiculo">
            <div class='tituloPop'>Nuevo Vehiculo</div>
            <span class='negrillaenter'>Descripcion</span>
            <input type='text' class='grande' name='vehiculoNueva'/>
            <div class='centrar'>
                <button onclick='crearVehiculo(1)' class='normal'>CREAR</button>
                <button onclick='crearVehiculo(2)' class='normal'>CANCELAR</button>
            </div>
        </div>
        <div class='popup' id="popacesorio">
            <div class='tituloPop'>Acesorios de vehiculos</div>
            <div class='cuerpo'>
                
            </div>
            <div class='centrar'>
                <button onclick="masAccesorios(1)" class='medio'>CERRAR</button>
            </div>
        </div>
        <div class='popup' id="poptrabajo">
            <div class='tituloPop'>Trabajos</div>
            <div class='centrar'>
                <input type='text' class='grande' name='descTrabajo' placeholder="DESCRIPCION"/>
                <input type='text' class='pequeno' name='precioTrabajo' placeholder="PRECIO"/>
                <button onclick='crearTrabajo(1)' class='normal sinborde'>CREAR</button>
            </div>
            <table id='tablaTrabajo'>
                <thead>
                    <th><div class='pequeno'></div></th>
                    <th><div class='grande'>Descripción</div></th>
                    <th><div class='normal'>Precio</div></th>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
            <div class='centrar'>
                <button onclick="masTrabajo(1)" class='medio'>CERRAR</button>
                <button onclick="actualizarTrabajo()" class='medio'>ACTUALIZAR</button>
            </div>
        </div>
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
