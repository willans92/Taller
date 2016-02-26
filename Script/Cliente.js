var padreSession = window.parent.$("#cerrarSession");
var contenedorSeleccionado = "";
var url = "../Controlador/Cliente_Controller.php";
var estadoCambioProseso = false;
$(document).ready(function () {
    $(".fecha").datepicker();
    $("#popMarca").centrar();
    $("#popVehiculo").centrar();
    $(".fecha").val(fechaActual());
    cambioProceso("Listado de Clientes", "cuerpoListadoCliente")
});
function cambioProceso(titulo, etiqueta) {
    if (contenedorSeleccionado === etiqueta || estadoCambioProseso)
        return;
 
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
            if ("cuerpoListadoCliente" === etiqueta) {
                idCliente = 0;
                buscarClientes("");
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
                if ("cuerpoListadoCliente" === etiqueta) {
                    idCliente = 0;
                    buscarClientes("");
                }
                if ("cuerpoHistorial" === etiqueta) {
                     $("#cuerpoHistorial .fecha").val(fechaActual());
                    historial();
                }
                if ("cuerpoNuevoCliente" === etiqueta) {
                    $("#contenedorVehiculo").html("");
                    reparacionID = 0;
                    autoId = 0;
                    modoHistorial=false;
                    $("#cuerpoNuevoCliente").limpiarFormulario();
                    if (idCliente === 0) {
                        $("#contentVehiculo").ocultar();
                        $("#registroCliente").text("REGISTAR");
                    } else {
                        $("#contentVehiculo").visible();
                        $("#registroCliente").text("GUARDAR");
                        datosCliente()
                    }
                }
                if ("cuerpoReparacion" === etiqueta) {
                    cargando(true);
                    estadoReparacion = "activo falta pago";
                    $.post(url, {proceso: 'abrirReparacion', auto: autoId,idreparacion:reparacionID}, function (response) {
                        cargando(false);
                        var json = $.parseJSON(response);
                        if (json.error.length > 0) {
                            if ("Error Session" === json.error) {
                                padreSession.click();
                            }
                            $("body").msmOK(json.error);
                        } else {
                            if(modoHistorial){
                                $("#cuerpoReparacion .mas").ocultar();
                                $("#cuerpoReparacion button").ocultar();
                                $("#repara2 input").attr("readonly",true);
                                $("#repara2 select").attr("disabled",true);
                                $("#atrasrepara").visible();
                                $("input[name=fechaIngresoReparacion]").attr("disabled",true);
                            }else{
                                $("#cuerpoReparacion .mas").visible();
                                $("#cuerpoReparacion button").visible();
                                $("#repara2 input").removeAttr("readonly");
                                $("#repara2 select").removeAttr("disabled");
                                $("input[name=fechaIngresoReparacion]").removeAttr("disabled");
                            }
                            $("#vehiculoreparacion").text(json.result.auto.vehiculo);
                            $("#modeloreparacion").text(json.result.auto.modelo);
                            $("#chasisreparacion").text(json.result.auto.nro_chasis);
                            $("#marcareparacion").text(json.result.auto.marca);
                            $("#colorreparacion").text(json.result.auto.color);
                            $("#placareparacion").text(json.result.auto.placa);
                            $("#obsreparacion").text(json.result.auto.observacion);
                            if (json.result.mecanico != null) {
                                var mecanico = "<option value='0'>--Seleccione un mecánico--</option>";
                                for (var i = 0; i < json.result.mecanico.length; i++) {
                                    mecanico += "<option value='" + json.result.mecanico[i].id_personal + "'>" + json.result.mecanico[i].nombre + "</option>";
                                }
                                $("#mecanico").html(mecanico);
                            }
                            if (json.result.reparacion != null) {
                                var meca = json.result.reparacion.id_personal == "" ? 0 : json.result.reparacion.id_personal;
                                $("#mecanico option[value=" + meca + "]").attr("selected", true);
                                $("input[name=otReparacion]").val(json.result.reparacion.OT);
                                $("input[name=kilometroReparacion]").val(json.result.reparacion.kilometro);
                                $("input[name=fechaIngresoReparacion]").val(json.result.reparacion.fecha_Ingreso);
                                $("input[name=fechaSalidaReparacion]").val(json.result.reparacion.fecha_salida);
                                $("input[name=combustibleReparacion]").val(json.result.reparacion.combustible);
                                estadoReparacion = json.result.reparacion.estado;
                                reparacionID = json.result.reparacion.id_reparacion;
                            }
                            var listatrabajo = json.result.trabajo;
                            var html = "";
                            var total = 0;
                            if (listatrabajo != null)
                                for (var i = 0; i < listatrabajo.length; i++) {
                                    html += "<div class='itemTrabajo'>";
                                    html += "<div class='medio'>" + listatrabajo[i].descripcion + "</div>";
                                    if(modoHistorial){
                                        html += "<input type='number' class='pequeno' data-id='" + listatrabajo[i].id_trabajos + "' value='" + listatrabajo[i].costo + "' step='0.5' min='0' readonly>";
                                    }else{
                                        html += "<input type='number' class='pequeno' data-id='" + listatrabajo[i].id_trabajos + "' value='" + listatrabajo[i].costo + "' step='0.5' min='0'>";
                                    }
                                    html += "</div>";
                                    total += parseFloat(listatrabajo[i].costo);
                                }
                            $("#totaltrabajo").text(total.toFixed(2));
                            $("#contenedorTrabajo").html(html);
                            html = "";
                            var listaaccesorio = json.result.accesorio;
                            if (listaaccesorio != null)
                                for (var i = 0; i < listaaccesorio.length; i++) {
                                    html += "<p data-id='" + listaaccesorio[i].id_accesorio + "'>- " + listaaccesorio[i].descripcion + "</p>";
                                }
                            $("#contenedorAccesorios").html(html);
                            
                        }
                    });
                }
            });
        });
    }
}
var vehiculoOption = "";
var marcaOption = "";
var idCliente = 0;
var autoId = 0;
var estadoReparacion = "activo falta pago";
var reparacionID = 0;
var modoHistorial=false;
function historial(){
    var de=$("input[name=fechadeHistorial]").val();
    var hasta=$("input[name=fechahastaHistorial]").val();
    cargando(true);
    $.post(url, {proceso: 'historialReparacion', de:de,hasta:hasta,auto: autoId,cliente:idCliente}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            var html="";
            if(json.result!=null)
                for (var i = 0; i < json.result.length; i++) {
                    html+="<tr ondblclick='detallehistorial("+json.result[i].id_reparacion+","+json.result[i].id_auto+")'><td><div class='normal'>"+json.result[i].fecha_Ingreso+"</div></td>";
                    html+="<td><div class='normal'>"+json.result[i].fecha_salida+"</div></td>";
                    html+="<td><div class='normal'>"+json.result[i].kilometro+"</div></td>";
                    html+="<td><div class='normal'>"+json.result[i].combustible+"</div></td>";
                    html+="<td><div class='normal'>"+json.result[i].OT+"</div></td>";
                    html+="<td><div class='normal'>"+json.result[i].total+"</div></td>";
                    html+="<td><div class='normal'>"+json.result[i].estado+"</div></td></tr>";// cambia por moroso , finalizado , reparando
                }
            $("#tablaHistorialRe tbody").html(html);   
            $("#tablaHistorialRe").igualartabla();   
        }
    });
}
function detallehistorial(reparacion,auto){
    reparacionID=reparacion;
    autoId=auto;
    modoHistorial=true;
    cambioProceso("Historial de Reparacion", "cuerpoReparacion");
}
function datosCliente() {
    cargando(true);
    $.post(url, {proceso: 'cliente', id: idCliente}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            $("input[name=ci]").val(json.result.cliente.Ci);
            $("input[name=nombre]").val(json.result.cliente.nombre);
            $("input[name=direccion]").val(json.result.cliente.direccion);
            $("input[name=telefonoCasa]").val(json.result.cliente.Telefono_Casa);
            $("input[name=telefonoOficina]").val(json.result.cliente.Telefono_Oficina);
            $("input[name=telefonoCelular]").val(json.result.cliente.Telefono_Celular);
            $("#fotoPerfil img").attr("src", json.result.cliente.foto);
            vehiculoOption = "<option value='0'>--Seleccione Vehiculo--</option>";
            marcaOption = "<option value='0'>--Seleccione Marca--</option>";
            if (json.result.marca !== null) {
                rellenadorMarca(json.result.marca);
            }
            if (json.result.vehiculo !== null) {
                rellenadorVehiculo(json.result.vehiculo);
            }
            if (json.result.auto != null) {
                for (var i = 0; i < json.result.auto.length; i++) {
                    var item = "<div class='itemVehiculo' data-id='" + json.result.auto[i].id_auto + "'>";
                    item += "<div id='toolauto'><img src='../Imagen/tools.svg' alt='Reparar' onclick='repararAuto(this)'/><img src='../Imagen/delete.svg' alt='Eliminar' onclick='eliminarAuto(this)'/></div>";
                    item += "<div class='contenedor50'>";
                    item += "<span class='negrillaenter'>Vehiculo</span>";
                    item += "<select class='normal2 vehiculo'>";
                    item += vehiculoOption;
                    item += "</select>";
                    item += "</div>";
                    item += "<div class='contenedor50'>";
                    item += "<span class='negrillaenter'>Marca</span>";
                    item += "<select class='normal2 marca'>";
                    item += marcaOption;
                    item += "</select>";
                    item += "</div>";
                    item += "<div class='contenedor50'>";
                    item += "<span class='negrillaenter'>Modelo</span>";
                    item += "<input type='text' class='normal2' name='modelo' value='" + json.result.auto[i].modelo + "'/>";
                    item += "</div>";
                    item += "<div class='contenedor50'>";
                    item += "<span class='negrillaenter'>Color</span>";
                    item += "<input type='text' class='normal2' name='color' value='" + json.result.auto[i].color + "'/>";
                    item += "</div>";
                    item += "<div class='contenedor50'>";
                    item += "<span class='negrillaenter'>Nro Chasis</span>";
                    item += "<input type='text' class='normal2' name='chasis' value='" + json.result.auto[i].nro_chasis + "'/>";
                    item += "</div>";
                    item += "<div class='contenedor50'>";
                    item += "<span class='negrillaenter'>Placa</span>";
                    item += "<input type='text' class='normal2' name='placa' value='" + json.result.auto[i].placa + "'/>";
                    item += "</div>";
                    item += "<span class='negrillaenter'>Observacion</span>";
                    item += "<input type='text' class='grande2' name='observacion' value='" + json.result.auto[i].observacion + "'/>";
                    item += "</div>";
                    $("#contenedorVehiculo").append(item);
                    $("#contenedorVehiculo .vehiculo:last option[value='" + json.result.auto[i].id_vehiculo + "']").attr("selected", true);
                    $("#contenedorVehiculo .marca:last option[value='" + json.result.auto[i].id_marca + "']").attr("selected", true);
                }
            }

        }
    });
}
function registrarReparacion() {
    var listadoAccesorios = $("#contenedorAccesorios p");
    var accesorios = [];
    for (var i = 0; i < listadoAccesorios.length; i++) {
        accesorios.push($(listadoAccesorios[i]).data("id"));
    }
    var listadoTrabajo = $("#contenedorTrabajo input");
    var trabajo = [];
    for (var i = 0; i < listadoTrabajo.length; i++) {
        var precio = $(listadoTrabajo[i]).val();
        trabajo.push({
            id: $(listadoTrabajo[i]).data("id"),
            precio: precio
        });
    }
    var mecanico = $("#mecanico option:selected").val();
    var ot = $("input[name=otReparacion]").val();
    var km = $("input[name=kilometroReparacion]").val();
    var ingreso = $("input[name=fechaIngresoReparacion]").val();
    var combustible = $("input[name=combustibleReparacion]").val();
    var total=$("#totaltrabajo").text();
    var salida="";
    if (estadoReparacion == "") {
        estadoReparacion = "activo falta pago";
    }
    if(estadoReparacion.indexOf("fin")>=0){
        salida = fechaActual();
    }
    cargando(true);
    $.post(url, {proceso: 'registrarReparacion', accesorio: accesorios, trabajo: trabajo
        , mecanico: mecanico, ot: ot, km: km, ingreso: ingreso,salida:salida, total:total,combustible: combustible
        , idreparacion: reparacionID, auto: autoId, estado: estadoReparacion}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            if (estadoReparacion === "fin") {
                estadoReparacion = "activo";
            } else {
                estadoReparacion = "activo falta pago";
            }
            $("body").msmOK(json.error);
        } else {
            $("body").msmOK("Se registro correctamente la reparacion.");
            reparacionID = json.result;
            if (estadoReparacion.indexOf("fin") >= 0) {
                estadoReparacion = "activo falta pago";
                reparacionID = 0;
                $("#contenedorAccesorios").html("");
                $("#contenedorTrabajo").html("");
                $("#repara2").limpiarFormulario();
            }
        }
    });

}
function exportar(tabla) {
    var de =$("input[name=fechadeHistorial]").val().replace('///g', '_');
    var hasta =$("input[name=fechahastaHistorial]").val().replace('///g', '_');
    var titulo="HISTORIAL_DE_"+de+"_hasta_"+hasta;
    exportarExcel(tabla,titulo);
}
function rellenadorMarca(array) {
    for (var i = 0; i < array.length; i++) {
        marcaOption += "<option value='" + array[i].id_marca + "'>" + array[i].descripcion + "</option>";
    }
}
function rellenadorVehiculo(array) {
    for (var i = 0; i < array.length; i++) {
        vehiculoOption += "<option value='" + array[i].id_vehiculo + "'>" + array[i].descripcion + "</option>";
    }
}
function finalizarReparacion(estado) {
    if (reparacionID == 0) {
        $("body").msmOK("Primero registre la reparación.");
        return;
    }
    if (estado === 1) {
        $("body").msmPregunta("Esta seguro de terminar la reparación del auto.", "finalizarReparacion");
        return;
    }
    if (estadoReparacion === "activo") {
        estadoReparacion = "fin";
    } else {
        estadoReparacion = "fin falta pago";
    }
    registrarReparacion();
    ok();
}
function pagoReparacion(estado) {
    if (reparacionID == 0) {
        $("body").msmOK("Primero registre la reparación.");
        return;
    }
    if (estado === 1) {
        $("#poppago").visible();
        $(".background").visible();
        $("#poppago").centrar();
        $("#poppago").limpiarFormulario();
        cargando(true);
        $.post(url, {proceso: 'detallepagoreparacion', reparacion: reparacionID}, function (response) {
            cargando(false);
            var json = $.parseJSON(response);
            if (json.error.length > 0) {
                if ("Error Session" === json.error) {
                    padreSession.click();
                }
                $("body").msmOK(json.error);
            } else {
                var total = 0;
                var html = "";
                if (json.result != null)
                    for (var i = 0; i < json.result.length; i++) {
                        html += "<tr><td><div class='pequeno'>" + json.result[i].descripcion + "</div></td>";
                        html += "<td><div class='normal'>" + json.result[i].fecha + "</div></td>";
                        html += "<td><div class='normal'>" + json.result[i].monto + "</div></td></tr>";
                        total += parseFloat(json.result[i].monto);
                    }
                $("#tablaPago tbody").html(html);
                $("#tablaPago").igualartabla();
                var totalpago = parseFloat($("#totaltrabajo").text());
                $("input[name=totalrpago]").val(totalpago);
                $("input[name=pagadopago]").val(total);
                $("input[name=faltapago]").val((totalpago - total));
            }
        });
        return;
    }
    if (estado === 2) {
        $("#poppago").ocultar();
        $(".background").ocultar();
        return;
    }
    var fecha = $("input[name=fechapago]").val();
    var monto = parseFloat($("input[name=montopago]").val());
    var falta = parseFloat($("input[name=faltapago]").val());
    if (falta == 0) {
        $("body").msmOK("Esta reparacion ya fue cancelada.");
        return;
    }
    if (monto > falta) {
        $("body").msmOK("El pago esta excediendo a la deuda del cliente.");
        return;
    }
    var desc=$("#tablaPago tr").length;
    cargando(true);
    $.post(url, {proceso: 'pagarReparacion',desc:desc, reparacion: reparacionID, monto: monto, fecha: fecha}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            var listadoAccesorios = $("#contenedorAccesorios p");
            var accesorios = [];
            for (var i = 0; i < listadoAccesorios.length; i++) {
                accesorios.push($(listadoAccesorios[i]).data("id"));
            }
            var listadoTrabajo = $("#contenedorTrabajo input");
            var trabajo = [];
            for (var i = 0; i < listadoTrabajo.length; i++) {
                var precio = $(listadoTrabajo[i]).val();
                trabajo.push({
                    id: $(listadoTrabajo[i]).data("id"),
                    precio: precio
                });
            }
            var mecanico = $("#mecanico option:selected").val();
            var ot = $("input[name=otReparacion]").val();
            var km = $("input[name=kilometroReparacion]").val();
            var ingreso = $("input[name=fechaIngresoReparacion]").val();
            var combustible = $("input[name=combustibleReparacion]").val();
            var est="";
            if (falta-monto == 0) {
                est = "activo";
            }else{
                est = "activo falta pago";
            }
            cargando(true);
            $.post(url, {proceso: 'registrarReparacion', accesorio: accesorios, trabajo: trabajo
                , mecanico: mecanico, ot: ot, km: km, ingreso: ingreso, combustible: combustible, idreparacion: reparacionID, auto: autoId, estado: est}, function (response) {
                cargando(false);
                var json = $.parseJSON(response);
                if (json.error.length > 0) {
                    if ("Error Session" === json.error) {
                        padreSession.click();
                    }
                    $("body").msmOK(json.error);
                } else {
                    $("body").msmOK("El pago se registro correctamente.");
                    $("#poppago").ocultar();
                }
            });
        }
    });
}
function buscarClientes(e) {
    if (e !== "" && e.keyCode !== 13) {
        return;
    }
    var text = $("input[name=buscadorCliente]").val();
    if (!validar("texto y entero", text)) {
        $("body").msmOK("El criterio de busqueda no puede tener caracteres especiales.");
        return;
    }
    cargando(true);
    $.post(url, {proceso: 'buscarClientes', text: text}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            var html = "";
            if (json.result === null) {
                $("#contenedorCliente").html("");
                return;
            }
            var html = "";
            for (var i = 0; i < json.result.length; i++) {
                html += "<div class='itemCliente' ondblclick='detalleCliente(" + json.result[i].id_cliente + ")'>";
                html += "<div class='negrilla centrar'>" + json.result[i].nombre + "</div>";
                html += "<div class='contenedor30'>";
                html += "<img src='" + json.result[i].foto + "' alt='" + json.result[i].nombre + "'/>";
                html += "</div>";
                html += "<div class='contenedor70'>";
                html += "<span class='negrilla'>CI:</span>" + json.result[i].Ci + "<br>";
                html += "<span class='negrilla'>Telfono Casa:</span>" + json.result[i].Telefono_Casa + "<br>";
                html += "<span class='negrilla'>Telfono Oficina:</span>" + json.result[i].Telefono_Oficina + "<br>";
                html += "<span class='negrilla'>Telfono Celular:</span>" + json.result[i].Telefono_Celular + "<br>";
                html += "</div>";
                html += "</div>";

            }
            $("#contenedorCliente").html(html);
        }
    });
}
function detalleCliente(id) {
    idCliente = id;
    cambioProceso("Cliente", "cuerpoNuevoCliente")
}
function masVehiculo() {
    var item = "<div class='itemVehiculo' data-id='0'>";
    item += "<div id='toolauto'><img src='../Imagen/tools.svg' alt='Reparar' onclick='repararAuto(this)'/><img src='../Imagen/delete.svg' alt='Eliminar' onclick='eliminarAuto(this)'/></div>";
    item += "<div class='contenedor50'>";
    item += "<span class='negrillaenter'>Vehiculo</span>";
    item += "<select class='normal2 vehiculo'>";
    item += vehiculoOption;
    item += "</select>";
    item += "</div>";
    item += "<div class='contenedor50'>";
    item += "<span class='negrillaenter'>Marca</span>";
    item += "<select class='normal2 marca'>";
    item += marcaOption;
    item += "</select>";
    item += "</div>";
    item += "<div class='contenedor50'>";
    item += "<span class='negrillaenter'>Modelo</span>";
    item += "<input type='text' class='normal2' name='modelo' value=''/>";
    item += "</div>";
    item += "<div class='contenedor50'>";
    item += "<span class='negrillaenter'>Color</span>";
    item += "<input type='text' class='normal2' name='color' value=''/>";
    item += "</div>";
    item += "<div class='contenedor50'>";
    item += "<span class='negrillaenter'>Nro Chasis</span>";
    item += "<input type='text' class='normal2' name='chasis' value=''/>";
    item += "</div>";
    item += "<div class='contenedor50'>";
    item += "<span class='negrillaenter'>Placa</span>";
    item += "<input type='text' class='normal2' name='placa' value=''/>";
    item += "</div>";
    item += "<span class='negrillaenter'>Observacion</span>";
    item += "<input type='text' class='grande2' name='observacion' value=''/>";
    item += "</div>";
    $("#contenedorVehiculo").append(item);
}
function repararAuto(ele) {
    var id = $(ele).parent().parent().data("id");
    if (id == 0) {
        $("body").msmOK("Guarde cambios para poder reparar el auto.");
        return;
    }
    autoId = id;
    cambioProceso("Reparacion", "cuerpoReparacion");
}
function eliminarAuto(ele) {
    var elemeto = $(ele).parent().parent();
    if (elemeto.data("id") == 0) {
        elemeto.remove();
    }
    cargando(true);
    $.post(url, {proceso: 'eliminarAuto', id: elemeto.data("id")}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            elemeto.remove();
        }
    });
}
function registroCliente(e) {
    var ci = $("input[name=ci]").val().trim();
    var nombre = $("input[name=nombre]").val().trim();
    var direccion = $("input[name=direccion]").val().trim();
    var casa = $("input[name=telefonoCasa]").val().trim();
    var oficina = $("input[name=telefonoOficina]").val().trim();
    var celular = $("input[name=telefonoCelular]").val().trim();
    var foto = $("#fotoPerfil img").attr("src");
    var text = "";
    if (!validar("texto y entero", ci)) {
        text += "<p>El ci no puede tener caracteres especiales.</p>";
    }
    if (nombre.length === 0) {
        text += "<p>El nombre no puede ser vacío.</p>";
    }
    if (!validar("texto y entero", nombre)) {
        text += "<p>El nombre no puede tener caracteres especiales.</p>";
    }
    if (!validar("texto y entero", direccion)) {
        text += "<p>La direccion no puede tener caracteres especiales.</p>";
    }
    if (!validar("entero", casa)) {
        text += "<p>El telefono de la casa del cliente solo acepta numero.</p>";
    }
    if (!validar("entero", oficina)) {
        text += "<p>El telefono de la oficina del cliente solo acepta numero.</p>";
    }
    if (!validar("entero", celular)) {
        text += "<p>El telefono celular del cliente solo acepta numero.</p>";
    }
    var listavehiculo = [];
    $("#contentVehiculo input").css("background", "white");
    $("#contentVehiculo select").css("background", "white");
    var itemvehiculo = $(".itemVehiculo");
    for (var i = 0; i < itemvehiculo.length; i++) {
        var item = $(itemvehiculo[i]);
        var vehiculo = item.find("select.vehiculo option:selected").val();
        var marca = item.find("select.marca option:selected").val();
        var modelo = item.find("input[name=modelo]").val();
        var chasis = item.find("input[name=chasis]").val();
        var color = item.find("input[name=color]").val();
        var placa = item.find("input[name=placa]").val();
        var observacion = item.find("input[name=observacion]").val();
        if (vehiculo == 0) {
            item.find("select.vehiculo").css("background", "red");
            text += "<p>No ha seleccionado el tipo de vehiculo de un auto.</p>";
            break;
        }
        if (marca == 0) {
            item.find("select.vehiculo").css("background", "red");
            text += "<p>No ha seleccionado la marca de un auto.</p>";
            break;
        }
        if (!validar("texto y entero", modelo)) {
            item.find("input[name=modelo]").css("background", "red");
            text += "<p>El modelo no puede tener caracteres especiales.</p>";
            break;
        }
        if (!validar("texto y entero", chasis)) {
            item.find("input[name=chasis]").css("background", "red");
            text += "<p>El chasis no puede tener caracteres especiales.</p>";
            break;
        }
        if (!validar("texto y entero", color)) {
            item.find("input[name=color]").css("background", "red");
            text += "<p>El color no puede tener caracteres especiales.</p>";
            break;
        }
        if (!validar("texto y entero", placa)) {
            item.find("input[name=placa]").css("background", "red");
            text += "<p>El color no puede tener caracteres especiales.</p>";
            break;
        }
        if (!validar("texto y entero", observacion)) {
            item.find("input[name=observacion]").css("background", "red");
            text += "<p>La observacion no puede tener caracteres especiales.</p>";
            break;
        }
        if (observacion.length > 200) {
            item.find("input[name=observacion]").css("background", "red");
            text += "<p>La observacion no puede tener mas de 200 caracteres.</p>";
            break;
        }
        listavehiculo.push({
            id: item.data("id"),
            vehiculo: vehiculo,
            marca: marca,
            modelo: modelo,
            chasis: chasis,
            color: color,
            placa: placa,
            observacion: observacion
        });
    }
    if (text.length > 0) {
        $("body").msmOK(text);
        return;
    }
    cargando(true);
    $.post(url, {proceso: 'registroCliente', ci: ci, nombre: nombre, direccion: direccion
        , casa: casa, oficina: oficina, celular: celular, foto: foto, cliente: idCliente, vehiculos: listavehiculo}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            if (idCliente === 0) {
                $("body").msmOK("Se registro al cliente " + nombre + ".");
                if (json.result.marca !== null) {
                    rellenadorMarca(json.result.marca);
                }
                if (json.result.vehiculo !== null) {
                    rellenadorVehiculo(json.result.vehiculo);
                }
                $("#contentVehiculo").visible();
                $("#registroCliente").text("GUARDAR");
                $("h1").text("Cliente");
            } else {
                $("body").msmOK("Se actualizaron los datos correctamente del cliente " + nombre + ".");
            }
            idCliente = json.result.cliente;
        }
    });
}
function crearMarca(tipo) {
    var text = $("input[name=marcaNueva]").val();
    if (tipo == 0) {
        $("#popMarca").visible();
        $(".background").visible();
        $("input[name=marcaNueva]").val("");
        return;
    }
    if (tipo == 2 || text.length == 0) {
        $("#popMarca").ocultar();
        $(".background").ocultar();
        return;
    }
    if (!validar("texto y entero", text)) {
        $("body").msmOK("No se aceptan caracteres especiales en la descripcion de la marca.");
        return;
    }
    cargando(true);
    $.post(url, {proceso: 'crearMarca', text: text}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            marcaOption += "<option value='" + json.result + "'>" + text + "</option>";
            var itemvehiculo = $(".itemVehiculo");
            for (var i = 0; i < itemvehiculo.length; i++) {
                var item = $(itemvehiculo[i]);
                item.find("select.marca").append("<option value='" + json.result + "'>" + text + "</option>");
            }
            $("body").msmOK("Se registro correctamente la marca.");
            $("#popMarca").ocultar();
            $(".background").ocultar();
        }
    });
}
function crearVehiculo(tipo) {
    var text = $("input[name=vehiculoNueva]").val();
    if (tipo == 0) {
        $("#popVehiculo").visible();
        $(".background").visible();
        $("input[name=vehiculoNueva]").val("");
        return;
    }
    if (tipo == 2 || text.length == 0) {
        $("#popVehiculo").ocultar();
        $(".background").ocultar();
        return;
    }
    if (!validar("texto y entero", text)) {
        $("body").msmOK("No se aceptan caracteres especiales en la descripcion del vehiculo.");
        return;
    }
    cargando(true);
    $.post(url, {proceso: 'crearVehiculo', text: text}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            vehiculoOption += "<option value='" + json.result + "'>" + text + "</option>";
            var itemvehiculo = $(".itemVehiculo");
            for (var i = 0; i < itemvehiculo.length; i++) {
                var item = $(itemvehiculo[i]);
                item.find("select.vehiculo").append("<option value='" + json.result + "'>" + text + "</option>");
            }
            $("body").msmOK("Se registro correctamente el vehiculo.");
            $("#popVehiculo").ocultar();
            $(".background").ocultar();
        }
    });
}
function masAccesorios(tipo) {
    if (tipo == 1) {
        $("#popacesorio").ocultar();
        $(".background").ocultar();
        var lista = $("#popacesorio input:checked");
        var html = "";
        for (var i = 0; i < lista.length; i++) {
            var item = $(lista[i]);
            html += "<p data-id='" + item.val() + "'>- " + item.parent().text() + "</p>";
        }
        $("#contenedorAccesorios").html(html);
        return;
    }
    $("#popacesorio input[type=checkbox]").removeAttr("checked");
    cargando(true);
    $.post(url, {proceso: 'lsitaAccesorios'}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            $("#popacesorio").visible();
            $(".background").visible();
            if (json.result == null) {
                $("#popacesorio .cuerpo").html("<div class='itemaccesorios'><centrar><input type='text' class='medio' placeholder='NUEVO ACCESORIO' onkeyup='crearAccesorio(event)' name='accesoriotxt'/> </centrar></div>");
                $("#popacesorio").centrar();
                return
            }
            var html = "";
            for (var i = 0; i < json.result.length; i++) {
                html += "<div class='itemaccesorios'><input type='checkbox' value='" + json.result[i].id_accesorio + "'> " + json.result[i].descripcion + "</div>";
            }
            html += "<div class='itemaccesorios'><centrar><input type='text' class='medio' placeholder='NUEVO ACCESORIO' onkeyup='crearAccesorio(event)' name='accesoriotxt'/> </centrar></div>";
            $("#popacesorio .cuerpo").html(html);
            var lista = $("#contenedorAccesorios p");
            for (var i = 0; i < lista.length; i++) {
                $("#popacesorio .cuerpo input[value=" + $(lista[i]).data("id") + "]").attr("checked", true);
            }
            $("#popacesorio").centrar();
        }
    });
}
function masTrabajo(tipo) {
    if (tipo == 1) {
        $("#poptrabajo").ocultar();
        $(".background").ocultar();
        var lista = $("#poptrabajo input:checked");
        var html = "";
        var total = 0;
        for (var i = 0; i < lista.length; i++) {
            var item = $(lista[i]).parent().parent();
            html += "<div class='itemTrabajo'>";
            html += "<div class='medio'>" + item.next().find("input").val() + "</div>";
            html += "<input type='number' class='pequeno' data-id='" + item.parent().data("id") + "' value='" + item.next().next().find("input").val() + "' step='0.5' min='0'>";
            html += "</div>";
            total += parseFloat(item.next().next().find("input").val());
        }
        $("#totaltrabajo").text(total.toFixed(2));
        $("#contenedorTrabajo").html(html);
        return;
    }
    $("input[name=descTrabajo]").val("");
    $("input[name=precioTrabajo]").val("");
    $("#poptrabajo input[type=checkbox]").removeAttr("checked");
    cargando(true);
    $.post(url, {proceso: 'listaTrabajo'}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            $("#poptrabajo").visible();
            $(".background").visible();
            if (json.result == null) {
                $("#poptrabajo .cuerpo").html("<div class='itemTrabajo'><centrar><input type='text' class='medio' placeholder='NUEVO TRABAJO' onkeyup='crearTrabajo(event)' name='trabajotxt'/> </centrar></div>");
                $("#poptrabajo").centrar();
                return
            }
            var html = "";
            for (var i = 0; i < json.result.length; i++) {
                html += "<tr data-id='" + json.result[i].id_trabajo + "'><td><div class='pequeno'><input type='checkbox' value='" + json.result[i].id_trabajo + "'></div></td>";
                html += "<td><div class='grande'><input type='text' value='" + json.result[i].descripcion + "'></div></td>";
                html += "<td><div class='normal'><input type='text' value='" + json.result[i].costo + "'></div></td><tr>";
            }
            $("#tablaTrabajo tbody").html(html);
            var lista = $("#contenedorTrabajo input");
            for (var i = 0; i < lista.length; i++) {
                $("#tablaTrabajo tbody input[value=" + $(lista[i]).data("id") + "]").attr("checked", true);
            }
            $("#tablaTrabajo").igualartabla();
            $("#poptrabajo").centrar();
        }
    });
}
function crearAccesorio(e) {
    if (e.keyCode === 13) {
        var text = $("#popacesorio input[name=accesoriotxt]").val();
        if (!validar("texto y entero", text)) {
            $("body").msmOK("No puede tener caracteres especiales el nuevo accesorio.");
            return;
        }
        if (text.length > 50) {
            $("body").msmOK("El nuevo accesorio no puede exceder de los 50 caracteres");
            return;
        }
        cargando(true);
        $.post(url, {proceso: 'crearAccesorio', text: text}, function (response) {
            cargando(false);
            var json = $.parseJSON(response);
            if (json.error.length > 0) {
                if ("Error Session" === json.error) {
                    padreSession.click();
                }
                $("body").msmOK(json.error);
            } else {
                $("#popacesorio input[name=accesoriotxt]").parent().parent().remove();
                var html = "<div class='itemaccesorios'><input type='checkbox' value='" + json.result + "'> " + text + "</div>";
                html += "<div class='itemaccesorios'><centrar><input type='text' class='medio' placeholder='NUEVO ACCESORIO' onkeyup='crearAccesorio(event)' name='accesoriotxt'/> </centrar></div>";
                $("#popacesorio .cuerpo").append(html);
                $("#popacesorio").centrar();
            }
        });
    }
}
function crearTrabajo() {
    var idtrabajo = 0;
    var desc = $("input[name=descTrabajo]").val();
    var precio = $("input[name=precioTrabajo]").val();
    var error = "";
    if (!validar("texto y entero", desc)) {
        error += "<p>La descripcion no puede tener caracteres especiales.</p>";
    }
    if (!validar("decimal", precio)) {
        error += "<p>El precio solo acepta datos numericos.</p>";
    }
    if (parseFloat(precio) < 0) {
        error += "<p>El precio no puede ser negativo.</p>";
    }
    cargando(true);
    $.post(url, {proceso: 'crearTrabajo', desc: desc, precio: precio, trabajo: idtrabajo}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            $("body").msmOK("Se creo el trabajo correctamente.");
            var html = "";
            html += "<tr data-id='" + response + "'><td><div class='pequeno'><input type='checkbox' value='" + response + "'></div></td>";
            html += "<td><div class='grande'><input type='text' value='" + desc + "'></div></td>";
            html += "<td><div class='normal'><input type='text' value='" + precio + "'></div></td><tr>";
            $("#tablaTrabajo tbody").append(html);
            $("input[name=descTrabajo]").val("");
            $("input[name=precioTrabajo]").val("");
            $("#tablaTrabajo").igualartabla();
        }
    });
}
function actualizarTrabajo() {
    var tr = tuplaSeleccionada("tablaTrabajo");
    var idtrabajo = 0;
    if (!tr == "") {
        idtrabajo = tr.data("id");
    } else {
        $("body").msmOK("Debe seleccionar el trabajo que desea actualizar.");
        return;
    }
    var input = $(tr).find("input");
    var desc = $(input[1]).val();
    var precio = $(input[2]).val();
    var error = "";
    if (!validar("texto y entero", desc)) {
        error += "<p>La descripcion no puede tener caracteres especiales.</p>";
    }
    if (!validar("decimal", precio)) {
        error += "<p>El precio solo acepta datos numericos.</p>";
    }
    if (parseFloat(precio) < 0) {
        error += "<p>El precio no puede ser negativo.</p>";
    }
    cargando(true);
    $.post(url, {proceso: 'crearTrabajo', desc: desc, precio: precio, trabajo: idtrabajo}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            $("body").msmOK("Se actualizaron los datos correctamente.");
        }
    });
}