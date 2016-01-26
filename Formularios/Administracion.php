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
        <script src="../Script/Administracion.js" type="text/javascript"></script>
    </head>
    <body id="blanco">
        <div id="formulario">
            <ul id="ListaMenu" data-tipo="0">
                <li onclick="cambioProceso('Registro Personal','cuerpoRegistroPersonal')">Registro Personal</li>
                <li onclick="cambioProceso('Listado Empleados','cuerpoListadoEmpleado')">Listado Empleados</li>
                <li onclick="cambioProceso('Otros Pagos','cuerpoOtrosPagos')">Otros Pagos</li>
                <li onclick="cambioProceso('Datos de la empresa','cuerpoDatosEmpresa')">Datos de la empresa</li>
            </ul>
            <h1>FORMULARIO DE ADMINISTRACION</h1>
            <div id="cuerpoRegistroPersonal">
                <div class='contenedor50'>
                    <span class='negrillaenter'>Foto</span>
                    <div id="fotoPerfil">
                        <img src="../Imagen/perfil.svg" alt="" class="point" onclick="cargarImagen(this,1)"/>
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
            </div>
            <div id="cuerpoOtrosPagos">
            </div>
            <div id="cuerpoDatosEmpresa">
            </div>
            <div class="background"></div>
        </div>
        <div  id='cargando' style="z-index: 2;">
            <div>
                <img src="Imagen/cargando.gif" title="CARGANDO"/>
                <span class="negrillaenter centrar">CARGANDO</span>
            </div>
        </div>
        <input type='file' onclick="cargarImagen(this,2)" id='fotocargar' style="display: none;"/>
        <canvas id='canvas' style="display: none;"></canvas>
    </body>
</html>
