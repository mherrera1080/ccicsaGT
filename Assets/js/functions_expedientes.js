let tableExpedientes;
let divLoading = document.querySelector("#divLoading");

document.addEventListener("DOMContentLoaded", function () {
  let empleado_id = document.querySelector("#empleado_id").value;
  let permisosMod = permisos[5] || { leer: 0, crear: 0, editar: 0, eliminar: 0 };
  // Inicializar DataTable
  tableExpedientes = $("#tableExpedientes").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    language: {
      url: base_url + "/Assets/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Expedientes/getExpedientes/" + empleado_id,
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },
      { data: "nombre" },
      {
        data: "ubicacion",
        render: function (data, type, row) {
          if (!data || data.trim() === null) {
            return `<h5><strong>No se ha subido ningún archivo</strong></h5>`;
          } else {
            return `
              <a href="${base_url}/${data}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Ver PDF
              </a>
            `;
          }
        },
      },
      {
        data: null,
        render: function (data, type, row) 
        {
          if (permisosMod.editar == 1) {
            return `
            <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${row.id}">
                <i class="fas fa-pencil-square"></i>
            </button>`;
          } else {
            return `
            <button button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
              <i class="fas fa-pencil-square"></i>
            </button>`; 
          }
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
    const idExpediente = $(this).data("id");

    $.ajax({
      url: `${base_url}/Expedientes/getExpedientesPrincipal/${idExpediente}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#edit_id").val(response.data.id);
          $("#edit_nombres").val(response.data.nombres);
          $("#edit_nombre_documento").val(response.data.nombre_documento);
          $("#edit_ubicacion").val(response.data.ubicacion);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  document
    .querySelector("#editForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Expedientes/subirDocumento";

      let request = new XMLHttpRequest();
      request.open("POST", ajaxUrl, true);

      // Manejo de error en la solicitud AJAX
      request.onerror = function () {
        Swal.fire(
          "Error",
          "No se pudo completar la solicitud. Intente de nuevo.",
          "error"
        );
      };

      request.onload = function () {
        if (request.status === 200) {
          let response;
          try {
            response = JSON.parse(request.responseText);
          } catch (e) {
            Swal.fire("Error", "Respuesta inválida del servidor.", "error");
            return;
          }

          if (response.status) {
            Swal.fire({
              title: "Éxito",
              text: response.msg || "Operación completada correctamente.",
              icon: "success",
              confirmButtonText: "OK",
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload(); // Recargar la página al presionar "Aceptar"
              }
            });
          } else {
            Swal.fire({
              title: "Advertencia",
              text: response.msg || "Ocurrió un problema.",
              icon: response.type === "warning" ? "warning" : "error",
              confirmButtonText: "Entendido",
            });
          }
        } else {
          Swal.fire("Error", "Error en la solicitud al servidor.", "error");
        }
      };

      request.send(formData);
    });
});
