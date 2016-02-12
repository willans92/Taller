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
                    <button onclick='cambioProceso("Nuevo Cliente", "cuerpoNuevoCliente")' class='medio'>NUEVO CLIENTE</button>
                </div>
                <div id='contenedorCliente'>

                </div>
                <div id='mensajeAyuda'>Al darle doble click a cualquier cliente se puede ingresar a ver sus datos.</div>
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
                    <div class='centrar'>
                        <button onclick='cambioProceso("Listado de Clientes", "cuerpoListadoCliente")' class='normal'>CLIENTES</button>
                        <button onclick='registroCliente(this)' class='normal' id="registroCliente">REGISTAR</button>
                    </div>
                </div>
                <div class='contenedor50' id="contentVehiculo">
                    <span class='negrilla'>Vehiculos</span><span class="mas" onclick="masVehiculo()">(+)</span><br>
                    <div id='contenedorVehiculo'>

                    </div>
                    <div class='centrar'>
                        <button onclick='evento()' class='normal'>REPARAR</button>
                        <button onclick='evento()' class='normal'>ELIMINAR</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="background"></div>
    </body>
</html>
