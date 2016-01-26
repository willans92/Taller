<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>LOGIN</title>
        <link href="Estilo/ESTILO.css" rel="stylesheet" type="text/css"/>
        <link href="Estilo/Responsive.css" rel="stylesheet" type="text/css" media/>
        <script src="Script/Plugin/jquery-2.1.3.min.js" type="text/javascript"></script>
        <script src="Script/Plugin/jquery-ui.js" type="text/javascript"></script>
        <script src="Script/Plugin/HERRAMIENTAS.js" type="text/javascript"></script>
        <script src="Script/Login.js" type="text/javascript"></script>
    </head>
    <body>
        <div id="ingreso" class="point">
            INGRESAR
        </div>
        <div id='cuerpoIngresar' >
            <div style="height: 263px">
                <img src="Imagen/Logo_taller.png" alt=""/>
            </div>
            <span class='negrillaenter'>Cuenta</span>
            <input type='text' value='' class='grande3' data-min='0' data-max='10' name="cuentaLogeo" >
            <span class='negrillaenter'>Contraseña</span>
            <input type='password' value='' class='grande3' onkeyup="entrar(event)" data-min='5' data-max='10' name="contrasenaLogeo">
            <span class='error' data-acro=''></span>
            <span class='correcto' data-acro=''></span>
            <div class='centrar'>
                <button onclick='entrar("")' class="grande">ENTRAR</button> 
            </div>
            
        </div>
    </body>
</html>
