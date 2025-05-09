let tableSolicitud;

document.addEventListener("DOMContentLoaded", function () {
  let id_empleado = document.querySelector("#id_empleado").value;

  // Inicializa DataTable
  tableSolicitud = $("#tableSolicitud").DataTable({
    aProcessing: true,
    aServerSide: true,
    deferRender: true, // Mejora el rendimiento retrasando el renderizado
    responsive: true,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url:
        base_url +
        "/Vacaciones_Revision/getSolicitudesbyJefeOperativa/" +
        id_empleado,
      data: function (d) {
        d.limit = d.length; // Implementar paginación en el backend
        d.offset = d.start;
      },
      error: function (xhr, error, thrown) {
        console.error("Error al cargar datos:", error, thrown);
      },
    },
    columns: [
      {
        data: "solicitud_id",
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
            Pendiente: "Pendiente",
            "Pendiente Aprob. 1": "Pendiente",
            "Pendiente Aprob. 2": "Pendiente",
            "Pendiente Aprob. 3": "Pendiente",
            //Administrativa
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
          } else if (!data || data.trim() === "Aprobado") {
            return `
            <button type="button" class="btn btn-success cons-btn" data-bs-toggle="modal" data-bs-target="#consumidoSolicitudModal" data-id="${row.solicitud_id}">
                <i class="fa fa-check" aria-hidden="true"></i>
            </button>
            `;
          }else if (!data || data.trim() === "Rechazado") {
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
          } else if (
            data?.trim() === "Pendiente Aprob. 1" &&
            row.mi_revision === 'revision_aprobador_1'
          ) {
            return `
            <button type="button" class="btn btn-warning actOperativaArea-btn" data-bs-toggle="modal" data-bs-target="#updateSolicitudOperativaModal" data-id="${row.solicitud_id}">
                <i class="fas fa-pencil-square"></i>
            </button>
            `;
          } else if (
            data?.trim() === "Pendiente Aprob. 2" &&
            row.mi_revision === 'revision_aprobador_2'
          ) {
            return `
            <button type="button" class="btn btn-warning actOperativaJefe-btn" data-bs-toggle="modal" data-bs-target="#updateSolicitudOperativaJefeModal" data-id="${row.solicitud_id}">
                <i class="fas fa-pencil-square"></i>
            </button>
            `;
          } else if (
            data?.trim() === "Pendiente Aprob. 3" &&
            row.mi_revision === 'revision_aprobador_3'
          ) {
            return `
            <button type="button" class="btn btn-warning actOperativaGerente-btn" data-bs-toggle="modal" data-bs-target="#updateSolicitudOperativaGerenteModal" data-id="${row.solicitud_id}">
                <i class="fas fa-pencil-square"></i>
            </button>
            `;
          } else if (data && data.trim().includes("Pendiente Aprob.")) {
            return `
            <button type="button" class="btn btn-info info-btn" data-bs-toggle="modal" data-bs-target="#infoOperativaModal" data-id="${row.solicitud_id}">
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
    pageLength: 10, // Reducimos filas iniciales para mejor rendimiento
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
          $("#jefe_aprobador").val(data.jefe_aprobador);
          $("#comentario_solicitud_update").val(data.comentario_solicitud);
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

  // ACTUALIZAR OPORATIVA

  $(document).on("click", ".actOperativaArea-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getSolicitudbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_solicitud_operativa").val(data.id_solicitud);
          $("#nombre_completo_operativa").val(data.nombre_completo);
          $("#revision_aprobador_1").val(data.revision_aprobador_1);
          $("#comentario_solicitud_update_operativa").val(data.comentario_solicitud);
          $("#asunto_operativa").val(data.asunto);

          // Llenar la tabla de fechas
          const tablaFechas = $("#tablaFechasOperativoEdit tbody");
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

  $(document).on("click", ".actOperativaJefe-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getSolicitudbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_revision_aprobador_2").val(data.id_solicitud);
          $("#nombre_completo_jefe").val(data.nombre_completo);
          $("#jefe_aprobador_jefe").val(data.jefe_aprobador);
          $("#asunto_jefe").val(data.asunto);
          $("#comentario_solicitud").val(data.comentario_solicitud);

          // Llenar la tabla de fechas
          const tablaFechas = $("#tablaFechasOperativoEdit tbody");
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

  $(document).on("click", ".actOperativaGerente-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getSolicitudbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_revision_aprobador_3").val(data.id_solicitud);
          $("#nombre_completo_gerente").val(data.nombre_completo);
          $("#asunto_gerente").val(data.asunto);
          $("#comentario_solicitud_gerente").val(data.comentario_solicitud);
          $("#categoria_solicitud").val(data.id_categoria);

          // Llenar la tabla de fechas
          const tablaFechas = $("#tablaFechasOperativoEdit tbody");
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

  $(document).on("click", ".actOperativaGerente-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          $("#jefe_aprobador_gerente").val(data.jefe_aprobador);
          $("#revision_aprobador_1_gerente").val(data.nombre_aprobador_1);
          $("#revision_aprobador_2_gerente").val(data.nombre_aprobador_2);
          $("#revision_aprobador_3_gerente").val(data.nombre_aprobador_3);

        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });



  $(document).on("click", ".infoOperativa-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getSolicitudbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_solicitud_operativa").val(data.id_solicitud);
          $("#nombre_completo_operativa").val(data.nombre_completo);
          $("#jefe_aprobador_operativa").val(data.jefe_aprobador);
          $("#asunto_operativa").val(data.asunto);

          // Llenar la tabla de fechas
          const tablaFechas = $("#tablaFechasOperativoEdit tbody");
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

  if (document.querySelector("#revision_aprobador_1")) {
    let ajaxUrl = base_url + "/Vacaciones_Revision/getSelectAprobador1"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#revision_aprobador_1").innerHTML = request.responseText;
        $("#revision_aprobador_1").selectpicker("refresh");
      }
    };
  }

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
          $("#jefe_aprobador_reject").val(data.responsable_aprobador);
          $("#revision_aprobador_1_reject").val(data.nombre_aprobador_1);
          $("#revision_aprobador_2_reject").val(data.nombre_aprobador_2);
          $("#revision_aprobador_3_reject").val(data.nombre_aprobador_3);
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
          $("#solicitud_consumido").val(data.comentario_respuesta);
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
          $("#jefe_aprobador_consumido").val(data.responsable_aprobador);
          $("#revision_aprobador_1_consumido").val(data.nombre_aprobador_1);
          $("#revision_aprobador_2_consumido").val(data.nombre_aprobador_2);
          $("#revision_aprobador_3_consumido").val(data.nombre_aprobador_3);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });


  $(document).on("click", ".info-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getSolicitudbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_solicitud_info").val(data.id_solicitud);
          $("#nombre_completo_info").val(data.nombre_completo);
          $("#asunto_info").val(data.asunto);
          $("#comentario_solicitud_info").val(data.comentario_solicitud);
          $("#estado_info").val(data.estado);

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

  $(document).on("click", ".info-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          $("#jefe_aprobador_info").val(data.responsable_aprobador);
          $("#revision_aprobador_1_info").val(data.nombre_aprobador_1);
          $("#revision_aprobador_2_info").val(data.nombre_aprobador_2);
          $("#revision_aprobador_3_info").val(data.nombre_aprobador_3);
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
          $("#jefe_aprobador_cancel").val(data.jefe_aprobador);
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

          $("#jefe_aprobador_info").val(data.responsable_aprobador);
          $("#revision_aprobador_1_cancel").val(data.nombre_aprobador_1);
          $("#revision_aprobador_2_info").val(data.nombre_aprobador_2);
          $("#revision_aprobador_3_info").val(data.nombre_aprobador_3);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });


  $(document).on("click", ".info-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          $("#jefe_aprobador_info").val(data.responsable_aprobador);
          $("#revision_aprobador_1_info").val(data.nombre_aprobador_1);
          $("#revision_aprobador_2_info").val(data.nombre_aprobador_2);
          $("#revision_aprobador_3_info").val(data.nombre_aprobador_3);
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

      // Determina la acción del botón presionado
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
        if (request.readyState === 4 && request.status === 200) {
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
              Swal.fire(
                "Error",
                response.message || "Ocurrió un error",
                "error"
              );
            }
          } catch (error) {
            Swal.fire("Error", "Respuesta no válida del servidor", "error");
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

  // Manejar la acción de aprobar o rechazar
  document
    .getElementById("updateSolicitudOperativaForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Determina la acción del botón presionado
      const submitButton = event.submitter; // Captura el botón que disparó el evento
      const action = submitButton.value;

      // Selecciona la URL adecuada según la acción
      const ajaxUrl =
        action === "no_approve"
          ? base_url + "/Vacaciones_Revision/setRechazoEmpleadoOperativa"
          : base_url + "/Vacaciones_Revision/setAprobarEmpleadoOperativa";

      // Crea el objeto FormData a partir del formulario
      const formData = new FormData(this);

      const spinner = submitButton.querySelector("span");

      // Deshabilita el botón y muestra el spinner
      submitButton.disabled = true;
      spinner.classList.remove("d-none");

      const request = new XMLHttpRequest();
      request.open("POST", ajaxUrl, true);
      request.send(formData);

      // Maneja la respuesta del servidor de Operativa
      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
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

  document
    .getElementById("updateSolicitudOperativaJefeForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Determina la acción del botón presionado
      const submitButton = event.submitter; // Captura el botón que disparó el evento
      const action = submitButton.value;

      // Selecciona la URL adecuada según la acción
      const ajaxUrl =
        action === "no_approve"
          ? base_url + "/Vacaciones_Revision/setRechazoEmpleadoOperativa2"
          : base_url + "/Vacaciones_Revision/setAprobarEmpleadoOperativa2";

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

      // Maneja la respuesta del servidor de Operativa
      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
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
            spinner.classList.add("d-none");
            submitButton.disabled = false;
            Swal.fire("Error", "Respuesta no válida del servidor", "error");
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

  document
    .getElementById("updateSolicitudOperativaGerenteForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Determina la acción del botón presionado
      const submitButton = event.submitter; // Captura el botón que disparó el evento
      const action = submitButton.value;

      // Selecciona la URL adecuada según la acción
      const ajaxUrl =
        action === "no_approve"
          ? base_url + "/Vacaciones_Revision/setRechazoEmpleadoOperativa3"
          : base_url + "/Vacaciones_Revision/setAprobarEmpleadoOperativa3";

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

      // Maneja la respuesta del servidor de Operativa
      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
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
