var padreSession = window.parent.$("#cerrarSession");
var contenedorSeleccionado = "";
var url = "../Controlador/Cliente_Controller.php";
var estadoCambioProseso = false;
$(document).ready(function () {
    $(".fecha").datepicker();
    $(".fecha").val(fechaActual());
    cambioProceso("Listado de Clientes", "cuerpoListadoCliente")
});
function cambioProceso(titulo, etiqueta) {
    if (contenedorSeleccionado === etiqueta || estadoCambioProseso)
        return;
    var f = new Date();
    var mes = f.getMonth();
    var ano = f.getFullYear();
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
            if ("cuerpoNuevoCliente" === etiqueta) {
                if (idCliente === 0) {
                    $("#contentVehiculo").ocultar();
                    $("#registroCliente").text("REGISTAR");
                } else {
                    $("#contentVehiculo").visible();
                    $("#registroCliente").text("ACTUALIZAR");
                    datosCliente()
                }
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
                if ("cuerpoNuevoCliente" === etiqueta) {
                    if (idCliente === 0) {
                        $("#contentVehiculo").ocultar();
                        $("#registroCliente").text("REGISTAR");
                    } else {
                        $("#contentVehiculo").visible();
                        $("#registroCliente").text("ACTUALIZAR");
                        datosCliente();
                    }
                }
            });
        });
    }
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
            $("input[name=ci]").val(json.result.Ci);
            $("input[name=nombre]").val(json.result.nombre);
            $("input[name=direccion]").val(json.result.direccion);
            $("input[name=telefonoCasa]").val(json.result.Telefono_Casa);
            $("input[name=telefonoOficina]").val(json.result.Telefono_Oficina);
            $("input[name=telefonoCelular]").val(json.result.Telefono_Celular);
            $("#fotoPerfil img").attr("src",json.result.foto);
        }
    });
}
function buscarClientes(e) {
    if (e !== "" && e.keyCode !== 13) {
        return;
    }
    var text = $("input[name=buscadorCliente]").val();
    if (!validar("texto y entero", text)) {
        $("body").msmOK("El criterio de busqueda no puede tener caracteres especiales.")
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
var vehiculoOption = "";
var marcaOption = "";
var idCliente = 0;
function masVehiculo() {
    var item = "<div class='itemVehiculo'>";
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
    item += "<input type='text' class='normal2' name='{nombre}'/>";
    item += "</div>";
    item += "<div class='contenedor50'>";
    item += "<span class='negrillaenter'>Color</span>";
    item += "<input type='text' class='normal2' name='{nombre}'/>";
    item += "</div>";
    item += "<div class='contenedor50'>";
    item += "<span class='negrillaenter'>Nro Chasis</span>";
    item += "<input type='text' class='normal2' name='{nombre}'/>";
    item += "</div>";
    item += "<div class='contenedor50'>";
    item += "<span class='negrillaenter'>Placa</span>";
    item += "<input type='text' class='normal2' name='{nombre}'/>";
    item += "</div>";
    item += "<span class='negrillaenter'>Observacion</span>";
    item += "<input type='text' class='grande2' name='{nombre}'/>";
    item += "</div>";
    $("#contenedorVehiculo").append(item);
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
    if (text.length > 0) {
        $("body").msmOK(text);
        return;
    }
    cargando(true);
    $.post(url, {proceso: 'registroCliente', ci: ci, nombre: nombre, direccion: direccion
        , casa: casa, oficina: oficina, celular: celular, foto: foto, cliente: idCliente}, function (response) {
        cargando(false);
        var json = $.parseJSON(response);
        if (json.error.length > 0) {
            if ("Error Session" === json.error) {
                padreSession.click();
            }
            $("body").msmOK(json.error);
        } else {
            idCliente = json.result;
            $("#contentVehiculo").visible();
            $("#registroCliente").text("ACTUALIZAR");
            $("h1").text("Cliente");
        }
    });
}