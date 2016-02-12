var imagenCargando="../Imagen/cargando.gif";
(function (a) {
    $.fn.extend({
        visible: function (tipo) {
            if(tipo===1){
                $(this).css("display","block");
                $(this).focus();
            }else{
                $(this).css("display","inline-block");
            }
        },
        igualartabla: function (){
            var tabla=$(this);
            tabla.find("tbody tr").click(function(){
                tabla.find("tbody tr").css("background-color","white");
                $(this).css("background-color","#17B566");
            });
            $(this).find("tbody").css("width",$(this).find("thead").width()+20);
        },
        ocultar: function () {
            $(this).css("display","none");
        },
        dragable:function(drag,drog,evento){
            $(drag).draggable({
                revert: "invalid",
                refreshPositions: true,
                containment: 'parent',
                drag: function (event, ui) {
                    $(this).css({
                        cursor:"move",
                        opacity:"0.3",
                        transform: "scale(0.8,0.8)",
                    });
                },
                stop: function (event, ui) {
                    $(this).css({
                        cursor:"pointer",
                        opacity:"1",
                        transform: "scale(1,1)"
                    });
                }
            });
            $(drog).droppable({
                drop: evento
            });
        },
        limpiarFormulario: function () {
            $(this).find("input").val("");
            $(this).find(".error").text("");
            $(this).find(".correcto").text("");
            $(this).find("input").css("background","white");
            $(this).find("input[type=number]").val(0);
            $(this).find("select option:eq(0)").attr("selected",true);
            $(this).find(".fecha").val(fechaActual());
        },
        
        centrar: function () {
            $(this).css({
                position: 'fixed',
                left: ($(window).width() - $(this).outerWidth()) / 2,
                top: (($(window).height() - $(this).outerHeight()) / 2)-35
            });
            $(window).resize(function () {
                $(this).css({
                    position: 'fixed',
                    left: ($(window).width() - $(this).outerWidth()) / 2,
                    top: (($(window).height() - $(this).outerHeight()) / 2)-35
                });
            });
        },
        msmOK:function(options){
            var result="<div class='background' id='backgroundAux'></div><div class='popup' id='msmOK'>"+
                            "<span class='negrillaenter centrar'>ALERTA</span>"+
                            "<div>"+options+"</div>"+
                            "<div class='centrar'>"+
                                 "<button onclick='ok()' class='normal'>OK</button>"+
                            "</div>"+
                        "</div>"
            $(this).append(result);
            $("#msmOK").visible(1);
            $("#msmOK").centrar();
            $("#msmOK button").focus();
            $(".background").visible(1);
        },
        msmPregunta:function(pregunta,funcion){
            var result="<div class='background' id='backgroundAux'></div><div class='popup' id='msmOK'>"+
                            "<span class='negrillaenter centrar'>ALERTA</span>"+
                            "<div>"+pregunta+"</div>"+
                            "<div class='centrar'>"+
                                 "<button onclick='"+funcion+"()' class='normal'>SI</button>"+
                                 "<button onclick='ok()' class='normal'>NO</button>"+
                            "</div>"+
                        "</div>"
            $(this).append(result);
            $("#msmOK").visible(1);
            $("#msmOK").centrar();
            $("#msmOK button").focus();
            $(".background").visible(1);
        }
        ,
        by:function(options){
            var result="<div class='background' id='backgroundAux'></div>"+
                        "<div id='by'>"+
                            "<div class='centrar negrilla'>SISTEMA DE LABORATORIOS UNIDOS \"LABUN\"</div>"+
                            "<div  style='margin-right: 19px; width: 100px; padding-top: 13px; float: left;'>"+
                                "<img src='IMAGENES/logo.png' alt=''/>"+
                            "</div>"+
                            "<div style='width: 250px; float: left;'>"+
                                "<span class='negrillaenter'>Hecho Por:</span>"+
                                 "Ing. Williams Armando Montenegro Mansilla"+
                                "<span class='negrillaenter'>Telefono: </span>"+
                                 "3251551 - 75685675"+
                                 "<span class='negrillaenter'>Correo: </span>"+
                                  "WdigitalSolution02@gmail.com"+    
                            "</div>"+
                            "<span class='negrilla point' style='position: absolute; top: 4px; right: 6px;' onclick='cerrarBy()'>(x)</span>"
                        "</div>";
            $(this).append(result);
            $("#by").visible(1);
            $("#by").centrar();
            $(".background").visible(1);
        }
        ,
        validar: function () {
            this.each(function () {
                var $this = $(this);
                var typ = $this.attr("type");
                switch (typ) {
                    case "text":
                        $this.focus(function () {
                            $this.keyup(function () {
                                var min=parseInt($this.data("min"));
                                var max=parseInt($this.data("max"));
                                var valor = $this.val().length;
                                if (valor>=min && valor<=max) {
                                    $this.css({"background-color": "#00ff00"});
                                    $this.next().text("");
                                } else {
                                    $this.next().text($this.next().data("acro")+" debe tener como minimo "+min+" caracteres y maximo "+max);
                                    $this.css({"background-color": "#e44e2d"});
                                }
                            });
                        });
                        break;
                    case "number":
                        $this.focus(function () {
                            $this.keyup(function () {
                                var min=parseInt($this.data("min"));
                                var max=parseInt($this.data("max"));
                                var valor = $this.val().length;
                                if (valor>=min && valor<=max) {
                                    $this.css({"background-color": "#00ff00"});
                                    $this.next().text("");
                                } else {
                                    $this.next().text($this.next().data("acro")+" debe tener como minimo "+min+" caracteres y maximo "+max);
                                    $this.css({"background-color": "#e44e2d"});
                                }   
                                
                            });
                        });
                        break;
                    case "email":
                        $this.focus(function () {
                            $this.keyup(function () {
                                var expresion = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
                                var valor = $this.val();
                                if (!expresion.test(valor)) {
                                    $this.css({"background-color": "#e44e2d"});
                                    $this.next().text($this.next().data("acro")+" electronico invalido");
                                } else {
                                    $this.css({"background-color": "#00ff00"});
                                    $this.next().text("");
                                }
                            });
                        });
                        break;
                    case "password":
                        $this.focus(function () {
                            $this.keyup(function () {
                                var min=parseInt($this.data("min"));
                                var max=parseInt($this.data("max"));
                                var valor = $this.val().length;
                                if (valor>=min && valor<=max) {
                                    $this.css({"background-color": "#00ff00"});
                                    $this.next().text("");
                                } else {
                                    $this.next().text($this.next().data("acro")+" debe tener como minimo "+min+" caracteres y maximo "+max);
                                    $this.css({"background-color": "#e44e2d"});
                                }
                            });
                        });
                        break;
                }
            });
        },
        validarActualizar: function () {
            this.each(function () {
                var $this = $(this);
                var typ = $this.attr("type");
                
                switch (typ) {
                    case "text":
                        var min=parseInt($this.data("min"));
                        var max=parseInt($this.data("max"));
                        var valor = $this.val().length;
                        if (valor>=min && valor<=max) {
                            $this.css({"background-color": "#00ff00"});
                            $this.next().text("");
                        } else {
                            $this.next().text($this.next().data("acro")+" debe tener como minimo "+min+" caracteres y maximo "+max);
                            $this.css({"background-color": "#e44e2d"});
                        }
                        break;
                    case "number":
                        var min=parseInt($this.data("min"));
                        var max=parseInt($this.data("max"));
                        var valor = $this.val().length;
                        if(isNaN($this.val())){
                            $this.next().text($this.next().data("acro")+" debe ser de tipo numerico");
                            $this.css({"background-color": "#e44e2d"});
                            return;
                        }
                        if (valor>=min && valor<=max) {
                            $this.css({"background-color": "#00ff00"});
                            $this.next().text("");
                        } else {
                            $this.next().text($this.next().data("acro")+" debe tener como minimo "+min+" caracteres y maximo "+max);
                            $this.css({"background-color": "#e44e2d"});
                        }
                        break;
                    case "email":
                        var expresion = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
                        var valor = $this.val();
                        if (!expresion.test(valor)) {
                            $this.css({"background-color": "#e44e2d"});
                            $this.next().text($this.next().data("acro")+" electronico es invalido");
                        } else {
                            $this.css({"background-color": "#00ff00"});
                            $this.next().text("");
                        }
                        break;
                    case "password":
                        var min=parseInt($this.data("min"));
                        var max=parseInt($this.data("max"));
                        var valor = $this.val().length;
                        if (valor>=min && valor<=max) {
                            $this.css({"background-color": "#00ff00"});
                            $this.next().text("");
                        } else {
                            $this.next().text($this.next().data("acro")+" debe tener como minimo "+min+" caracteres y maximo "+max);
                            $this.css({"background-color": "#e44e2d"});
                        }
                        break;
                }
            });
        }
    });
})(jQuery);
$(document).ready(function(){
   $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
        'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié;', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
    };
    $.datepicker.setDefaults($.datepicker.regional["es"]);
});
function validar(tipo,texto){
    texto+=" ";
    switch (tipo){
        case "texto":
            var expresion=/^[a-zA-Z\.\,\s-_º()=?¿/%$@!:;{}óíáéúñÍÁÉÚÓ]+$/;
            if(expresion.exec(texto)){
                return true;
            }
            break;
        case "correo":
            /*var expresion=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (expresion.test(texto))*/
                return true;
            break;
        case "entero":
            var expresion=/^[0-9\s]+$/;
            if(expresion.exec(texto)){
                return true;
            }
            break;
        case "texto y entero":
            var expresion=/^[0-9a-zA-Z\.\,\s-_º()=?¿/%$@!:;{}óíáéúñÍÁÉÚÓ]+$/;
            if(expresion.exec(texto)){
                return true;
            }
            break;
    }
    return false;
}
function ok(){
    $("#backgroundAux").remove();
    $(".background").ocultar();
    $("#msmOK").remove();
}
function cerrarBy(){
    $("#backgroundAux").remove();
    $(".background").ocultar();
    $("#by").remove();
}
function horaActual(){
    var f = new Date();
    var hora=f.getHours();
    var min=f.getMinutes();
    var seg=f.getSeconds();
    hora=hora<10?"0"+hora:hora;
    min=min<10?"0"+min:min;
    seg=seg<10?"0"+seg:seg;
    return hora+":"+min+":"+seg;
}
function fechaActual(){

    var f = new Date();
    var dia=f.getDate();
    var mes=f.getMonth()+1;
    var ano=f.getFullYear();
    dia=dia<10?"0"+dia:dia;
    mes=mes<10?"0"+mes:mes;
    return dia+"/"+mes+"/"+ano;
}
function fechaActualReporte(){
    var f = new Date();
    var dia=f.getDate();
    var mes=f.getMonth()+1;
    var ano=f.getFullYear();
    dia=dia<10?"0"+dia:dia;
    mes=mes<10?"0"+mes:mes;
    return dia+"_"+mes+"_"+ano;
}
var imagenAModificar;
function cargarImagen(input, tipo) {
    if (tipo === 1 || tipo === "1") {
        imagenAModificar = $(input);
        $("body").append("<input type='file' onchange='cargarImagen(this,2)' id='fotocargar' style='display: none;'/><canvas id='canvas' style='display: none;'></canvas>");
        $('#fotocargar').click();
        return;
    }
    if (input.files && input.files[0]) {
        cargando(true);
        var reader = new FileReader();
        reader.onload = function (e) {
            var canvas = document.getElementById("canvas");
            var ctx = canvas.getContext('2d');
            var img = new Image();
            img.onload = function () {
                canvas.width = 300;
                canvas.height = 300;
                ctx.drawImage(img, 0, 0, 300, 300);
                imagenAModificar.attr("src", canvas.toDataURL(input.files[0].type));
                cargando(false);
                $("#fotocargar").remove();
                $("#canvas").remove();
            };
            img.src = reader.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function tuplaSeleccionada(tabla){
    var seleccionado = "";
    var lista = $("#"+tabla+" tbody tr");
    for (var i = 0; i < lista.length; i++) {
        if ($(lista[i]).css("background-color") === "rgb(23, 181, 102)") {
            $("#"+tabla+" tbody tr").css("background", "none");
            seleccionado = $(lista[i]);
            break;
        }
    }
    return seleccionado;
}
/*importa
 * 
 * <script src="Script/Plugin/tableExport.min.js" type="text/javascript"></script>
   <script src="Script/Plugin/tableExport.min.js" type="text/javascript"></script>
 */
function exportarExcel(tabla,titulo) {
    cargando(true);
    var lista = $("#"+tabla).find("tr");
    var elemento="<table id='tablaExcel'><thead>";
    for (var i = 0; i < lista.length; i++) {
        var tr=$(lista[i]).find("div");
        elemento+="<tr>";
        for (var j = 0; j < tr.length; j++) {
            var ele=$(tr[j]).children();
            var texto="";
            if(ele.is('input')){
                texto=ele.val();
            }
            if(ele.is('select')){
                texto=ele.find("option:selected").text();
            }
            if(texto===""){
                texto=$(tr[j]).text();
            }
            if(i===0){
               elemento+="<th>"+texto+"</th>"; 
            }else{
                elemento+="<td>"+texto+"</td>"; 
            }
        }
        elemento+="</tr>";
        if(i===0){
            elemento+="</thead><tboddy>";
        }
    }
    elemento+="</tboddy></table>";
    $("body").append(elemento);
    cargando(false);
    $('#tablaExcel').tableExport({type:'xls',fileName: titulo});  
    $("#tablaExcel").remove();
}
function cargando(estado){
    if(estado){
         var elemento="<div  id='cargando' style='z-index: 2;'>"
                        +"<div>"
                        +"<img src='"+imagenCargando+"' title='CARGANDO'/>"
                        +"<span class='negrillaenter centrar'>CARGANDO</span>"
                        +"</div>";
                        +"</div>";
        $("body").append(elemento);
    }else{
        $("#cargando").remove();
    }
}
/*
 * 
 * var lista = $("#"+tabla).find("tr");
    var aux = "";
    for (var i = 0; i < lista.length; i++) {
        var tr=$(lista[i]).find("div");
        for (var j = 1; j < tr.length; j++) {
            var ele=$(tr[j]).children();
            var texto="";
            if(ele.is('input')){
                texto=ele.val();
            }
            if(ele.is('select')){
                texto=ele.find("option:selected").text();
            }
            if(texto===""){
                texto=$(tr[j]).text();
            }
            aux += texto + "{";
        }
        aux = aux.substring(0, aux.length-1) + ",";
    }
    aux = aux.substring(0, aux.length-1);
    $("#formExcel").remove();
    $("body").append("<form id='formExcel' action='"+url+"' method='post' style='display: none;' accept-charset='ISO-8859-1'>"
                    +"<input type='text' value='exportarExcel' name='proceso'/>"
                    +"<input type='text' value='a{b{c{d,a{b{c{d,a{b{c{d,a{b{c{d' name='excel'/>"
                    +"<input type='text' value='REPORTE' name='nombreExcel'/>"
                    +"<input type='text' value='' name='tituloExcel'/>"
                    +"<button id='descarga'>descargar</button>"
                    +"</form>");
    $("input[name=excel]").val(aux);
 */

