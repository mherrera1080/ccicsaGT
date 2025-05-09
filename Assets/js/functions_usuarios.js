document.addEventListener("DOMContentLoaded", function () {
  // Crear DataTable

  let permisosMod = permisos[7] || { leer: 0, crear: 0, editar: 0, eliminar: 0 };

  let tableUsuarios = $("#tableUsuarios").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Usuarios/getUsuarios",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "correo_empresarial" },
      { data: "nombres" },
      { data: "role_name" },
      { data: "estado" },
      {
        data: null,
        render: function (data, type, row) {
          let botones = '';
      
          // Botón Editar
          if (permisosMod.editar == 1) {
            botones += `<button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${row.id_user}">
                            <i class="fas fa-pencil-square"></i>
                        </button>`;
          } else {
            botones += `<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fas fa-pencil-square"></i>
                        </button>`;
          }
      
          // Botón Eliminar
          if (permisosMod.eliminar == 1) {
            botones += `<button type="button" class="btn btn-danger delete-btn" data-id="${row.id_user}">
                            <i class="fas fa-trash-alt"></i>
                        </button>`;
          } else {
            botones += `<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fas fa-trash-alt"></i>
                        </button>`;
          }
      
          return botones;
        },
      }
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
      let ajaxUrl = base_url + "/Usuarios/setUsuario";
      let request = new XMLHttpRequest();

      request.open("POST", ajaxUrl, true);
      request.send(formData);

      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          let response = JSON.parse(request.responseText);

          if (response.status) {
            Swal.fire({
              title: "Éxito",
              text: response.msg,
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then(() => {
              $("#createModal").modal("hide");
              tableUsuarios.ajax.reload();
            });
          } else {
            Swal.fire({
              title: "Error",
              text: response.msg,
              icon: "error",
              confirmButtonText: "Aceptar",
            });
          }
        }
      };
    });

  $("#createModal").on("show.bs.modal", function () {
    // Limpiar campos del formulario
    cargarEmpleado("usuario_id");
    cargarRol("role_id");
    $("#createForm")[0].reset(); // Reinicia el formulario
    $("#id_user").val(""); // Asegúrate de que el campo oculto esté vacío
    $(".modal-title").text("Añadir Usuario"); // Opcional: Cambia el título del modal
  });

  function cargarEmpleado(selectElementId) {
    $.ajax({
      url: `${base_url}/Usuarios/getEmpleados`, // URL para obtener usuarios desde el controlador
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          let opciones =
            '<option selected disabled value="">Seleccione un Empleado...</option>';
          response.data.forEach(function (empleado) {
            opciones += `<option value="${empleado.id_empleado}">${empleado.apellidos} ${empleado.nombres}</option>`;
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

  function cargarRol(selectElementId) {
    $.ajax({
      url: `${base_url}/Usuarios/getRol`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          let opciones =
            '<option selected disabled value="">Seleccione un Rol...</option>';
          response.data.forEach(function (rol) {
            opciones += `<option value="${rol.id}">${rol.role_name}</option>`;
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

  // Manejar el clic en el botón de editar para mostrar los datos
  $(document).on("click", ".edit-btn", function () {
    const id_user = $(this).data("id");

    cargarRol("edit_role_id");

    $.ajax({
      url: `${base_url}/Usuarios/getUsuariosbyId/${id_user}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          $("#edit_id_user").val(response.data.id_user);
          $("#edit_id_colaborador").val(response.data.usuario_id);
          $("#edit_nombres").val(response.data.nombres);
          $("#edit_identificacion").val(response.data.identificacion);
          $("#edit_correo_empresarial").val(response.data.correo_empresarial);
          $("#edit_estado").val(response.data.estado);
          $("#edit_role_id").val(response.data.role_id);
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

      cargarRol("role_id");

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Usuarios/setUsuario";
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
              title: "Éxito",
              text: response.msg,
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then(() => {
              $("#editModal").modal("hide");
              tableUsuarios.ajax.reload();
            });
          } else {
            Swal.fire({
              title: "Error",
              text: response.msg,
              icon: "error",
              confirmButtonText: "Aceptar",
            });
          }
        }
      };
    });

  // Manejar el clic en el botón de eliminar
  $(document).on("click", ".delete-btn", function () {
    const idEmpresa = $(this).data("id");

    Swal.fire({
      title: "¿Estás seguro?",
      text: "Esta acción no se puede deshacer.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: `${base_url}/Usuarios/deleteUsuario/${idEmpresa}`,
          method: "POST",
          success: function (response) {
            if (response.status) {
              Swal.fire({
                title: "Eliminado",
                text: response.msg,
                icon: "success",
                confirmButtonText: "Aceptar",
              }).then(() => {
                tableUsuarios.ajax.reload(); // Recargar tabla
              });
            } else {
              Swal.fire({
                title: "Error",
                text: "Error al eliminar Usuario: " + response.msg,
                icon: "error",
                confirmButtonText: "Aceptar",
              });
            }
          },
          error: function () {
            Swal.fire({
              title: "Error",
              text: "Error al eliminar la Usuario.",
              icon: "error",
              confirmButtonText: "Aceptar",
            });
          },
        });
      }
    });
  });
});
