document.addEventListener("DOMContentLoaded", function () {
  // Crear DataTable
  let tableAprobaciones = $("#tableAprobaciones").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Aprobacion/getAprobaciones",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "fecha_ingreso" },
      { data: "apellidos" },
      { data: "nombres" },
      { data: "identificacion" },
      { data: "puesto_contrato" },
      { data: "puesto_operativo" },
      { data: "departamento" },
      { data: "area_laboral" },
      { data: "aprobacion" },
      {
        data: null,
        render: function (data, type, row) {
          return `
                <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${row.id_aprobaciones}">
                    <i class="fas fa-pencil-square"></i>
                </button>
              `;
        },
      },
    ],
    dom: "Bfrtip",
    buttons: [
      {
        extend: "copyHtml5",
        text: "<i class='far fa-copy'></i> Copiar",
        titleAttr: "Copiar",
        className: "btn btn-dark",
      },
      {
        extend: "excelHtml5",
        text: "<i class='fas fa-file-excel'></i> Excel",
        titleAttr: "Exportar a Excel",
        className: "btn btn-success",
      },
      {
        extend: "pdfHtml5",
        text: "<i class='fas fa-file-pdf'></i> PDF",
        titleAttr: "Exportar a PDF",
        className: "btn btn-danger",
      },
      "colvis",
    ],
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  });

  $(document).on("click", ".edit-btn", function () {
    const idEmpresa = $(this).data("id");

    $.ajax({
      url: `${base_url}/Aprobacion/getAprobacionesbyID/${idEmpresa}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          // Establece los valores de los campos
          $("#id_aprobaciones").val(response.data.id_aprobaciones);
          $("#fecha_ingreso").val(response.data.fecha_ingreso);
          $("#primer_apellido").val(response.data.primer_apellido);
          $("#segundo_apellido").val(response.data.segundo_apellido);
          $("#apellidos").val(response.data.apellidos);
          $("#nombres").val(response.data.nombres);
          $("#identificacion").val(response.data.identificacion);
          $("#puesto_contrato").val(response.data.nombre_puesto_contrato);
          $("#puesto_operativo").val(response.data.nombre_puesto_operativo);
          $("#departamento").val(response.data.nombre_departamento);
          $("#nombre_area").val(response.data.nombre_area);
          $("#estado").val(response.data.estado);
          $("#salario_base").val(response.data.salario_base);
          $("#bonificacion").val(response.data.bonificacion);
          $("#kpi1").val(response.data.kpi1);
          $("#kpi2").val(response.data.kpi2);
          $("#kpi_max").val(response.data.kpi_max);

          // Controla el campo `codigo_empleado`
          const codigoEmpleado = response.data.codigo_empleado;
          const codigoEmpleadoInput = $("#codigo_empleado");

          if (codigoEmpleado) {
            // Si `codigo_empleado` tiene un valor, muéstralo como solo lectura
            codigoEmpleadoInput.val(codigoEmpleado);
            codigoEmpleadoInput.prop("readonly", true);
          } else {
            // Si `codigo_empleado` está vacío, permite que sea editable
            codigoEmpleadoInput.val(""); // Opcional, limpia el campo
            codigoEmpleadoInput.prop("readonly", false);
          }
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  $("#editModal").on("show.bs.modal", function () {
    $("#editForm")[0].reset();
  });

  document
    .getElementById("editForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Determina el estado del empleado y la acción
      const empleadoEstado = document.querySelector("#estado").value;
      const action = event.submitter.value;

      // Selecciona la URL adecuada según el estado y la acción
      const getAjaxUrl = (estado, action) => {
        if (estado === "Recontratacion") {
          return action === "no_approve"
            ? base_url + "/Aprobacion/setRechazoRecontratacion"
            : base_url + "/Aprobacion/setAprobarRecontratacion";
        }
        if (estado === "Pendiente") {
          return action === "no_approve"
            ? base_url + "/Aprobacion/setRechazoEmpleado"
            : base_url + "/Aprobacion/setAprobarEmpleado";
        }
      };

      const ajaxUrl = getAjaxUrl(empleadoEstado, action);

      // Crea el objeto FormData a partir del formulario
      const formData = new FormData(this);
      const request = new XMLHttpRequest();
      request.open("POST", ajaxUrl, true);
      request.send(formData);

      // Maneja la respuesta del servidor
      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          try {
            const response = JSON.parse(request.responseText);

            if (response.status) {
              // Mensajes personalizados según la acción y el estado
              let message = "";
              if (
                empleadoEstado === "Recontratacion" &&
                action === "no_approve"
              ) {
                message = "La recontratación ha sido rechazada";
              } else if (empleadoEstado === "Recontratacion") {
                message = "La recontratación ha sido aprobada";
              } else if (
                empleadoEstado === "Pendiente" &&
                action === "no_approve"
              ) {
                message = "La aprobación pendiente ha sido rechazada";
              } else {
                message = "La aprobación pendiente ha sido aceptada";
              }

              Swal.fire({
                title: "Operación exitosa",
                text: message,
                icon: "success",
                confirmButtonText: "Aceptar",
              }).then((result) => {
                if (result.isConfirmed) {
                  // Recargar la página al presionar "Aceptar"
                  location.reload();
                }
              });
              // Oculta el modal y recarga la tabla
              $("#editModal").modal("hide");
              tableAprobaciones.ajax.reload();
            } else {
              Swal.fire("Error", response.msg || "Ocurrió un error", "error");
            }
          } catch (error) {
            Swal.fire("Error", "Respuesta no válida del servidor", "error");
          }
        }
      };

      // Maneja errores de red
      request.onerror = function () {
        Swal.fire(
          "Error",
          "No se pudo establecer conexión con el servidor",
          "error"
        );
      };
    });


    // finaaal
});
