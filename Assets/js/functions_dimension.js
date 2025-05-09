document.addEventListener("DOMContentLoaded", function () {
  // Crear DataTable
  let permisosMod = permisos[13] || {
    leer: 0,
    crear: 0,
    editar: 0,
    eliminar: 0,
  };
  let tableDimensiones = $("#tableDimensiones").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Listado/getDimensiones",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1; // Índice de fila
        },
      },
      { data: "numero" },
      { data: "descripcion" },
      { data: "naturaleza" },
      { data: "estado" },
      {
        data: null,
        render: function (data, type, row) {
          let botones = "";
          if (permisosMod.editar == 1) {
            botones += `
              <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${row.id_dimension}">
                  <i class="fas fa-pencil-square"></i>
              </button>`;
          } else {
            botones += `
          <button button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fas fa-pencil-square"></i>
          </button>`;
          }
          if (permisosMod.eliminar == 1) {
            botones += `
             <button type="button" class="btn btn-danger delete-btn" data-id="${row.id_dimension}">
                  <i class="fas fa-trash-alt"></i>
              </button>`;
          } else {
            botones += `
          <button button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fas fa-trash-alt"></i>
          </button>`;
          }
          return botones;
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

  // Manejo del formulario de creación
  $("#createForm").on("submit", function (event) {
    event.preventDefault();
    let formData = new FormData(this);
    let ajaxUrl = base_url + "/Listado/setDimension";
    let request = new XMLHttpRequest();

    request.open("POST", ajaxUrl, true);
    request.send(formData);

    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        let response;
        try {
          response = JSON.parse(request.responseText);
        } catch (e) {
          console.error("Error al parsear JSON: ", e);
          Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          return;
        }
        if (response.status) {
          Swal.fire({
            title: "Datos guardados correctamente",
            icon: "success",
            confirmButtonText: "Aceptar",
          }).then((result) => {
            if (result.isConfirmed) {
              // Recargar la página al presionar "Aceptar"
              tableDimensiones.ajax.reload();
            }
          });
          $("#createModal").modal("hide");
        } else {
          Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
        }
      }
    };
  });

  // Limpiar el modal de creación cuando se abre
  $("#createModal").on("show.bs.modal", function () {
    $("#createForm")[0].reset();
    $(".modal-title").text("Añadir Dimension");
  });

  // Manejo de edición
  $(document).on("click", ".edit-btn", function () {
    const idDimension = $(this).data("id");

    $.ajax({
      url: `${base_url}/Listado/getDimensionbyID/${idDimension}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#edit_id_dimension").val(response.data.id_dimension);
          $("#edit_numero").val(response.data.numero);
          $("#edit_descripcion").val(response.data.descripcion);
          $("#edit_naturaleza").val(response.data.naturaleza);
          $("#edit_estado").val(response.data.estado);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  // Manejo del formulario de edición
  $("#editForm").on("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(this);
    let ajaxUrl = base_url + "/Listado/setDimension";
    let request = new XMLHttpRequest();
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        let response;
        try {
          response = JSON.parse(request.responseText);
        } catch (e) {
          console.error("Error al parsear JSON: ", e);
          alert("Error en la respuesta del servidor.");
          return;
        }

        if (response.status) {
          Swal.fire({
            title: "Datos guardados correctamente",
            icon: "success",
            confirmButtonText: "Aceptar",
          }).then((result) => {
            if (result.isConfirmed) {
              // Recargar la página al presionar "Aceptar"
              tableDimensiones.ajax.reload();
            }
          });
          $("#editModal").modal("hide");
        } else {
          Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
        }
      }
    };
  });

  $(document).on("click", ".delete-btn", async function () {
    const idEmpresa = $(this).data("id");

    const result = await Swal.fire({
      title: "¿Estás seguro?",
      text: "Esta acción eliminará la Dimension permanentemente.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    });

    if (result.isConfirmed) {
      try {
        const response = await $.ajax({
          url: `${base_url}/Listado/deleteDimension/${idEmpresa}`,
          method: "POST",
        });

        const data = JSON.parse(response);

        if (data.status) {
          Swal.fire({
            title: "Dimension Eliminada",
            text: "La Dimension ha sido eliminado correctamente.",
            icon: "success",
            confirmButtonText: "Aceptar",
          }).then(() => {
            tableDimensiones.ajax.reload(); // Recarga solo la tabla
          });
        } else {
          Swal.fire("Atención", data.msg, "error");
        }
      } catch (error) {
        Swal.fire("Error", "Hubo un problema al eliminar el puesto.", "error");
      }
    }
  });
});
