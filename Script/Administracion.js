var padremenu = window.parent.$("#submenu");
var contenedorSeleccionado = "";
var url="../Controlador/Administrador_Controller.php";
var estadoCambioProseso = false;
$(document).ready(function(){
    $(".fecha").datepicker();
});
function cambioProceso(titulo, etiqueta) {
    if (contenedorSeleccionado === etiqueta || estadoCambioProseso)
        return;
    padremenu.click();
    $("h1").text(titulo);
    estadoCambioProseso = true;
    if (contenedorSeleccionado === "") {
        $("#" + etiqueta).visible(1);
        $("#" + etiqueta).animate({
            top: 50,
            left: 15,
            width: 630,
            height: 460,
        }, "slow", function () {
            contenedorSeleccionado = etiqueta;
            estadoCambioProseso = false;
        });
    } else {
        $("#" + contenedorSeleccionado).animate({
            top: 295,
            left: 330,
            width: 10,
            height: 10,
        }, "slow", function () {
            $("#" + contenedorSeleccionado).ocultar();
            $("#" + etiqueta).visible(1);
            $("#" + etiqueta).animate({
                top: 50,
                left: 15,
                width: 630,
                height: 460,
            }, "slow", function () {
                contenedorSeleccionado = etiqueta;
                estadoCambioProseso = false;
            });
        });
    }
}
function RegistrarPersonal(tipo){
    if(tipo===2){
        $("#cuerpoRegistroPersonal").limpiarFormulario();
        $("#fotoPerfil img").attr("src","../Imagen/perfil.svg");
        return;
    }
    var ci=$("input[name=ci]").val().trim();
    var nombre=$("input[name=nombre]").val().trim();
    var direccion=$("input[name=direccion]").val().trim();
    var correo=$("input[name=correo]").val().trim();
    var cumpleanos=$("input[name=cumpleano]").val().trim();
    var fechaingreso=$("input[name=fechaingreso]").val().trim();
    var sueldo=$("input[name=sueldo]").val().trim();
    var cargo=$("#tipoCuenta option:selected").text().trim();
    var cuenta=$("input[name=cuenta]").val().trim();
    var contrasena=$("input[name=contrasena]").val().trim();
    var recontrasena=$("input[name=recontrasena]").val().trim();
    var perfil=$("#fotoPerfil img").attr("src").trim();
    var error="";
    if(ci.length===0){
        error+="<p>El carnet no puede ser vacío.</p>";
    }
    if(!validar("entero",ci)){
        error+="<p>Ingrese un carnet valido.</p>";
    }
    if(nombre.length===0){
        error+="<p>El nombre no puede estar vacío.</p>";
    }
    if(!validar("texto",nombre)){
        error+="<p>El nombre no puede tener caracteres especiales.</p>";
    }
    if(direccion.length===0){
        error+="<p>La dirección no puede estar vacía.</p>";
    }
    if(!validar("texto y entero",direccion)){
        error+="<p>La dirección no puede tener caracteres especiales.</p>";
    }
    if(!validar("correo",correo)){
        error+="<p>El correo no es valido.</p>";
    }
    if(!validar("texto y entero",cuenta)){
        error+="<p>La cuenta no puede tener caracteres especiales.</p>";
    }
    if(!(cuenta.length>=4 && cuenta.length<9)){
        error+="<p>La cuenta tiene que ser mayor a 4 caracteres y menor a 8.</p>";
    }
    if(!validar("texto y entero",contrasena)){
        error+="<p>La contrasena no puede tener caracteres especiales.</p>";
    }
    if(!(contrasena.length>=4 && contrasena.length<9)){
        error+="<p>La contrasena tiene que ser mayor a 4 caracteres y menor a 8.</p>";
    }
    if(contrasena!==recontrasena){
        error+="<p>Las contraseñas no coinciden.</p>";
    }
    if(error.length>0){
        $("body").msmOK(error);
        return;
    }
    $("#cargando").visible();
    $.post(url, {proceso: 'registroPersonal',ci:ci,nombre:nombre,direccion:direccion,correo:correo,cumpleano:cumpleanos,fechaingreso:fechaingreso,sueldo:sueldo,cargo:cargo,cuenta:cuenta,contrasena:contrasena,perfil:perfil}, function (response) {
        $("#cargando").ocultar();
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            $("body").msmOK("<p>Se interrumpio la conexión mientras se traían los datos. Intente nuevamente.</p>");
        } else {
            
        }
    });
}