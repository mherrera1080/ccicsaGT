document.addEventListener("DOMContentLoaded", function () {
  // Crear DataTable
  let tableEmpresa = $("#TableEmpresa").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Listado/getEmpresa",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "nombre" },
      { data: "descripcion" },
      { data: "nit" },
      { data: "estado" },
      {
        data: null,
        render: function (data, type, row) {
          return `
            <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${row.id_empresa}">
                <i class="fas fa-pencil-square"></i>
            </button>
            <button type="button" class="btn btn-danger delete-btn" data-id="${row.id_empresa}">
                <i class="fas fa-trash-alt"></i>
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

  // Manejar el envío del formulario de crear empresa
  document
    .querySelector("#createForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Listado/setEmpresa";
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
                location.reload();
              }
            });
            $("#createModal").modal("hide");
            tableEmpresa.ajax.reload();
          } else {
            Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          }
        }
      };
    });

  $("#createModal").on("show.bs.modal", function () {
    // Limpiar campos del formulario
    $("#createForm")[0].reset(); // Reinicia el formulario
    $("#id_empresa").val(""); // Asegúrate de que el campo oculto esté vacío
    $(".modal-title").text("Añadir Empresa"); // Opcional: Cambia el título del modal
  });

  // Manejar el clic en el botón de editar para mostrar los datos
  $(document).on("click", ".edit-btn", function () {
    const idEmpresa = $(this).data("id");

    $.ajax({
      url: `${base_url}/Listado/getEmpresabyID/${idEmpresa}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#edit_id_empresa").val(response.data.id_empresa);
          $("#edit_nombre").val(response.data.nombre);
          $("#edit_descripcion").val(response.data.descripcion);
          $("#edit_nit").val(response.data.nit);
          $("#edit_estado").val(response.data.estado);
        } else {
           Swal.fire({
              title: "Datos guardados correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
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
      let ajaxUrl = base_url + "/Listado/setEmpresa";
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
                location.reload();
              }
            });
            $("#editModal").modal("hide");
            tableEmpresa.ajax.reload();
          } else {
             Swal.fire({
              title: "Datos guardados correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
          }
        }
      };
    });

  // Manejar el clic en el botón de eliminar
  $(document).on("click", ".delete-btn", function () {
    const idEmpresa = $(this).data("id");
    if (confirm("¿Estás seguro de que deseas eliminar esta empresa?")) {
      $.ajax({
        url: `${base_url}/Listado/deleteEmpresa/${idEmpresa}`,
        method: "POST",
        success: function (response) {
          if (response.status) {
            Swal.fire({
              title: "Datos guardados correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                tableEmpresa.ajax.reload(); // Recarga solo la tabla
              }
            });
          } else {
            Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
          }
        },
        error: function () {
          alert("Error al eliminar la empresa.");
        },
      });
    }
  });
});
