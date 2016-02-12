var padremenu = window.parent.$("#submenu");
var padreSession = window.parent.$("#cerrarSession");
var contenedorSeleccionado = "";
var url="../Controlador/Administrador_Controller.php";
var estadoCambioProseso = false;
$(document).ready(function(){
    $(".fecha").datepicker();
    $(".fecha").val(fechaActual());
    $("#popPagoSueldo").centrar();
    $("#popPagoOtros").centrar();
});
function cambioProceso(titulo, etiqueta) {
    if (contenedorSeleccionado === etiqueta || estadoCambioProseso)
        return;
    padremenu.click();
    var f = new Date();
    var mes=f.getMonth();
    var ano=f.getFullYear();
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
            if("cuerpoListadoEmpleado"===etiqueta){
                buscarEmpleado("");  
            }
            if("cuerpoEmpleadoPago"===etiqueta){
                $("input[name=anoEmpleado]").val(ano);
                $("#mesEmpleado option[value="+mes+"]").attr("selected");
                buscarEmpleadoPago("");  
            }
            if("cuerpoOtrosPagos"===etiqueta){
                $("input[name=anoOtropago]").val(ano);
                $("#mesOtroPago option[value="+mes+"]").attr("selected");
                buscarOtroPago("");  
            }
            if("cuerpoDatosEmpresa"===etiqueta){
                datosEmpresa();  
            }
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
                if("cuerpoListadoEmpleado"===etiqueta){
                    buscarEmpleado("");  
                }
                if("cuerpoEmpleadoPago"===etiqueta){
                    $("input[name=anoEmpleado]").val(ano);
                    $("#mesEmpleado option[value="+mes+"]").attr("selected");
                    buscarEmpleadoPago("");  
                }
                if("cuerpoOtrosPagos"===etiqueta){
                    $("input[name=anoOtropago]").val(ano);
                    $("#mesOtroPago option[value="+mes+"]").attr("selected");
                    buscarOtroPago("");  
                }
                if("cuerpoDatosEmpresa"===etiqueta){
                    datosEmpresa();  
                }
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
        error+="<p>El carnet no puede ser vac�o.</p>";
    }
    if(!validar("entero",ci)){
        error+="<p>Ingrese un carnet valido.</p>";
    }
    if(nombre.length===0){
        error+="<p>El nombre no puede estar vac�o.</p>";
    }
    if(!validar("texto y entero",nombre)){
        error+="<p>El nombre no puede tener caracteres especiales.</p>";
    }
    if(direccion.length===0){
        error+="<p>La direcci�n no puede estar vac�a.</p>";
    }
    if(!validar("texto y entero",direccion)){
        error+="<p>La direcci�n no puede tener caracteres especiales.</p>";
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
        error+="<p>Las contrase�as no coinciden.</p>";
    }
    if(error.length>0){
        $("body").msmOK(error);
        return;
    }
    cargando(true);
    $.post(url, {proceso: 'registroPersonal',ci:ci,nombre:nombre,direccion:direccion,correo:correo,cumpleano:cumpleanos,fechaingreso:fechaingreso,sueldo:sueldo,cargo:cargo,cuenta:cuenta,contrasena:contrasena,perfil:perfil}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if("Error Session" ===json.error){
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            $("body").msmOK("Se registro correctamete al personal");
            $("#cuerpoRegistroPersonal").limpiarFormulario();
            $("#fotoPerfil img").attr("src","../Imagen/perfil.svg");
        }
    });
}
function buscarEmpleado(e){
    if(e!=="" && e.keyCode!==13 ){
        return;
    }
    var text=$("input[name=buscadorEmpleado]").val().trim();
    if(!validar("texto y entero",text)){
        $("body").msmOK("El criterio de busqueda no puede tener caracteres especiales.")
        return;
    }
    var estado=$("input[name=tipo]:checked").val();
    cargando(true);
    $.post(url, {proceso: 'buscarEmpleado', text: text,estado:estado}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if("Error Session" === json.error){
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            var html="";
            if(json.result===null){
                $("#tablaEmpleado tbody").html("");
                return;
            }
            var cargos="";
            $("#tablaEmpleado tbody").html("");
            for (var i = 0; i < json.result.length; i++) {
                if(json.result[i].rol==="Administrador"){
                    cargos="<select>"
                        +"<option value='0' selected>Administrador</option>"
                        +"<option value='1'>Mecanico</option>"
                        +"<option value='2'>Recepcionista</option>"
                        +"</select>";
                }
                if(json.result[i].rol==="Mecanico"){
                    cargos="<select>"
                        +"<option value='0'>Administrador</option>"
                        +"<option value='1' selected>Mecanico</option>"
                        +"<option value='2'>Recepcionista</option>"
                        +"</select>";
                }
                if(json.result[i].rol==="Recepcionista"){
                    cargos="<select>"
                        +"<option value='0' >Administrador</option>"
                        +"<option value='1' selected>Mecanico</option>"
                        +"<option value='2'>Recepcionista</option>"
                        +"</select>";
                }
                html="<tr data-id='"+json.result[i].id_personal+"'>";
                html+="<td ><div class='pequeno'><img src='"+json.result[i].foto+"' alt='"+json.result[i].nombre+"' class='vistafoto' onclick='cargarImagen(this,1)'></div></td>";
                html+="<td><div class='normal'><input type='text' value='"+json.result[i].carnet+"'/></div></td>";
                html+="<td><div class='grande2'><input type='text' value='"+json.result[i].nombre+"'/></div></td>";
                html+="<td><div class='grande'><input type='text' value='"+json.result[i].direccion+"'/></div></td>";
                html+="<td><div class='grande'><input type='text' value='"+json.result[i].correo+"'/></div></td>";
                html+="<td><div class='medio'><input type='fecha' value='"+json.result[i].cumpleano+"'/></div></td>";
                html+="<td><div class='normal'><input type='text' value='"+json.result[i].sueldo+"'/></div></td>";
                html+="<td><div class='medio'>"+json.result[i].fecha_ingreso+"</div></td>";
                html+="<td><div class='medio'>"+cargos+"</div></td>";
                html+="<td><div class='medio'>"+json.result[i].cuenta+"</div></td>";
                html+="<td><div class='medio'><input type='password' value=''></div></td>";
                html+="<td><div class='medio'><span class='negrilla' onclick='cambiarEstado(this)'>"+json.result[i].estado+"</span></div></td>";
                html+="<td><div class='medio'>"+json.result[i].fecha_retirado+"</div></td>";
                html+="</tr>";
                $("#tablaEmpleado tbody").append(html);
            }
            $(".fecha").datepicker();
            $("#tablaEmpleado").igualartabla();
        }
    });
}
function cambiarEstado(e){
    var estado=$(e).text();
    if(estado==="ACTIVO"){
        $(e).text("INACTIVO");
        $(e).parent().parent().next().find("div").text(fechaActual());
    }
    else{
        $(e).text("ACTIVO");
        $(e).parent().parent().next().find("div").text("");
    }
}
function actualizarPersonal(){
    var seleccionado=tuplaSeleccionada("tablaEmpleado");
    if (seleccionado === "") {
        $("body").msmOK("<p>No ha seleccionado al personal que desea actualizar.</p>");
        return;
    }
    var ci=seleccionado.find("input:eq(0)").val().trim();
    var nombre=seleccionado.find("input:eq(1)").val().trim();
    var direccion=seleccionado.find("input:eq(2)").val().trim();
    var correo=seleccionado.find("input:eq(3)").val().trim();
    var cumpleanos=seleccionado.find("input:eq(4)").val().trim();
    var sueldo=seleccionado.find("input:eq(5)").val().trim();
    var cargo=seleccionado.find("select option:selected").text().trim();
    var contrasena=seleccionado.find("input:eq(6)").val().trim();
    var perfil=seleccionado.find("img").attr("src").trim();
    var estado=seleccionado.find("div:eq(11)").text();
    var retirado=seleccionado.find("div:eq(12)").text();
    var error="";
    if(ci.length===0){
        error+="<p>El carnet no puede ser vac�o.</p>";
    }
    if(!validar("entero",ci)){
        error+="<p>Ingrese un carnet valido.</p>";
    }
    if(nombre.length===0){
        error+="<p>El nombre no puede estar vac�o.</p>";
    }
    if(!validar("texto y entero",nombre)){
        error+="<p>El nombre no puede tener caracteres especiales.</p>";
    }
    if(direccion.length===0){
        error+="<p>La direcci�n no puede estar vac�a.</p>";
    }
    if(!validar("texto y entero",direccion)){
        error+="<p>La direcci�n no puede tener caracteres especiales.</p>";
    }
    if(!validar("correo",correo)){
        error+="<p>El correo no es valido.</p>";
    }
    if(!validar("texto y entero",contrasena)){
        error+="<p>La contrasena no puede tener caracteres especiales.</p>";
    }
    if(contrasena.length>0 && !(contrasena.length>=4 && contrasena.length<9)){
        error+="<p>La contrasena tiene que ser mayor a 4 caracteres y menor a 8.</p>";
    }
    if(error.length>0){
        $("body").msmOK(error);
        return;
    }
    cargando(true);
    $.post(url, {proceso: 'actualizarPersonal',ci:ci,nombre:nombre,direccion:direccion,retirado:retirado,id:seleccionado.data("id")
        ,correo:correo,cumpleano:cumpleanos,sueldo:sueldo,cargo:cargo,contra:contrasena,perfil:perfil,estado:estado }, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if("Error Session" === json.error){
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            $("body").msmOK("<p>Se actualizaron los datos correctamente.</p>");
        }
    });
}
function exportar(tabla) {
    var titulo="";
    if(tabla==="tablaEmpleado"){
        var estado=$("input[name=tipo]:checked").val();
        titulo="REPORTE_PERSONAL_"+estado.toUpperCase()+"_"+fechaActualReporte();
    }
    if(tabla==="tablaDetallePago"){
        titulo="REPORTE_DETALLE_PAGOS__"+$("#cisueldo").text()+"_"+fechaActualReporte();
    }
    if(tabla==="tablaEmpleadoSueldo"){
        var estado=$("input[name=tipopago]:checked").val();
        titulo="REPORTE_PAGO_PERSONAL_"+estado.toUpperCase()+"_"+fechaActualReporte();
    }
    if(tabla==="tablaOtroPago"){
        var estado=$("input[name=tipootro]:checked").val();
        titulo="REPORTE_OTROS_PAGOS_"+estado.toUpperCase()+"_"+fechaActualReporte();
    }
    exportarExcel(tabla,titulo);
}
function buscarEmpleadoPago(e){
    if(e!=="" && e.keyCode!==13 ){
        return;
    }
    var ano=$("input[name=anoEmpleado]").val();
    var mes=$("#mesEmpleado option:selected").val();
    var text=$("input[name=buscadorEmpleadoPago]").val().trim();
    if(!validar("texto y entero",text)){
        $("body").msmOK("El criterio de busqueda no puede tener caracteres especiales.")
        return;
    }
    if(!validar("entero",ano) || parseInt(ano)<1990){
        $("body").msmOK("El a�o no es valido.")
        return;
    }
    var estado=$("input[name=tipopago]:checked").val();
     if(estado==="FALTA_PAGAR"){
        $("#btnpago").visible();
    }else{
        $("#btnpago").ocultar();
    }
    cargando(true);
    $.post(url, {proceso: 'buscarEmpleadoPago', text: text,estado:estado,ano:ano,mes:parseInt(mes)+1}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if("Error Session" === json.error){
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            var html="";
            if(json.result===null){
                $("#tablaEmpleadoSueldo tbody").html("");
                return;
            }
            $("#tablaEmpleadoSueldo tbody").html("");
            for (var i = 0; i < json.result.length; i++) {
                html="<tr ondblclick='detalleSueldo(this)' data-id='"+json.result[i].id_personal+"' data-saldo='"+json.result[i].saldo+"'>";
                html+="<td><div class='normal'>"+json.result[i].carnet+"</div></td>";
                html+="<td><div class='grande2'>"+json.result[i].nombre+"</div></td>";
                html+="<td><div class='medio'>"+json.result[i].sueldo+"</div></td>";
                html+="<td><div class='medio'>"+json.result[i].pagado+"</div></td>";
                html+="<td><div class='medio'>"+json.result[i].saldo+"</div></td>";
                html+="<td><div class='medio'>"+json.result[i].ultimoPago+"</div></td>";
                html+="</tr>";
                
            }
            $("#tablaEmpleadoSueldo tbody").html(html);
            $("#tablaEmpleadoSueldo").igualartabla();
        }
    });
}
var personalSelecionado=0;
function abrirpagoSueldo(){
    var seleccionado=tuplaSeleccionada("tablaEmpleadoSueldo");
    if (seleccionado === "") {
        $("body").msmOK("<p>No ha seleccionado al personal que se le cancelara el sueldo.</p>");
        return;
    }
    
    var ano=$("input[name=anoEmpleado]").val();
    var mes=$("#mesEmpleado option:selected").val();
    personalSelecionado=seleccionado.data("id");
    cargando(true);
    $.post(url, {proceso: 'datosPersonal', personal: personalSelecionado,ano:ano,mes:parseInt(mes)+1}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if("Error Session" === json.error){
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            mes=$("#mesEmpleado option:selected").text();
            $("#tituloSueldo").text("Pago del mes de "+mes+" del "+ano);
            $("#cisueldo").text(json.result.persona.carnet);
            $("#nombresueldo").text(json.result.persona.nombre);
            $("#sueldosueldo").text(json.result.persona.sueldo);
            $("#cargosueldo").text(json.result.persona.rol);
            $("#popPagoSueldo").visible();
            $("#saldosueldo").text(seleccionado.data("saldo"));
            $("input[name=descsueldo]").val("");
            $("input[name=montosueldo]").val("");
            $(".background").visible();
            if(json.result.tabla===null){
                $("#tablaDetallePago tbody").html("");
                return;
            }
            var html="";
            for (var i = 0; i < json.result.tabla.length; i++) {
                html+="<tr>";
                html+="<td><div class='normal'>"+json.result.tabla[i].fecha+"</div></td>";
                html+="<td><div class='normal'>"+json.result.tabla[i].monto+"</div></td>";
                html+="<td><div class='grande2'>"+json.result.tabla[i].descripcion+"</div></td>";
                html+="<td><div class='normal'>"+json.result.tabla[i].estado+"</div></td>";
                html+="</tr>";
            }
            $("#tablaDetallePago tbody").html(html);
            $("#tablaDetallePago").igualartabla();
        }
    });   
}
function pagoSueldo(e){
    if(e===1){
        $("#popPagoSueldo").ocultar();
        $(".background").ocultar();
        return;
    }
    var monto=parseFloat($("input[name=montosueldo]").val());
    var saldo=parseFloat($("#saldosueldo").text());
    var desc=$("input[name=descsueldo]").val();
    if(monto<=0){
        $("body").msmOK("<p>No puede realizar un pago negativo de sueldo.</p>");
        return;
    }
    if(monto>saldo){
        $("body").msmOK("<p>En este mes ya le cancelo todo el sueldo al empleado "
                +$("#nombresueldo").text()+" baya al siguiente mes para darle un adelanto.</p>");
        return;
    }
    if(!validar("texto y entero",desc)){
        $("body").msmOK("<p>La descripci�n del pago no puede tener caracteres especiales.</p>");
        return;
    }
    var ano=$("input[name=anoEmpleado]").val();
    var mes=parseInt($("#mesEmpleado option:selected").val())+1;
    mes=mes<10?"0"+mes:mes;
    cargando(true);
    $.post(url, {proceso: 'pagarSueldo',monto:monto,personal: personalSelecionado,fechacorresponde:"01/"+mes+"/"+ano,fecha:fechaActual()}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if("Error Session" === json.error){
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            $("body").msmOK("El pago se realizo correctamente.");
            $("#popPagoSueldo").ocultar();
            $(".background").ocultar();
            buscarEmpleadoPago("");
            
        }
    });
}
function buscarOtroPago(e){
    if(e!=="" && e.keyCode!==13 ){
        return;
    }
    var ano=$("input[name=anoOtropago]").val();
    var mes=$("#mesOtroPago option:selected").val();
    var text=$("input[name=buscadorOtroPago]").val().trim();
    if(!validar("texto y entero",text)){
        $("body").msmOK("El criterio de busqueda no puede tener caracteres especiales.")
        return;
    }
    if(!validar("entero",ano) || parseInt(ano)<1990){
        $("body").msmOK("El a�o no es valido.")
        return;
    }
    var estado=$("input[name=tipootro]:checked").val();
     if(estado==="activo"){
        $("#btnpago").visible();
    }else{
        $("#btnpago").ocultar();
    }
    cargando(true);
    $.post(url, {proceso: 'buscarOtroPago', text: text,estado:estado,ano:ano,mes:parseInt(mes)+1}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if("Error Session" === json.error){
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            var html="";
            if(json.result===null){
                $("#tablaOtroPago tbody").html("");
                return;
            }
            $("#tablaOtroPago tbody").html("");
            for (var i = 0; i < json.result.length; i++) {
                html+="<tr >";
                html+="<td><div class='normal'>"+json.result[i].fecha+"</div></td>";
                html+="<td><div class='normal'>"+json.result[i].monto+"</div></td>";
                html+="<td><div class='grande2'>"+json.result[i].descripcion+"</div></td>";
                html+="<td><div class='normal'>"+json.result[i].estado+"</div></td>";
                html+="</tr>";
                
            }
            $("#tablaOtroPago tbody").html(html);
            $("#tablaOtroPago").igualartabla();
        }
    });
}
function abrirpagoOtro(tipo){
    if(tipo===1){
        $("#popPagoOtros,.background").visible();
        $(".background").visible();
    }else{
        $("#popPagoOtros,.background").ocultar();
        $("#popPagoOtros").limpiarFormulario();
    }
}
function pagoOtros(){
    var fecha=$("input[name=otrofecha]").val();
    var monto=$("input[name=montofecha]").val();
    var desc=$("input[name=otrodesc]").val();
    if(!validar("texto y entero",desc)){
        $("body").msmOK("La descripci�n no puede tener caracteres especiales");
        return;
    }
    if(parseFloat(monto)<=0){
        $("body").msmOK("No ha especificado el monto pagado");
        return;
    }
    cargando(true);
    $.post(url, {proceso: 'pagarotros', fecha:fecha,monto:monto,desc:desc}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if("Error Session" === json.error){
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            $("body").msmOK("Se registro el pago correctamente.");
            abrirpagoOtro(2);
            buscarOtroPago("");
        }
    });
}
function datosEmpresa(){
    cargando(true);
    $.post(url, {proceso: 'datosEmpresa'}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if("Error Session" === json.error){
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            $("input[name=nombreTaller]").val(json.result.nombre);
            $("input[name=rz]").val(json.result.razon_social);
            $("#LogoEmpresa img").attr('src',json.result.logo);
            $("input[name=aniversarioTaller]").val(json.result.aniversario);
            $("input[name=nit]").val(json.result.nit);
            $("input[name=direccionTaller]").val(json.result.direccion);
            $("input[name=nroFactura]").val(json.result.nro_factura);
            $("input[name=fechaDosificacion]").val(json.result.fecha_factura);
            $("input[name=llaveDosificacion]").val(json.result.llave_dosificacion);
            $("input[name=autorizacion]").val(json.result.nro_autorizacion);
            $("input[name=findosificacion]").val(json.result.fecha_finDosificacion);
            $("input[name=telefonoTaller]").val(json.result.telefono);
        }
    });
}
function actualizarEmpresa(){
    var nombre=$("input[name=nombreTaller]").val().trim();
    var rz=$("input[name=rz]").val().trim();
    var logo=$("#LogoEmpresa img").attr('src').trim();
    var aniversario=$("input[name=aniversarioTaller]").val().trim();
    var nit=$("input[name=nit]").val().trim();
    var direccion=$("input[name=direccionTaller]").val().trim();
    var fechadosificacion=$("input[name=fechaDosificacion]").val().trim();
    var llavedosificacion=$("input[name=llaveDosificacion]").val().trim();
    var autorizacion=$("input[name=autorizacion]").val().trim();
    var telefono=$("input[name=telefonoTaller]").val().trim();
    var error="";
    if(nit.length===0){
        error+="<p>El nit no puede estar vac�o.</p>";
    }
    if(!validar("texto y entero",nit)){
        error+="<p>El nit no puede tener caracteres especiales.</p>";
    }
    if(nombre.length===0){
        error+="<p>El nombre no puede estar vac�o.</p>";
    }
    if(!validar("texto y entero",nombre)){
        error+="<p>El nombre no puede tener caracteres especiales.</p>";
    }
    if(rz.length===0){
        error+="<p>La razon social no puede estar vac�a.</p>";
    }
    if(!validar("texto y entero",rz)){
        error+="<p>La razon social no puede tener caracteres especiales.</p>";
    }
    if(!validar("texto y entero",direccion)){
        error+="<p>El direcci�n no puede tener caracteres especiales.</p>";
    }
    if(!validar("texto y entero",telefono)){
        error+="<p>El telefono no puede tener caracteres especiales.</p>";
    }  
    if(!validar("texto y entero",autorizacion)){
        error+="<p>La autorizacion no puede tener caracteres especiales.</p>";
    }
    cargando(true);
    $.post(url, {proceso: 'actualizardatosEmpresa',nombre:nombre,rz:rz,logo:logo,aniversario:aniversario
        ,nit:nit,direccion:direccion,fechadosificacion:fechadosificacion,llave:llavedosificacion,autorizacion:autorizacion,telefono:telefono}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if("Error Session" === json.error){
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            
        }
    });
}