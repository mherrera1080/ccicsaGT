let tableSolicitud;

document.addEventListener("DOMContentLoaded", function () {
  let id_empleado = document.querySelector("#id_empleado").value;

  // Inicializa DataTable
  tableSolicitud = $("#tableSolicitud").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url:
        base_url + "/Vacaciones_Revision/getSolicitudesbyJefe/" + id_empleado,
      error: function (xhr, error, thrown) {
        console.error("Error al cargar datos:", error, thrown);
      },
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "nombre_completo" },
      { data: "codigo_empleado" },
      { data: "asunto" },
      { data: "dias_suma" },
      { data: "fecha_solicitud" },
      { data: "fecha_aprobacion" },
      { data: "dias_retraso" },
      {
        data: "estado",
        render: function (data) {
          const estados = {
            //Administrativa
            Pendiente: "Pendiente",
            Aprobado: "Aprobado",
            "Reversion": "Aprobado",
            "Revertido": "Revertido",
            Rechazado: "Rechazado",
            Cancelado: "Cancelado",
            Reversion: "Aprobado",

          };
          return estados[data?.trim()] || "Desconocido";
        },
      },
      {
        data: "estado",
        render: function (data, type, row) {
          if (!data || data.trim() === "Pendiente") {
            return `
            <button type="button" class="btn btn-warning act-btn" data-bs-toggle="modal" data-bs-target="#updateSolicitudModal" data-id="${row.solicitud_id}">
                <i class="fas fa-pencil-square"></i>
            </button>
            `;
          } else if (!data || data.trim() === "Aprobado") {
            return `
            <button type="button" class="btn btn-success cons-btn" data-bs-toggle="modal" data-bs-target="#consumidoSolicitudModal" data-id="${row.solicitud_id}">
                <i class="fa fa-check" aria-hidden="true"></i>
            </button>
            `;
          } else if (!data || data.trim() === "Rechazado") {
            return `
            <button type="button" class="btn btn-danger reject-btn" data-bs-toggle="modal" data-bs-target="#rejectSolicitud" data-id="${row.solicitud_id}">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
            `;
          } else if (!data || data.trim() === "Cancelado") {
            return `
            <button type="button" class="btn btn-danger cancel-btn" data-bs-toggle="modal" data-bs-target="#cancelSolicitudModal" data-id="${row.solicitud_id}">
                <i class="fa-solid fa-ban"></i>
            </button>
            `;
          } else if (!data || data.trim() === "Reversion") {
            return `
            <button type="button" class="btn btn-success cons-btn" data-bs-toggle="modal" data-bs-target="#consumidoSolicitudModal" data-id="${row.solicitud_id}">
                <i class="fa fa-check" aria-hidden="true"></i>
            </button>
            `;
          } else if (!data || data.trim() === "Revertido") {
            return `
            <button type="button" class="btn btn-success cons-btn" data-bs-toggle="modal" data-bs-target="#consumidoSolicitudModal" data-id="${row.solicitud_id}">
                <i class="fa fa-check" aria-hidden="true"></i>
            </button>
            `;
          }
        },
      },
    ],
    dom: "Bfrtip",
    buttons: [
      {
        extend: "colvis",
        text: "<i class='fas fa-columns'></i> Columnas",
        titleAttr: "Mostrar/Ocultar Columnas",
        className: "btn btn-primary",
      },
    ],
    bDestroy: true,
    iDisplayLength: 25,
    order: [[0, "asc"]],
  });

  // ACTUALIZAR
  $(document).on("click", ".act-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getSolicitudbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_solicitud").val(data.id_solicitud);
          $("#nombre_completo").val(data.nombre_completo);
          $("#categoria_solicitud").val(data.id_categoria);
          $("#asunto").val(data.asunto);

          // Llenar la tabla de fechas
          const tablaFechas = $("#tablaFechasEdit tbody");
          tablaFechas.empty();
          const fechas = data.fechas.split(","); // Separar fechas
          const valores = data.valores.split(","); // Separar valores
          const dias = data.dias.split(","); // Separar días
          fechas.forEach((fecha, index) => {
            tablaFechas.append(`
              <tr>
                <td>${fecha}</td>
                <td>${valores[index]}</td>
                <td>${dias[index]}</td>
              </tr>
            `);
          });
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  $(document).on("click", ".act-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;
          // Establecer los valores de los campos en el modal
          $("#jefe_aprobador_actualizar").val(data.responsable_aprobador);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  // Rechazado
  $(document).on("click", ".reject-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getSolicitudbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_solicitud").val(data.id_solicitud);
          $("#nombre_completo_reject").val(data.nombre_completo);
          $("#asunto_reject").val(data.asunto);
          $("#solicitud_reject").val(data.comentario_solicitud);
          $("#respuesta_reject").val(data.comentario_respuesta);
          // Llenar la tabla de fechas
          const tablaFechas = $("#tablaFechasEdit tbody");
          tablaFechas.empty();
          const fechas = data.fechas.split(","); // Separar fechas
          const valores = data.valores.split(","); // Separar valores
          const dias = data.dias.split(","); // Separar días
          fechas.forEach((fecha, index) => {
            tablaFechas.append(`
              <tr>
                <td>${fecha}</td>
                <td>${valores[index]}</td>
                <td>${dias[index]}</td>
              </tr>
            `);
          });
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  $(document).on("click", ".reject-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;
          // Establecer los valores de los campos en el modal
          $("#jefe_aprobador_reject").val(data.responsable_aprobador);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });
  


  // Consumido

  $(document).on("click", ".cons-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getSolicitudbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_solicitud").val(data.id_solicitud);
          $("#nombre_completo_consumido").val(data.nombre_completo);
          $("#asunto_consumido").val(data.asunto);
          $("#comentario_solicitud").val(data.comentario_solicitud);
          $("#comentario_respuesta_up").val(data.comentario_respuesta);

          // Llenar la tabla de fechas
          const tablaFechas = $("#tablaFechasEdit tbody");
          tablaFechas.empty();
          const fechas = data.fechas.split(","); // Separar fechas
          const valores = data.valores.split(","); // Separar valores
          const dias = data.dias.split(","); // Separar días
          fechas.forEach((fecha, index) => {
            tablaFechas.append(`
                <tr>
                  <td>${fecha}</td>
                  <td>${valores[index]}</td>
                  <td>${dias[index]}</td>
                </tr>
              `);
          });
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  $(document).on("click", ".cons-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;
          // Establecer los valores de los campos en el modal
          $("#jefe_aprobador_consumido").val(data.responsable_aprobador);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });
  

  // Cancelado
  $(document).on("click", ".cancel-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getSolicitudbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_solicitud").val(data.id_solicitud);
          $("#nombre_completo_cancel").val(data.nombre_completo);
          $("#asunto_cancel").val(data.asunto);
          $("#solicitud_cancel").val(data.comentario_solicitud);
          // Llenar la tabla de fechas
          const tablaFechas = $("#tablaFechasEdit tbody");
          tablaFechas.empty();
          const fechas = data.fechas.split(","); // Separar fechas
          const valores = data.valores.split(","); // Separar valores
          const dias = data.dias.split(","); // Separar días
          fechas.forEach((fecha, index) => {
            tablaFechas.append(`
                <tr>
                  <td>${fecha}</td>
                  <td>${valores[index]}</td>
                  <td>${dias[index]}</td>
                </tr>
              `);
          });
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  $(document).on("click", ".cancel-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;
          // Establecer los valores de los campos en el modal
          $("#jefe_aprobador_cancel").val(data.responsable_aprobador);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });
  

  // Manejar la acción de aprobar o rechazar
  document
    .getElementById("updateSolicitudForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Determina el botón presionado y su acción
      const submitButton = event.submitter; // Captura el botón que disparó el evento
      const action = submitButton.value;

      // Selecciona la URL adecuada según la acción
      const ajaxUrl =
        action === "no_approve"
          ? base_url + "/Vacaciones_Revision/setRechazoEmpleado"
          : base_url + "/Vacaciones_Revision/setAprobarEmpleado";

      // Crea el objeto FormData a partir del formulario
      const formData = new FormData(this);

      // Obtiene el spinner dentro del botón presionado
      const spinner = submitButton.querySelector("span");

      // Deshabilita el botón y muestra el spinner
      submitButton.disabled = true;
      spinner.classList.remove("d-none");

      const request = new XMLHttpRequest();
      request.open("POST", ajaxUrl, true);
      request.send(formData);

      // Maneja la respuesta del servidor
      request.onreadystatechange = function () {
        if (request.readyState === 4) {
          // Oculta el spinner y habilita el botón
          if (request.status === 200) {
            try {
              const response = JSON.parse(request.responseText);
              if (response.status) {
                // Mensajes personalizados según la acción
                const message =
                  action === "no_approve"
                    ? "La solicitud ha sido rechazada"
                    : "La solicitud ha sido aprobada";

                Swal.fire({
                  title: "Operación exitosa",
                  text: message,
                  icon: "success",
                  confirmButtonText: "Aceptar",
                }).then((result) => {
                  if (result.isConfirmed) {
                    location.reload(); // Recargar la página al presionar "Aceptar"
                  }
                });
              } else {
                spinner.classList.add("d-none");
                submitButton.disabled = false;
                Swal.fire(
                  "Error",
                  response.message || "Ocurrió un error",
                  "error"
                );
              }
            } catch (error) {
              Swal.fire("Error", "Respuesta no válida del servidor", "error");
            }
          } else {
            Swal.fire("Error", "Error en la solicitud al servidor", "error");
          }
        }
      };

      // Maneja errores de red
      request.onerror = function () {
        spinner.classList.add("d-none");
        submitButton.disabled = false;

        Swal.fire(
          "Error",
          "No se pudo establecer conexión con el servidor",
          "error"
        );
      };
    });

  // FINAL
});
