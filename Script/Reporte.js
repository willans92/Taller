var padreSession = window.parent.$("#cerrarSession");
var contenedorSeleccionado = "";
var padremenu = window.parent.$("#submenu");
var url = "../Controlador/Reporte_Controller.php";
var estadoCambioProseso = false;
$(document).ready(function () {
    $(".fecha").datepicker();
    $(".fecha").val(fechaActual());
});
function cambioProceso(titulo, etiqueta) {
    if (contenedorSeleccionado === etiqueta || estadoCambioProseso)
        return;
    $("h1").text(titulo);
    //padremenu.click();
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
            if ("cuerpoMoroso" === etiqueta) {
                $("input[name=buscarMovimiento]").val("");
                $(".fecha").val(fechaActual());
                buscarMorosos("");
            }

            if ("cuerpoMovimiento" === etiqueta) {
                $("input[name=buscarMovimiento]").val("");
                $(".fecha").val(fechaActual());
                buscarMovimiento("");
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
                if ("cuerpoMoroso" === etiqueta) {
                    buscarMorosos("");
                }
                if ("cuerpoMovimiento" === etiqueta) {
                    buscarMovimiento("");
                }
                if ("cuerpoImpresion" === etiqueta) {
                    if (reparacionID == 0) {
                        $("#tituloimpresion").html("COTIZACION DE LA REPARACION");
                    } else {
                        $("#tituloimpresion").html("DETALLE DE LA REPARACION");
                    }
                    if ($("#mecanico option:selected").val() != 0) {
                        $("#mecanicoimpresion").html($("#mecanico option:selected").text());
                    } else {
                        $("#mecanicoimpresion").html("");
                    }
                    cargando(true);
                    $.post(url, {proceso: 'datosempresa'}, function (response) {
                        cargando(false);
                        var json = $.parseJSON(response);
                        if (json.error.length > 0) {
                            if ("Error Session" === json.error) {
                                padreSession.click();
                            }
                            $("body").msmOK(json.error);
                        } else {
                            $("#logoimpresion").attr("src", json.result.logo);
                            $("#direccionimpresion").text(json.result.direccion);
                            $("#telefonoimpresion").text(json.result.telefono);
                            $("#nombreimpresion").text(json.result.nombre);
                            $("#correoimpresion").text(json.result.correo);
                            $("#marcaimpresion").html($("#marcareparacion").text());
                            $("#vehiculoimpresion").html($("#vehiculoreparacion").text());
                            $("#placaimpresion").html($("#placareparacion").text());
                            $("#observacionimpresion").html($("#obsreparacion").text());
                            var html = "";
                            var lista = $("#contenedorTrabajo input");
                            var total = 0;
                            for (var i = 0; i < lista.length; i++) {
                                var valor = parseFloat($(lista[i]).val());
                                var precioreal = parseFloat($(lista[i]).data("val"));
                                var descuento = precioreal - valor;
                                html += "<tr><td><div class='chico'>1</div></td>";
                                html += "<td><div class='grande'>" + $(lista[i]).prev().html() + "</div></td>";
                                html += "<td><div class='pequeno'>" + precioreal + "</div></td>";
                                html += "<td><div class='chico'>" + descuento.toFixed(2) + "</div></td>";
                                html += "<td><div class='chico'>" + valor.toFixed(2) + "</div></td></tr>";
                                total += valor;
                            }
                            $("#totalimpr").html(total.toFixed(2));
                            $("#tablaimpresion tbody").html(html);
                        }
                    });

                }
                if ("cuerpoReparacion" === etiqueta) {
                    cargando(true);
                    $.post(url, {proceso: 'abrirReparacion', auto: autoId, idreparacion: reparacionID}, function (response) {
                        cargando(false);
                        var json = $.parseJSON(response);
                        if (json.error.length > 0) {
                            if ("Error Session" === json.error) {
                                padreSession.click();
                            }
                            $("body").msmOK(json.error);
                        } else {
                            $("#repara2 input").attr("readonly", true);
                            $("#repara2 select").attr("disabled", true);
                            $("input[name=fechaIngresoReparacion]").attr("disabled", true);
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
                                $("input[name=reciboReparacion]").val(json.result.reparacion.recibo);
                                $("input[name=facturaReparacion]").val(json.result.reparacion.factura);
                            }
                            var listatrabajo = json.result.trabajo;
                            var html = "";
                            var total = 0;
                            if (listatrabajo != null)
                                for (var i = 0; i < listatrabajo.length; i++) {
                                    html += "<div class='itemTrabajo'>";
                                    html += "<div class='medio'>" + listatrabajo[i].descripcion + "</div>";
                                    html += "<input type='number' class='pequeno' data-id='" + listatrabajo[i].id_trabajos + "' data-val='" + listatrabajo[i].costo2 + "' value='" + listatrabajo[i].costo + "' step='0.5' min='0' readonly>";
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
function imprimir() {
    $("#cuerpoImpresion").css({
        height: "auto"
    });
    window.print();
}
function exportar(tabla, titulo) {
    var de = $("input[name=fechademoroso]").val().replace('///g', '_');
    var hasta = $("input[name=fechahastamoroso]").val().replace('///g', '_');
    var titulo2 = titulo + "_DE_" + de + "_hasta_" + hasta;
    exportarExcel(tabla, titulo2);
}
function exportarMovimiento(tabla, titulo) {
    var de = $("input[name=fechademoroso]").val().replace('///g', '_');
    var hasta = $("input[name=fechahastamoroso]").val().replace('///g', '_');
    var tipo = $("#cuerpoMovimiento input:checked").val().toUpperCase();
    var titulo2 = titulo + "_" + tipo + "_DE_" + de + "_hasta_" + hasta;
    exportarExcel(tabla, titulo2);
}
var reparacionID = 0;
var autoId = 0;
var estadoid = "";
function pagoReparacion(estado) {
    if (estado === 1) {
        var seleccionado = tuplaSeleccionada("cuerpoMoroso");
        if (seleccionado == "") {
            $("body").msmOK("No ha seleccionado a ningun moroso.");
            return;
        }
        reparacionID = seleccionado.data("reparacion");
        autoId = seleccionado.data("auto");
        estadoid = seleccionado.data("estado");
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
                if (json.result.detalle != null)
                    for (var i = 0; i < json.result.detalle.length; i++) {
                        html += "<tr><td><div class='pequeno'>" + json.result.detalle[i].descripcion + "</div></td>";
                        html += "<td><div class='normal'>" + json.result.detalle[i].fecha + "</div></td>";
                        html += "<td><div class='normal'>" + json.result.detalle[i].monto + "</div></td></tr>";
                        total += parseFloat(json.result.detalle[i].monto);
                    }
                $("#tablaPago tbody").html(html);
                $("#tablaPago").igualartabla();
                var totalpago = parseFloat(json.result.reparacion.total);
                $("input[name=totalrpago]").val(totalpago);
                $("input[name=pagadopago]").val(total);
                $("input[name=faltapago]").val((totalpago - total).toFixed(2));
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
    if (monto - falta == 0) {
        if (estadoid == "activo falta pago") {
            estadoid = "activo";
        }
        if (estadoid == "fin falta pago") {
            estadoid = "fin";
        }
    }
    var desc = $("#tablaPago tr").length;
    cargando(true);
    $.post(url, {proceso: 'pagarReparacion', desc: desc, reparacion: reparacionID, monto: monto, fecha: fecha, estado: estadoid}, function (response) {
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
            buscarMorosos("");
        }
    });
}
function buscarMorosos(e) {
    if (e !== "" && e.keyCode !== 13) {
        return;
    }
    var text = $("input[name=buscarmoroso]").val();
    var de = $("input[name=fechademoroso]").val();
    var hasta = $("input[name=fechahastamoroso]").val();
    if (!validar("texto y entero", text)) {
        $("body").msmOK("El criterio de busqueda no puede tener caracteres especiales.");
        return;
    }
    cargando(true);
    $.post(url, {proceso: 'morosos', text: text, de: de, hasta: hasta}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            var html = "";
            if (json.result !== null)
                for (var i = 0; i < json.result.length; i++) {
                    var total = parseFloat(json.result[i].total);
                    var pagado = parseFloat(json.result[i].pagado);
                    var falta = (total - pagado).toFixed(2);
                    html += "<tr ondblclick='verhistorial(" + json.result[i].id_auto + "," + json.result[i].id_reparacion + ")' data-auto='" + json.result[i].id_auto + "' data-reparacion='" + json.result[i].id_reparacion + "' data-estado='" + json.result[i].estado + "'><td><div class='normal'>" + json.result[i].ot + "</div></td>";
                    html += "<td><div class='normal'>" + json.result[i].ci + "</div></td>";
                    html += "<td><div class='grande'>" + json.result[i].nombre + "</div></td>";
                    html += "<td><div class='normal'>" + json.result[i].fecha_ingreso + "</div></td>";
                    html += "<td><div class='normal'>" + json.result[i].fecha_salida + "</div></td>";
                    html += "<td><div class='normal'>" + total + "</div></td>";
                    html += "<td><div class='normal'>" + pagado + "</div></td>";
                    html += "<td><div class='normal'>" + falta + "</div></td>";
                    html += "<td><div class='grande'>" + json.result[i].mecanico + "</div></td></tr>";
                }
            $("#tablamoroso tbody").html(html);
            $("#tablamoroso").igualartabla();
        }
    });
}
function buscarMovimiento(e) {
    if (e !== "" && e.keyCode !== 13) {
        return;
    }
    var text = $("input[name=buscarMovimiento]").val();
    var de = $("input[name=fechadeMovimiento]").val();
    var hasta = $("input[name=fechahastaMovimiento]").val();
    if (!validar("texto y entero", text)) {
        $("body").msmOK("El criterio de busqueda no puede tener caracteres especiales.");
        return;
    }
    var tipo = $("#cuerpoMovimiento input:checked").val();
    cargando(true);
    $.post(url, {proceso: 'Movimiento', text: text, de: de, hasta: hasta, tipo: tipo}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            var html = "";
            var total = 0;
            if (tipo == "reparacion") {
                html += "<tr><th><div class='normal'>Fecha</div></th>";
                html += "<th><div class='normal'>OT</div></th>";
                html += "<th><div class='normal'>Nro. Pago</div></th>";
                html += "<th><div class='normal'>CI</div></th>";
                html += "<th><div class='grande'>Nombre del cliente</div></th>";
                html += "<th><div class='grande'>Cobrador</div></th>";
                html += "<th><div class='normal'>Monto</div></th></tr>";
                $("#tablaMovimiento thead").html(html);
                html = "";
                if (json.result !== null)
                    for (var i = 0; i < json.result.length; i++) {
                        html += "<tr><td><div class='normal'>" + json.result[i].fecha + "</div></td>";
                        html += "<td><div class='normal'>" + json.result[i].ot + "</div></td>";
                        html += "<td><div class='normal'>" + json.result[i].nro + "</div></td>";
                        html += "<td><div class='normal'>" + json.result[i].ci + "</div></td>";
                        html += "<td><div class='grande'>" + json.result[i].nombre + "</div></td>";
                        html += "<td><div class='grande'>" + json.result[i].adm + "</div></td>";
                        html += "<td><div class='normal'>" + json.result[i].monto + "</div></td></tr>";
                        total += parseFloat(json.result[i].monto);
                    }
            }
            if (tipo == "sueldo") {
                html += "<tr><th><div class='normal'>Fecha</div></th>";
                html += "<th><div class='normal'>CI</div></th>";
                html += "<th><div class='grande'>Nombre</div></th>";
                html += "<th><div class='grande'>Detalle</div></th>";
                html += "<th><div class='normal'>Sueldo de:</div></th>";
                html += "<th><div class='normal'>Monto</div></th></tr>";
                $("#tablaMovimiento thead").html(html);
                html = "";
                if (json.result !== null)
                    for (var i = 0; i < json.result.length; i++) {
                        html += "<tr><td><div class='normal'>" + json.result[i].fecha + "</div></td>";
                        html += "<td><div class='normal'>" + json.result[i].carnet + "</div></td>";
                        html += "<td><div class='grande'>" + json.result[i].nombre + "</div></td>";
                        html += "<td><div class='grande'>" + json.result[i].descripcion + "</div></td>";
                        html += "<td><div class='normal'>" + json.result[i].fechaC + "</div></td>";
                        html += "<td><div class='normal'>" + json.result[i].monto + "</div></td></tr>";
                        total += parseFloat(json.result[i].monto);
                    }
            }
            if (tipo == "otros pagos") {
                html += "<tr><th><div class='normal'>Fecha</div></th>";
                html += "<th><div class='grande'>Registrado Por:</div></th>";
                html += "<th><div class='grande2'>Descripción</div></th>";
                html += "<th><div class='normal'>Monto</div></th></tr>";
                $("#tablaMovimiento thead").html(html);
                html = "";
                if (json.result !== null)
                    for (var i = 0; i < json.result.length; i++) {
                        html += "<tr><td><div class='normal'>" + json.result[i].fecha + "</div></td>";
                        html += "<td><div class='grande'>" + json.result[i].nombre + "</div></td>";
                        html += "<td><div class='grande2'>" + json.result[i].descripcion + "</div></td>";
                        html += "<td><div class='normal'>" + json.result[i].monto + "</div></td></tr>";
                        total += parseFloat(json.result[i].monto);
                    }
            }
            $("#totalmovimiento").text(total.toFixed(2));
            $("#tablaMovimiento tbody").html(html);
            $("#tablaMovimiento").igualartabla();
        }
    });
}
function verhistorial(auto, reparacion) {
    autoId = auto;
    reparacionID = reparacion;
    cambioProceso('Historial de Reparación', 'cuerpoReparacion');
}
