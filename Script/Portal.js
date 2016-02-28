var titulo="PORTAL";
$(document).ready(function(){
   $("#administracion,#cliente,#reportes").hover(function(){
       $("#titulomenu").html($(this).attr("id").toUpperCase());
   },function (){
       $("#titulomenu").html(titulo);
   }); 
    $.post("Controlador/Portal_Controller.php", {proceso: 'perfil'}, function (response) {
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                $("#cerrarSession").click();
            }
            $("body").msmOK(json.error);
        } else {
            $("#cardLoger img").attr("src",json.result.foto);
        }
    });
});
function abrirFormulario(ele){
    if($(ele).attr("id")==="cliente")    
        $("#submenu").ocultar();
    else
        $("#submenu").visible(1);
    titulo=$(ele).attr("id").toUpperCase();
    $("#titulomenu").html(titulo);
    $("iframe").attr("src","Formularios/"+$(ele).attr("id")+".php");
}
function cerrarSession(){
    $(location).attr('href',"../index.php");
}
var estadomenu=false;
function submenu(){
    if(estadomenu)return;
    estadomenu=true;
    var etiqueta=$("iframe").contents().find("#ListaMenu");
    if(etiqueta.data("tipo")==="0" ||etiqueta.data("tipo")===0){
        $("iframe").contents().find(".background").visible();
        etiqueta.animate({
            left:-30
        },"slow",function(){
            etiqueta.data("tipo","1");
            estadomenu=false;
        });  
    }else{
        $("iframe").contents().find(".background").ocultar();
        etiqueta.animate({
            left:-240
        },"slow",function(){
            etiqueta.data("tipo","0");
            estadomenu=false;
        });
    }
    
}