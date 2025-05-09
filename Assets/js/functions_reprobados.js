document.addEventListener("DOMContentLoaded", function () {
  // Crear DataTable
  let tableRechazos = $("#tableRechazos").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Aprobacion/getRechazos",
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
      { data: "nombre_area" },
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

  // Manejar el clic en el botón de editar para mostrar los datos
  $(document).on("click", ".edit-btn", function () {
    const idEmpresa = $(this).data("id");

    $.ajax({
      url: `${base_url}/Aprobacion/getAprobacionesbyID/${idEmpresa}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#id_aprobaciones").val(response.data.id_aprobaciones);
          $("#fecha_ingreso").val(response.data.fecha_ingreso);
          $("#primer_apellido").val(response.data.primer_apellido);
          $("#segundo_apellido").val(response.data.segundo_apellido);
          $("#nombres").val(response.data.nombres);
          $("#identificacion").val(response.data.identificacion);
          $("#puesto_contrato").val(response.data.puesto_contrato);
          $("#puesto_operativo").val(response.data.puesto_operativo);
          $("#departamento").val(response.data.departamento);
          $("#area").val(response.data.area_laboral);
          $("#descripcion").val(response.data.descripcion);
          $("#salario_base").val(response.data.salario_base);
          $("#bonificacion").val(response.data.bonificacion);
          $("#kpi1").val(response.data.kpi1);
          $("#kpi2").val(response.data.kpi2);
          $("#kpi_max").val(response.data.kpi_max);
          
        } else {
          Swal.fire({
            title: "Error",
            text: response.msg,
            icon: "error",
            confirmButtonText: "Aceptar",
          });
        }
      },
      error: function (error) {
        Swal.fire({
          title: "Error",
          text: "Error al cargar los datos de la aprobación.",
          icon: "error",
          confirmButtonText: "Aceptar",
        });
        console.log("Error:", error);
      },
    });
  });

  if (document.querySelector("#area")) {
    let ajaxUrl = base_url + "/Aprobacion/getAreas_laborales"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#area").innerHTML =
          request.responseText;
        $("#area").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#puesto_contrato")) {
    let ajaxUrl = base_url + "/Personal/getSelectPuestos"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#puesto_contrato").innerHTML =
          request.responseText;
        $("#puesto_contrato").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#puesto_operativo")) {
    let ajaxUrl = base_url + "/Personal/getSelectPuestos"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#puesto_operativo").innerHTML =
          request.responseText;
        $("#puesto_operativo").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#lider_proceso")) {
    let ajaxUrl = base_url + "/Personal/getSelectJefesLideres"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#lider_proceso").innerHTML =
          request.responseText;
        $("#lider_proceso").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#jefe_inmediato")) {
    let ajaxUrl = base_url + "/Personal/getSelectJefesLideres"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#jefe_inmediato").innerHTML =
          request.responseText;
        $("#jefe_inmediato").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#departamento")) {
    let ajaxUrl = base_url + "/Personal/getSelectDepartamento"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#departamento").innerHTML =
          request.responseText;
        $("#departamento").selectpicker("refresh");
      }
    };
  }

  // Manejar el envío del formulario
  document
    .getElementById("editForm")
    .addEventListener("submit", function (event) {
      event.preventDefault(); // Evita el envío del formulario por defecto

      // Obtiene el valor del botón que fue presionado
      let action = event.submitter.value;

      // Determina la URL de destino según la acción
      let ajaxUrl =
        action === "no_approve"
          ? base_url + "/Aprobacion/setDescartar"
          : base_url + "/Aprobacion/setResolicitar";

      // Muestra advertencias según el botón presionado
      if (action === "no_approve") {
        Swal.fire({
          title: "Advertencia",
          text: "¿Estás seguro de que deseas descartar la aprobación?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Sí, descartar",
          cancelButtonText: "Cancelar",
        }).then((result) => {
          if (result.isConfirmed) {
            // Si el usuario confirma, continúa con el envío del formulario
            enviarFormulario(ajaxUrl);
          }
        });
      } else {
        Swal.fire({
          title: "Confirmación",
          text: "¿Estás seguro de que deseas enviar la solicitud nuevamente?",
          icon: "info",
          showCancelButton: true,
          confirmButtonText: "Sí, enviar",
          cancelButtonText: "Cancelar",
        }).then((result) => {
          if (result.isConfirmed) {
            // Si el usuario confirma, continúa con el envío del formulario
            enviarFormulario(ajaxUrl);
          }
        });
      }
    });

  // Función para enviar el formulario
  function enviarFormulario(ajaxUrl) {
    // Crea el objeto FormData a partir del formulario
    let formData = new FormData(document.getElementById("editForm"));
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");

    // Configura y envía la solicitud AJAX
    request.open("POST", ajaxUrl, true);
    request.send(formData);

    // Maneja la respuesta del servidor
    request.onreadystatechange = function () {
      if (request.readyState === 4) {
        if (request.status === 200) {
          let response = JSON.parse(request.responseText);
          Swal.fire({
            title: "Datos guardados correctamente",
            icon: "success",
            confirmButtonText: "Aceptar",
          }).then((result) => {
            if (result.isConfirmed) {
              // Si el usuario confirma, continúa con el envío del formulario
              location.reload();
            }
          });
        } else {
          Swal.fire({
            title: "Error",
            text: "Hubo un problema al procesar la solicitud.",
            icon: "error",
            confirmButtonText: "Aceptar",
          });
        }
      }
    };
  }

  // FIN
});
