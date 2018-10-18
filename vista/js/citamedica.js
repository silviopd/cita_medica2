$(document).ready(function () {
//    cargarComboLinea("#cbolinea", "todos");
    cargarComboEspecialidad("#cboespecialidadmodal", "seleccione");
//    cargarComboMarca("#cbomarca", "todos");
    cargarComboPaciente("#cbopacientemodal", "seleccione");
    listar();
});

$("#cboespecialidadmodal").change(function () {
    var codigoMedico = $("#cboespecialidadmodal").val();
    cargarComboMedico("#cbomedicomodal", "todos", codigoMedico);
});

function listar() {

    $.post
            (
                    "../controlador/citamedica.listar.controlador.php",
                    {
                    }
            ).done(function (resultado) {
        var datosJSON = resultado;

        if (datosJSON.estado === 200) {
            var html = "";

            html += '<small>';
            html += '<table id="tabla-listado" class="table table-bordered table-striped">';
            html += '<thead>';
            html += '<tr style="background-color: #ededed; height:25px;">';
            html += '<th>CODIGO</th>';
            html += '<th>FECHA</th>';
            html += '<th>ESTADO</th>';
            html += '<th>PACIENTE</th>';
            html += '<th>MEDICO</th>';
            html += '<th>ESPECIALIDAD</th>';
            html += '<th>TURNO</th>';
            html += '<th>USUARIO</th>';
            html += '<th style="text-align: center">OPCIONES</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            //Detalle
            $.each(datosJSON.datos, function (i, item) {
                html += '<tr>';
                html += '<td align="center">' + item.codigo + '</td>';
                html += '<td>' + item.fecha_cita + '</td>';
                html += '<td>' + item.estado + '</td>';
                html += '<td>' + item.paciente + '</td>';
                html += '<td>' + item.medico + '</td>';
                html += '<td>' + item.especialidad + '</td>';
                html += '<td>' + item.turno + '</td>';
                html += '<td>' + item.usuario + '</td>';
                html += '<td align="center">';
                html += '<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal" onclick="leerDatos(' + item.codigo + ')"><i class="fa fa-pencil"></i></button>';
                html += '&nbsp;&nbsp;';
                html += '<button type="button" class="btn btn-danger btn-xs" onclick="eliminar(' + item.codigo + ')"><i class="fa fa-close"></i></button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';
            html += '</small>';

            $("#listado").html(html);

            $('#tabla-listado').dataTable({
                "aaSorting": [[1, "asc"]]
            });



        } else {
            swal("Mensaje del sistema", resultado, "warning");
        }

    }).fail(function (error) {
        var datosJSON = $.parseJSON(error.responseText);
        swal("Error", datosJSON.mensaje, "error");
    });

}


function eliminar(codigoCitaMedica) {
    swal({
        title: "Confirme",
        text: "¿Esta seguro de eliminar el registro seleccionado?",
        showCancelButton: true,
        confirmButtonColor: '#d93f1f',
        confirmButtonText: 'Si',
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: true,
        imageUrl: "../imagenes/eliminar.png"
    },
            function (isConfirm) {
                if (isConfirm) {
                    $.post(
                            "../controlador/citamedica.eliminar.controlador.php",
                            {
                                codigoCitaMedica: codigoCitaMedica
                            }
                    ).done(function (resultado) {
                        var datosJSON = resultado;
                        if (datosJSON.estado === 200) { //ok
                            listar();
                            swal("Exito", datosJSON.mensaje, "success");
                        }

                    }).fail(function (error) {
                        var datosJSON = $.parseJSON(error.responseText);
                        swal("Error", datosJSON.mensaje, "error");
                    });

                }
            });

}


$("#frmgrabar").submit(function (evento) {
    evento.preventDefault();

    swal({
        title: "Confirme",
        text: "¿Esta seguro de grabar los datos ingresados?",
        showCancelButton: true,
        confirmButtonColor: '#3d9205',
        confirmButtonText: 'Si',
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: true,
        imageUrl: "../imagenes/pregunta.png"
    },
            function (isConfirm) {

                if (isConfirm) { //el usuario hizo clic en el boton SI     

                    //procedo a grabar

                    $.post(
                            "../controlador/citamedica.agregar.editar.controlador.php",
                            {
                                p_datosFormulario: $("#frmgrabar").serialize()
                            }
                    ).done(function (resultado) {
                        var datosJSON = resultado;

                        if (datosJSON.estado === 200) {
                            swal("Exito", datosJSON.mensaje, "success");

                            $("#btncerrar").click();
                            listar();
                        } else {
                            swal("Mensaje del sistema", resultado, "warning");
                        }

                    }).fail(function (error) {
                        var datosJSON = $.parseJSON(error.responseText);
                        swal("Error", datosJSON.mensaje, "error");
                    });

                }
            });
});


$("#btnagregar").click(function () {
    $("#txttipooperacion").val("agregar");

    $("#txtcodigo").val("");
    $("#txtfecha").val("");
    $("#cboturnomodal").val("");
    $("#cbopacientemodal").val("");
    $("#cboespecialidadmodal").val("");
    $("#cbomedicomodal").empty();

    $("#titulomodal").text("Agregar Nuevo Medico");
});


$("#myModal").on("shown.bs.modal", function () {
    $("#txtfecha").focus();
});


function leerDatos( codigoCitaMedica ){
    
    $.post
        (
            "../controlador/citamedica.leer.datos.controlador.php",
            {
                p_codigoCitaMedica: codigoCitaMedica
            }
        ).done(function(resultado){
            var datosJSON = resultado;
            if (datosJSON.estado === 200){
                
                $.each(datosJSON.datos, function(i,item) {
                    $("#txtcodigo").val( item.nro_cita_medica );
                    $("#txtfecha").val( item.fecha_cita );                    
                    $("#cboturnomodal").val( item.turno );
                    $("#cbopacientemodal").val( item.nro_historia );
                    
                    $("#cboespecialidadmodal").val( item.cod_especialidad );
                    $("#cbomedicomodal").val( item.cod_medico );
                    
                    $("#cboespecialidadmodal").change();
                    
                    $("#myModal").on("shown.bs.modal", function(){
                        $("#cbomedicomodal").val( item.cod_medico );
                    });
                    
                });
                
            }else{
                swal("Mensaje del sistema", resultado , "warning");
            }
        })
    
}