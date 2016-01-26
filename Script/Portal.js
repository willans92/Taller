var titulo="PORTAL";
$(document).ready(function(){
   $("#administracion,#cliente,#reportes").hover(function(){
       $("#titulomenu").html($(this).attr("id").toUpperCase());
   },function (){
       $("#titulomenu").html(titulo);
   }); 
});
function abrirFormulario(ele){
    if($(ele).attr("id")==="cliente")    
        $("#submenu").ocultar();
    else
        $("#submenu").visible(1);
    $("iframe").attr("src","Formularios/"+$(ele).attr("id")+".php");
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