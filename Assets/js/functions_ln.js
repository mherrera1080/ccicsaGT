document.addEventListener("DOMContentLoaded", function () {
  // Crear DataTable
  let permisosMod = permisos[13] || {
    leer: 0,
    crear: 0,
    editar: 0,
    eliminar: 0,
  };
  let tableLN = $("#tableLN").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Listado/getLN",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "codigo" },
      { data: "descripcion" },
      { data: "descripcion_dimension" },
      {
        data: null,
        render: function (data, type, row) {
          let botones = "";
          if (permisosMod.editar == 1) {
            botones += `
                        <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${row.id_ln}">
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
                        <button type="button" class="btn btn-danger delete-btn" data-id="${row.id_ln}">
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

  function cargarDimension(selectElementId) {
    $.ajax({
      url: `${base_url}/Listado/getDimensionLN`, // URL para obtener usuarios desde el controlador
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          let opciones =
            '<option selected disabled value="">Seleccione una Dimension...</option>';
          response.data.forEach(function (dimension) {
            opciones += `<option value="${dimension.id_dimension}">${dimension.numero} ${dimension.descripcion}</option>`;
          });
          $(`#${selectElementId}`).html(opciones);
        } else {
          alert(response.msg);
        }
      },
      error: function () {
        alert("Error al cargar los Dimensiones.");
      },
    });
  }

  // Manejar el envío del formulario de crear empresa
  document
    .querySelector("#createForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Listado/setLN";
      let request = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");

      request.open("POST", ajaxUrl, true);
      request.send(formData);

      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          let response = JSON.parse(request.responseText);
          if (response.status) {
            Swal.fire({
              title: "Datos guardados correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                tableLN.ajax.reload();
              }
            });
            $("#createModal").modal("hide");
          } else {
            alert(response.msg);
          }
        }
      };
    });

  $("#createModal").on("show.bs.modal", function () {
    // Limpiar campos del formulario
    cargarDimension("dimension");
    $("#createForm")[0].reset(); // Reinicia el formulario
    $("#id_ln").val(""); // Asegúrate de que el campo oculto esté vacío
    $(".modal-title").text("Añadir LN"); // Opcional: Cambia el título del modal
  });

  $("#editModal").on("show.bs.modal", function () {
    // Limpiar campos del formulario
    cargarDimension("edit_dimension");
  });

  // Manejar el clic en el botón de editar para mostrar los datos
  $(document).on("click", ".edit-btn", function () {
    const idEmpresa = $(this).data("id");

    $.ajax({
      url: `${base_url}/Listado/getLNbyID/${idEmpresa}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#edit_id_ln").val(response.data.id_ln);
          $("#edit_codigo").val(response.data.codigo);
          $("#edit_descripcion").val(response.data.descripcion);
          $("#edit_dimension").val(response.data.dimension);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  // Manejar el envío del formulario de editar empresa
  document
    .querySelector("#editForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Listado/setLN";
      let request = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");

      request.open("POST", ajaxUrl, true);
      request.send(formData);

      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          let response = JSON.parse(request.responseText);
          if (response.status) {
            Swal.fire({
              title: "Datos guardados correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                tableLN.ajax.reload();
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
      text: "Esta acción eliminará el Departamento permanentemente.",
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
          url: `${base_url}/Listado/deleteLN/${idEmpresa}`,
          method: "POST",
        });

        const data = JSON.parse(response);

        if (data.status) {
          Swal.fire({
            title: "LN eliminada",
            text: "La linea de negocio ha sido eliminada correctamente.",
            icon: "success",
            confirmButtonText: "Aceptar",
          }).then(() => {
            tableLN.ajax.reload(); // Recarga solo la tabla
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
