var url='Controlador/Login_Controller.php';
function entrar(e){
    if(e!=="" && e.keyCode!==13){
        return;
    }
    var cuenta=$("input[name=cuentaLogeo]").val().trim().toLowerCase();
    var contra=$("input[name=contrasenaLogeo]").val().trim().toLowerCase();
    var errar="";
    if(cuenta.length===0){
        errar+="<p>-La cuenta se encuentra vacía</p>";
    }
    if(contra.length===0){
        errar+="<p>-La contraseña se encuentra vacía</p>";
    }
    if(errar.length>0){
        $("body").msmOK(errar);
        return;
    }
    $.post(url, {cuenta:cuenta, contra:contra}, function (response) {
        json=$.parseJSON(response);
        if(json.error.length>0){
            $("body").msmOK(json.error);
        }else{
            $(location).attr('href',"Portal.php");
        }

    });
}