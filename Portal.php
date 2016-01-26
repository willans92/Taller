<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="Estilo/ESTILO.css" rel="stylesheet" type="text/css"/>
        <script src="Script/Plugin/jquery-2.1.3.min.js" type="text/javascript"></script>
        <script src="Script/Plugin/jquery-ui.js" type="text/javascript"></script>
        <script src="Script/Plugin/HERRAMIENTAS.js" type="text/javascript"></script>
        <script src="Script/Portal.js" type="text/javascript"></script>
        <title>Portal</title>
    </head>
    <body>
        <div id="cuerpoPrincipal">
            <div id="cardLoger"></div>
            <div id="cardPresentacion">
                <img src="Imagen/logo.png" alt=""/>
            </div>
            <div id="cuerpoFormulario">
                <div id="menu">
                    <div id='administracion' onclick="abrirFormulario(this)">
                        <img src="Imagen/administracion.svg" alt=""/>
                    </div>
                    <div id='cliente' onclick="abrirFormulario(this)">
                        <img src="Imagen/cliente.svg" alt=""/>
                    </div>
                    <div id='reportes' onclick="abrirFormulario(this)">
                        <img src="Imagen/reporte.svg" alt=""/>
                    </div>
                </div>
                <iframe >
                    
                </iframe>
                <div id="submenu" onclick="submenu()">MENU</div>
                <div id="titulomenu">PORTAL</div>
            </div>
            
        </div>
        
    </body>
</html>
