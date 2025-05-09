let TableUniformes;
let divLoading = document.querySelector("#divLoading");

document.addEventListener("DOMContentLoaded", function () {
  let id_empleado = document.querySelector("#id_empleado").value;
  let permisosMod = permisos[4] || { leer: 0, crear: 0, editar: 0, eliminar: 0 };

  // Inicializar DataTable
  TableUniformes = $("#TableUniformes").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    language: {
      url: base_url + "/Assets/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Uniformes/getUniformes/" + id_empleado,
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },
      { data: "Camisas" },
      { data: "Pantalones" },
      { data: "Botas" },
      { data: "fecha_asignacion" },
      {
        title: "Reporte",
        data: "grupo_asignacion",
        render: function (data) {
          return `
            <a href="${base_url}/Uniformes/generarPDFuniformes/${data}" class="btn btn-danger btn-sm" title="Ver PDF" target="_blank">
              <i class="far fa-file-pdf"></i>
            </a>`;
        },
      },
      {
        title: "Subir",
        data: null,
        render: function (data, type, row) {
          if (permisosMod.editar == 1) {
            return `
                    <button type="button" class="btn btn-primary subir-btn" data-bs-toggle="modal" data-bs-target="#generarPDF" data-id="${row.grupo_asignacion}">
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

  $(document).on("click", ".subir-btn", function () {
    const grupo_asignacion = $(this).data("id");

    $.ajax({
      url: `${base_url}/Uniformes/getUniformesbyId/${grupo_asignacion}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_asignacion_up").val(data.id_asignacion);
          $("#id_empleado_up").val(data.id_empleado);
          $("#grupo_asignacion").val(data.grupo_asignacion);
          $("#ubicacion").val(data.ubicacion);
          $("#nombres").val(data.nombres);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  // Mostrar modal para añadir uniformes
  $("#addUniformeModal").on("show.bs.modal", function () {
    // Limpiar la tabla dentro del modal
    $("#modalTableUniformes tbody").html(` 
        <tr>
            <td>
                <select class="form-select" id="modal_uniforme" name="uniforme[]" required>
                    <option selected disabled value="">Seleccione una Prenda...</option>
                </select>
            </td>
            <td><input type="number" class="form-control" id="modal_cantidad" name="cantidad[]" placeholder="cantidad" required></td>
            <td class="eliminar"> <input type="button" value="Menos -" class="btn btn-danger"></td>
        </tr>
    `);

    cargarUniformes("modal_uniforme"); // Asegúrate de que esta función se llame
  });

  // Función para cargar las prendas de uniforme en los selects
  function cargarUniformes(selectElementId) {
    console.log("Cargando uniformes para el select: ", selectElementId); // Verifica que se llama a la función
    $.ajax({
      url: `${base_url}/Uniformes/getUniformeGeneral`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          let opciones =
            '<option selected disabled value="">Seleccione una Prenda...</option>';
          response.data.forEach(function (uniforme) {
            opciones += `<option value="${uniforme.id_uniforme}">${uniforme.nombre_prenda}</option>`;
          });
          $(`#${selectElementId}`).html(opciones);
        } else {
          alert(response.msg);
        }
      },
      error: function () {
        alert("Error al cargar los Uniformes.");
      },
    });
  }

  // Insertar nuevas filas dinámicamente
  $("input[name='insertar']").on("click", function (e) {
    e.preventDefault();
    let newRowIndex = $("#modalTableUniformes tbody tr").length; // Obtiene la cantidad de filas
    let newRow = `
    <tr>
        <td>
            <select class="form-select" id="modal_uniforme_${newRowIndex}" name="uniforme[]" required>
                <option selected disabled value="">Seleccione una Prenda...</option>
            </select>
        </td>
        <td><input type="number" class="form-control" name="cantidad[]" placeholder="cantidad" required></td>
        <td class="eliminar"> <input type="button" value="Menos -" class="btn btn-danger"></td>
    </tr>
    `;
    $("#modalTableUniformes tbody").append(newRow);

    // Cargar opciones en el nuevo select
    cargarUniformes(`modal_uniforme_${newRowIndex}`); // Llama a la función con el nuevo ID

    // Eliminar fila del modal
    $(".eliminar input").on("click", function () {
      $(this).closest("tr").remove();
    });
  });

  // Delegar el evento de eliminación
  $("#modalTableUniformes").on("click", ".eliminar input", function () {
    $(this).closest("tr").remove();
  });

  // Enviar el formulario
  $("#addUserForm").on("submit", function (e) {
    e.preventDefault();
    let formData = new FormData(this);
    const spinner = document.querySelector("#upUniformes ");
    const submitButton = document.querySelector("#spinerUniformes");

    submitButton.disabled = true;
    spinner.classList.remove("d-none");

    fetch(base_url + "/Uniformes/saveUniformes", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          Swal.fire({
            title: "Datos guardados correctamente",
            icon: "success",
            confirmButtonText: "Aceptar",
          }).then((result) => {
            if (result.isConfirmed) {
              // Recargar la página al presionar "Aceptar"
              TableUniformes.ajax.reload();
            }
          });
          $("#addUniformeModal").modal("hide");
        } else {
          Swal.fire({
            title: "Advertencia",
            text: data.message,
            icon: data.type === "warning" ? "warning" : "error", // Dependiendo del tipo
            confirmButtonText: "Entendido",
          }).then((result) => {
            if (result.isConfirmed) {
              // Recargar la página al presionar "Aceptar"
              submitButton.disabled = false;
              spinner.classList.add("d-none");
            }
          });
        }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
      });
  });

  $("#pdfForm").on("submit", function (e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch(base_url + "/Uniformes/subirDocumento", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          Swal.fire({
            title: "Datos guardados correctamente",
            icon: "success",
            confirmButtonText: "Aceptar",
          }).then((result) => {
            if (result.isConfirmed) {
              // Recargar la página al presionar "Aceptar"
              TableUniformes.ajax.reload();
            }
          });
          $("#generarPDF").modal("hide");
        } else {
          alert(data.msg);
        }
      })
      .catch((error) => {
        console.error("Error en la solicitud:", error);
      });
  });
});
