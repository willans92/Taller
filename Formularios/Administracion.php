<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Administracion</title>
        <link href="../Estilo/ESTILO.css" rel="stylesheet" type="text/css"/>
        <link href="../Estilo/EstiloFecha.css" rel="stylesheet" type="text/css"/>
        <link href="../Estilo/Responsive.css" rel="stylesheet" type="text/css"/>
        <script src="../Script/Plugin/jquery-2.1.3.min.js" type="text/javascript"></script>
        <script src="../Script/Plugin/jquery-ui.js" type="text/javascript"></script>
        <script src="../Script/Plugin/HERRAMIENTAS.js" type="text/javascript"></script>
        <script src="../Script/Plugin/tableExport.min.js" type="text/javascript"></script>
        <script src="../Script/Plugin/FileSaver.min.js" type="text/javascript"></script>
        <script src="../Script/Administracion.js" type="text/javascript"></script>
    </head>
    <body id="blanco">
        <div id="formulario">
            <ul id="ListaMenu" data-tipo="0">
                <li onclick="cambioProceso('Registro Personal', 'cuerpoRegistroPersonal')">Registro Personal</li>
                <li onclick="cambioProceso('Listado Empleados', 'cuerpoListadoEmpleado')">Listado Empleados</li>
                <li onclick="cambioProceso('Pago de Sueldo', 'cuerpoEmpleadoPago')">Pago de Sueldo</li>
                <li onclick="cambioProceso('Otros Pagos', 'cuerpoOtrosPagos')">Otros Pagos</li>
                <li onclick="cambioProceso('Datos de la empresa', 'cuerpoDatosEmpresa')">Datos de la empresa</li>
            </ul>
            <h1>Administracion</h1>
            <div id="cuerpoRegistroPersonal">
                <div class='contenedor50'>
                    <span class='negrillaenter'>Foto</span>
                    <div id="fotoPerfil">
                        <img src="../Imagen/perfil.svg" alt="" class="point" onclick="cargarImagen(this, 1)"/>
                    </div>
                    <span class='negrillaenter'>Carnet</span>
                    <input type='text' class='medio' name='ci'/>
                    <span class='negrillaenter'>Nombre Completo</span>
                    <input type='text' class='grande' name='nombre'/>
                    <span class='negrillaenter'>Dirección</span>
                    <input type='text' class='grande' name='direccion'/>
                    <span class='negrillaenter'>Correo</span>
                    <input type='text' class='grande' name='correo'/>
                </div>
                <div class='contenedor50'>
                    <span class='negrillaenter'>Cumpleaños</span>
                    <input type='text' class='medio fecha' name='cumpleano'/>
                    <span class='negrillaenter'>Fecha Ingreso</span>
                    <input type='text' class='medio fecha' name='fechaingreso'/>
                    <span class='negrillaenter'>Sueldo</span>
                    <input type='number' step="0.5" min="0" class='medio' name='sueldo'/>
                    <span class='negrillaenter'>Cargo</span>
                    <select id="tipoCuenta">
                        <option value="0">Administrador</option>
                        <option value="1">Mecanico</option>
                        <option value="2">Recepcionista</option>
                    </select>
                    <span class='negrillaenter'>Cuenta</span>
                    <input type='text' class='medio' name='cuenta'/>
                    <span class='negrillaenter'>Contrasena</span>
                    <input type='text' class='medio' name='contrasena'/>
                    <span class='negrillaenter'>Repita Contrasena</span>
                    <input type='text' class='medio' name='recontrasena'/>
                    <br><br>
                </div>
                <div class='clear'></div>
                <div class="centrar">
                    <button onclick='RegistrarPersonal(1)' class='medio'>REGISTRAR</button>
                    <button onclick='RegistrarPersonal(2)' class='medio'>LIMPIAR</button>
                </div>
            </div>
            <div id="cuerpoListadoEmpleado">
                <div class='centrar'>
                    <input type='text' class='grande2 centrar' name='buscadorEmpleado' placeholder="CRITERIO DE BUSQUEDA" onkeyup="buscarEmpleado(event)"/>
                    <input type='radio' name='tipo' value="ACTIVO" checked onchange="buscarEmpleado('')"/>
                    <span class='negrilla'>Activos</span>
                    <input type='radio' name='tipo' value="INACTIVO" onchange="buscarEmpleado('')" />
                    <span class='negrilla'>Inactivos</span>
                </div>
                <table id='tablaEmpleado'>
                    <thead>
                        <tr>
                            <th><div class="pequeno">Foto</div></th>
                            <th><div class="normal">CI</div></th>
                            <th><div class="grande2">Nombre</div></th>
                            <th><div class="grande">Dirección</div></th>
                            <th><div class="grande">Correo</div></th>
                            <th><div class="medio">Cumpleaños</div></th>
                            <th><div class="normal">Sueldo</div></th>
                            <th><div class="medio">Contratado</div></th>
                            <th><div class="medio">Cargo</div></th>
                            <th><div class="medio">Cuenta</div></th>
                            <th><div class="medio">Contrasena</div></th>
                            <th><div class="medio">Estado</div></th>
                            <th><div class="medio">Fecha Retirado</div></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class='centrar'>
                    <button onclick='actualizarPersonal()' class='medio'>ACTUALIZAR</button>
                    <button onclick="exportar('tablaEmpleado')" class='medio'>EXPORTAR EXCEL</button>
                </div>
            </div>
            <div id="cuerpoEmpleadoPago">
                <div class='centrar'>
                    <input type='text' class='grande2 centrar' name='buscadorEmpleadoPago' placeholder="CRITERIO DE BUSQUEDA" onkeyup="buscarEmpleadoPago(event)"/>
                    <span class='negrilla'>Año</span>
                    <input type='text' class='normal' name='anoEmpleado'/>
                    <span class='negrilla'>Mes</span>
                    <select class="medio" id="mesEmpleado" onchange="buscarEmpleadoPago('')">
                        <option value="0">Enero</option>
                        <option value="1">Febrero</option>
                        <option value="2">Marzo</option>
                        <option value="3">Abril</option>
                        <option value="4">Mayo</option>
                        <option value="5">Junio</option>
                        <option value="6">Julio</option>
                        <option value="7">Agosto</option>
                        <option value="8">Septiembre</option>
                        <option value="9">Octubre</option>
                        <option value="10">Noviembre</option>
                        <option value="11">Diciembre</option>
                    </select><br>
                    <input type='radio' name='tipopago' value="FALTA_PAGAR" checked onchange="buscarEmpleadoPago('')"/>
                    <span class='negrilla'>Falta Pagar</span>
                    <input type='radio' name='tipopago' value="PAGADO" onchange="buscarEmpleadoPago('')" />
                    <span class='negrilla'>Pagado</span>

                </div>
                <table id='tablaEmpleadoSueldo'>
                    <thead>
                        <tr>
                            <th><div class="normal">CI</div></th>
                            <th><div class="grande2">Nombre</div></th>
                            <th><div class="medio">Sueldo</div></th>
                            <th><div class="medio">Pagado</div></th>
                            <th><div class="medio">Saldo</div></th>
                            <th><div class="medio">Ultimo Pago</div></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class='centrar'>
                    <button onclick='abrirpagoSueldo()' class='medio' id="btnpago">PAGAR</button>
                    <button onclick="exportar('tablaEmpleadoSueldo')" class='medio'>EXPORTAR EXCEL</button>
                </div>
            </div>
            <div id="cuerpoOtrosPagos">
                <div class='centrar'>
                    <input type='text' class='grande2 centrar' name='buscadorOtroPago' placeholder="CRITERIO DE BUSQUEDA" onkeyup="buscarOtroPago(event)"/>
                    <span class='negrilla'>Año</span>
                    <input type='text' class='normal' name='anoOtropago'/>
                    <span class='negrilla'>Mes</span>
                    <select class="medio" id="mesOtroPago" onchange="buscarOtroPago('')">
                        <option value="0">Enero</option>
                        <option value="1">Febrero</option>
                        <option value="2">Marzo</option>
                        <option value="3">Abril</option>
                        <option value="4">Mayo</option>
                        <option value="5">Junio</option>
                        <option value="6">Julio</option>
                        <option value="7">Agosto</option>
                        <option value="8">Septiembre</option>
                        <option value="9">Octubre</option>
                        <option value="10">Noviembre</option>
                        <option value="11">Diciembre</option>
                    </select><br>
                    <input type='radio' name='tipootro' value="activo" checked onchange="buscarOtroPago('')"/>
                    <span class='negrilla'>ACTIVO</span>
                    <input type='radio' name='tipootro' value="cancelado" onchange="buscarOtroPago('')" />
                    <span class='negrilla'>CANCELADOS</span>

                </div>
                <table id='tablaOtroPago'>
                    <thead>
                        <tr>
                            <th><div class="normal">Fecha</div></th>
                            <th><div class="normal">Monto</div></th>
                            <th><div class="grande2">Descripcion</div></th>
                            <th><div class="normal">Estado</div></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class='centrar'>
                    <button onclick='abrirpagoOtro(1)' class='medio' id="btnpago">REGISTRAR PAGO</button>
                    <button onclick="exportar('tablaOtroPago')" class='medio'>EXPORTAR EXCEL</button>
                </div> 
            </div>
            <div id="cuerpoDatosEmpresa">
            </div>
            <div class="background"></div>
            <div id="popPagoSueldo" class="popup">
                <div class='tituloPop' id="tituloSueldo">PAGO SUELDO</div>
                <span class='negrilla'>Carnet: </span>
                <span id="cisueldo"></span><br>
                <span class='negrilla'>Nombre: </span>
                <span id="nombresueldo"></span><br>
                <span class='negrilla'>Sueldo: </span>
                <span id="sueldosueldo"></span><br>
                <span class='negrilla'>Cargo: </span>
                <span id="cargosueldo"></span><br>
                <span class='negrilla'>Saldo: </span>
                <span id="saldosueldo"></span><br>
                <span class='negrilla'>Monto: </span>
                <input type='number' step="0.5" min="0" class='normal' name='montosueldo'/><br><br>
                <input type='text' class='grande2' name='descsueldo' placeholder="MOTIVO DE PAGO"/><br><br>
                <span class='negrillaenter'>DETALLE DE PAGOS</span>
                <table id='tablaDetallePago'>
                    <thead>
                        <tr>
                            <th><div class="normal">Fecha</div></th>
                            <th><div class="normal">Monto</div></th>
                            <th><div class="grande2">Descripcion</div></th>
                            <th><div class="normal">Estado</div></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class='centrar'>
                    <button onclick='pagoSueldo(0)' class='medio'>PAGAR</button>
                    <button onclick="exportar('tablaDetallePago')" class='medio'>EXPORTAR</button>
                    <button onclick='pagoSueldo(1)' class='medio'>CANCELAR</button>
                </div>
            </div>
            <div id="popPagoOtros" class="popup">
                <div class='tituloPop'>Realizar Pago</div>
                <span class='negrillaenter'>Fecha</span>
                <input type='text' class='normal fecha' name='otrofecha'/>
                <span class='negrillaenter'>Monto</span>
                <input type='number' step="0.5" min="0" class='medio' name='montofecha'/>
                <span class='negrillaenter'>Descripcion</span>
                <input type='text' class='grande2' name='otrodesc'/>
                <div class='centrar'>
                    <button onclick='pagoOtros()' class='medio'>REALIZAR PAGO</button>
                    <button onclick='abrirpagoOtro(2)' class='medio'>CANCELAR</button>
                </div>
            </div>
        </div>

    </body>
</html>
