
function cargarComboEspecialidad(p_nombreCombo, p_tipo){
    $.post
    (
	"../controlador/especialidad.cargar.combo.controlador.php"
    ).done(function(resultado){
	var datosJSON = resultado;
	
        if (datosJSON.estado===200){
            var html = "";
            if (p_tipo==="seleccione"){
                html += '<option value="">Seleccione una especialidad</option>';
            }else{
                html += '<option value="0">Todas las especialidades</option>';
            }

            
            $.each(datosJSON.datos, function(i,item) {
                html += '<option value="'+item.cod_especialidad+'">'+item.descripcion+'</option>';
            });
            
            $(p_nombreCombo).html(html);
	}else{
            swal("Mensaje del sistema", resultado , "warning");
        }
    }).fail(function(error){
	var datosJSON = $.parseJSON( error.responseText );
	swal("Error", datosJSON.mensaje , "error");
    });
}

function cargarComboMedico(p_nombreCombo, p_tipo,p_codigoEspecialidad){
    
    $.post
    (
	"../controlador/medico.cargar.combo.controlador.php",
        {
         p_codigoEspecialidad:p_codigoEspecialidad   
        }
    ).done(function(resultado){
	var datosJSON = resultado;
	
        if (datosJSON.estado===200){
            var html = "";
            if (p_tipo==="seleccione"){
                html += '<option value="">Seleccione un medico</option>';
            }else{
                html += '<option value="0">Todos los medicos</option>';
            }

            
            $.each(datosJSON.datos, function(i,item) {
                html += '<option value="'+item.cod_medico+'">'+item.nombre+'</option>';
            });
            
            $(p_nombreCombo).html(html);
	}else{
            swal("Mensaje del sistema", resultado , "warning");
        }
    }).fail(function(error){
	var datosJSON = $.parseJSON( error.responseText );
	swal("Error", datosJSON.mensaje , "error");
    });
}

function cargarComboPaciente(p_nombreCombo, p_tipo){
    $.post
    (
	"../controlador/paciente.cargar.combo.controlador.php"
    ).done(function(resultado){
	var datosJSON = resultado;
	
        if (datosJSON.estado===200){
            var html = "";
            if (p_tipo==="seleccione"){
                html += '<option value="">Seleccione un paciente</option>';
            }else{
                html += '<option value="0">Tod0s l0s pacientes</option>';
            }

            
            $.each(datosJSON.datos, function(i,item) {
                html += '<option value="'+item.codigo+'">'+item.paciente+'</option>';
            });
            
            $(p_nombreCombo).html(html);
	}else{
            swal("Mensaje del sistema", resultado , "warning");
        }
    }).fail(function(error){
	var datosJSON = $.parseJSON( error.responseText );
	swal("Error", datosJSON.mensaje , "error");
    });
}