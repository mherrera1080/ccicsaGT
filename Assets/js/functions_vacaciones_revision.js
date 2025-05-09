let tableVacaciones;

document.addEventListener("DOMContentLoaded", function () {
  let mesActual = new Date().getMonth() + 1; // Obtiene el mes actual (1-12)
  let mesSelector = document.getElementById("mesSelector");
  mesSelector.value = mesActual;

  function actualizarDatos(mes) {
    fetch(`${base_url}/Vacaciones_Revision/getAllSolicitudesbyMes/${mes}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          actualizarContadores(data.data);
        } else {
          console.error("Error en la respuesta del servidor:", data.msg);
        }
      })
      .catch((error) => console.error("Error al cargar datos:", error));
  }

  function actualizarContadores(solicitudes) {
    let contadores = {
      info: 0,
      revertido: 0,
      approve: 0,
      reject: 0,
      pendiente: 0,
      canceled: 0,
    };

    solicitudes.forEach((solicitud) => {
      switch (solicitud.estado.trim()) {
        case "Pendiente":
          contadores.pendiente++;
          break;
        case "Pendiente Aprob. 1":
          contadores.pendiente++;
          break;
        case "Pendiente Aprob. 2":
          contadores.pendiente++;
          break;
        case "Pendiente Aprob. 3":
          contadores.pendiente++;
          break;
        case "Aprobado":
          contadores.approve++;
          contadores.info++;
          break;
        case "Revertido":
          contadores.revertido++;
          contadores.info++;
          break;
        case "Rechazado":
          contadores.reject++;
          break;
        case "Cancelado":
          contadores.canceled++;
          break;
        default:
          contadores.info++;
      }
    });

    document.querySelector(
      ".info.h5.mb-0.font-weight-bold.text-gray-800"
    ).textContent = contadores.info;
    document.querySelector(
      ".revertido.h5.mb-0.font-weight-bold.text-gray-800"
    ).textContent = contadores.revertido;
    document.querySelector(
      ".approve.h5.mb-0.font-weight-bold.text-gray-800"
    ).textContent = contadores.approve;
    document.querySelector(
      ".reject.h5.mb-0.font-weight-bold.text-gray-800"
    ).textContent = contadores.reject;
    document.querySelector(
      ".pendiente.h5.mb-0.font-weight-bold.text-gray-800"
    ).textContent = contadores.pendiente;
    document.querySelector(
      ".canceled.h5.mb-0.font-weight-bold.text-gray-800"
    ).textContent = contadores.canceled;
  }

  tableVacaciones = $("#tableVacaciones").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
      emptyTable: "No hay solicitudes para el mes seleccionado.", // Mensaje personalizado
    },
    ajax: {
      url:
        base_url + "/Vacaciones_Revision/getAllSolicitudesbyMes/" + mesActual,
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
        render: function (data, type, row) {
          if (data && data.trim().includes("Pendiente")) {
            return `
                <button type="button" class="btn btn-warning info-btn" data-bs-toggle="modal" data-bs-target="#infoSolicitudModal" data-id="${row.solicitud_id}">
                   <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                   <i class="fa-solid fa-hourglass-start"></i>          
                   </div>
                </button>
                `;
          } else if (!data || data.trim() === "Revertido") {
            return `
                <button type="button" class="btn btn-warning revertido-btn" data-bs-toggle="modal" data-bs-target="#revertidoSolicitudModal" data-id="${row.solicitud_id}">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                    <i class="fa-solid fa-clock-rotate-left"></i>               
                    </div>
                </button>
                `;
          } else if (!data || data.trim() === "Aprobado") {
            return `
                <button type="button" class="btn btn-success cons-btn" data-bs-toggle="modal" data-bs-target="#consumidoSolicitudModal" data-id="${row.solicitud_id}">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                    <i class="fa-regular fa-thumbs-up"></i>                
                    </div>
                </button>
                `;
          } else if (!data || data.trim() === "Rechazado") {
            return `
                <button type="button" class="btn btn-danger reject-btn" data-bs-toggle="modal" data-bs-target="#rejectSolicitud" data-id="${row.solicitud_id}">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                    <i class="fa-solid fa-not-equal"></i>                
                    </div>
                </button>
                `;
          } else if (!data || data.trim() === "Cancelado") {
            return `
                <button type="button" class="btn btn-danger cancel-btn" data-bs-toggle="modal" data-bs-target="#cancelSolicitudModal" data-id="${row.solicitud_id}">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                    <i class="fa-solid fa-ban"></i>                
                    </div>
                </button>
                `;
          } else if (!data || data.trim() === "Reversion") {
            return `
                <button type="button" class="btn btn-warning reversion-btn" data-bs-toggle="modal" data-bs-target="#reversionSolicitudModal" data-id="${row.solicitud_id}">
                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                    <i class="fa-solid fa-exclamation"></i>                
                    </div>
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
    order: [[0, "desc"]],
  });

  document
    .querySelector("#mesSelector")
    .addEventListener("change", function () {
      let mes = this.value; // Obtener el nuevo mes seleccionado
      tableVacaciones.ajax
        .url(base_url + "/Vacaciones_Revision/getAllSolicitudesbyMes/" + mes)
        .load(); // Recargar la tabla
    });

  mesSelector.addEventListener("change", function () {
    actualizarDatos(this.value);
  });

  actualizarDatos(mesActual);

  $("#infoSolicitudModal").on("hidden.bs.modal", function () {
    // Limpiar campos del formulario
    $("#tablaFechasEdit tbody").empty();
  });

  // Informacion
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
          $("#id_solicitud").val(data.id_solicitud);
          $("#nombre_completo_info").val(data.nombre_completo);
          $("#asunto_info").val(data.asunto);
          $("#solicitud_info").val(data.comentario_solicitud);
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

          // Establecer los valores de los campos en el modal
          $("#jefe_aprobador_info").val(data.responsable_aprobador);
          $("#reversion_aprobador_1_info").val(data.nombre_aprobador_1);
          $("#reversion_aprobador_2_info").val(data.nombre_aprobador_2);
          $("#reversion_aprobador_3_info").val(data.nombre_aprobador_3);

          if (data.responsable_aprobador != null) {
            // Mostrar el campo
            $("#jefe_aprobador_info").closest(".mb-3").show();
            $("#reversion_aprobador_1_info").closest(".mb-3").hide();
            $("#reversion_aprobador_2_info").closest(".mb-3").hide();
            $("#reversion_aprobador_3_info").closest(".mb-3").hide();
          } else {
            // Ocultar el campo
            $("#jefe_aprobador_info").closest(".mb-3").hide();
            $("#reversion_aprobador_1_info").closest(".mb-3").show();
            $("#reversion_aprobador_2_info").closest(".mb-3").show();
            $("#reversion_aprobador_3_info").closest(".mb-3").show();
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
          $("#reversion_aprobador_1_reject").val(data.nombre_aprobador_1);
          $("#reversion_aprobador_2_reject").val(data.nombre_aprobador_2);
          $("#reversion_aprobador_3_reject").val(data.nombre_aprobador_3);

          if (data.responsable_aprobador != null) {
            // Mostrar el campo
            $("#jefe_aprobador_reject").closest(".mb-3").show();
            $("#reversion_aprobador_1_reject").closest(".mb-3").hide();
            $("#reversion_aprobador_2_reject").closest(".mb-3").hide();
            $("#reversion_aprobador_3_reject").closest(".mb-3").hide();
          } else {
            // Ocultar el campo
            $("#jefe_aprobador_reject").closest(".mb-3").hide();
            $("#reversion_aprobador_1_reject").closest(".mb-3").show();
            $("#reversion_aprobador_2_reject").closest(".mb-3").show();
            $("#reversion_aprobador_3_reject").closest(".mb-3").show();
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
          $("#respuesta_cancel").val(data.comentario_respuesta);
          // Llenar la tabla de fechas
          const tablaFechasCancel = $("#tablaFechasCancel tbody");
          tablaFechasCancel.empty();
          const fechas = data.fechas.split(","); // Separar fechas
          const valores = data.valores.split(","); // Separar valores
          const dias = data.dias.split(","); // Separar días
          fechas.forEach((fecha, index) => {
            tablaFechasCancel.append(`
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
          $("#reversion_aprobador_1_cancel").val(data.nombre_aprobador_1);
          $("#reversion_aprobador_2_cancel").val(data.nombre_aprobador_2);
          $("#reversion_aprobador_3_cancel").val(data.nombre_aprobador_3);

          if (data.responsable_aprobador != null) {
            // Mostrar el campo
            $("#jefe_aprobador_cancel").closest(".mb-3").show();
            $("#reversion_aprobador_1_cancel").closest(".mb-3").hide();
            $("#reversion_aprobador_2_cancel").closest(".mb-3").hide();
            $("#reversion_aprobador_3_cancel").closest(".mb-3").hide();
          } else {
            // Ocultar el campo
            $("#jefe_aprobador_cancel").closest(".mb-3").hide();
            $("#reversion_aprobador_1_cancel").closest(".mb-3").show();
            $("#reversion_aprobador_2_cancel").closest(".mb-3").show();
            $("#reversion_aprobador_3_cancel").closest(".mb-3").show();
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

  $(document).on("click", ".reversion-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getSolicitudbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_solicitud_reversion").val(data.id_solicitud);
          $("#nombre_completo_reversion").val(data.nombre_completo);
          $("#categoria_solicitud_reversion").val(data.id_categoria);
          $("#asunto_reversion").val(data.asunto);
          $("#solicitud_reversion").val(data.comentario_solicitud);
          $("#respuesta_reversion").val(data.comentario_respuesta);

          // Llenar la tabla de fechas
          const tablaFechasReversion = $("#tablaFechasReversion tbody");
          tablaFechasReversion.empty();
          const fechas = data.fechas.split(","); // Separar fechas
          const valores = data.valores.split(","); // Separar valores
          const dias = data.dias.split(","); // Separar días
          fechas.forEach((fecha, index) => {
            tablaFechasReversion.append(`
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

  $(document).on("click", ".reversion-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#jefe_aprobador_reversion").val(data.responsable_aprobador);
          $("#reversion_aprobador_1").val(data.nombre_aprobador_1);
          $("#reversion_aprobador_2").val(data.nombre_aprobador_2);
          $("#reversion_aprobador_3").val(data.nombre_aprobador_3);

          if (data.responsable_aprobador != null) {
            // Mostrar el campo
            $("#jefe_aprobador_reversion").closest(".mb-3").show();
            $("#reversion_aprobador_1").closest(".mb-3").hide();
            $("#reversion_aprobador_2").closest(".mb-3").hide();
            $("#reversion_aprobador_3").closest(".mb-3").hide();
          } else {
            // Ocultar el campo
            $("#jefe_aprobador_reversion").closest(".mb-3").hide();
            $("#reversion_aprobador_1").closest(".mb-3").show();
            $("#reversion_aprobador_2").closest(".mb-3").show();
            $("#reversion_aprobador_3").closest(".mb-3").show();
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

  $(document).on("click", ".revertido-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getSolicitudbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_solicitud_revertido").val(data.id_solicitud);
          $("#nombre_completo_revertido").val(data.nombre_completo);
          $("#categoria_solicitud_reversion").val(data.id_categoria);
          $("#asunto_revertido").val(data.asunto);

          // Llenar la tabla de fechas
          const tablaFechasReversion = $("#tablaFechasRevertido tbody");
          tablaFechasReversion.empty();
          const fechas = data.fechas.split(","); // Separar fechas
          const valores = data.valores.split(","); // Separar valores
          const dias = data.dias.split(","); // Separar días
          fechas.forEach((fecha, index) => {
            tablaFechasReversion.append(`
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

  $(document).on("click", ".revertido-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones_Revision/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#jefe_aprobador_revertido").val(data.responsable_aprobador);
          $("#revertido_aprobador_1").val(data.nombre_aprobador_1);
          $("#revertido_aprobador_2").val(data.nombre_aprobador_2);
          $("#revertido_aprobador_3").val(data.nombre_aprobador_3);

          if (data.responsable_aprobador != null) {
            // Mostrar el campo
            $("#jefe_aprobador_revertido").closest(".mb-3").show();
            $("#revertido_aprobador_1").closest(".mb-3").hide();
            $("#revertido_aprobador_2").closest(".mb-3").hide();
            $("#revertido_aprobador_3").closest(".mb-3").hide();
          } else {
            // Ocultar el campo
            $("#jefe_aprobador_revertido").closest(".mb-3").hide();
            $("#revertido_aprobador_1").closest(".mb-3").show();
            $("#revertido_aprobador_2").closest(".mb-3").show();
            $("#revertido_aprobador_3").closest(".mb-3").show();
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
          $("#solicitud_consumido").val(data.comentario_solicitud);
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
          $("#reversion_aprobador_1_consumido").val(data.nombre_aprobador_1);
          $("#reversion_aprobador_2_consumido").val(data.nombre_aprobador_2);
          $("#reversion_aprobador_3_consumido").val(data.nombre_aprobador_3);

          if (data.responsable_aprobador != null) {
            // Mostrar el campo
            $("#jefe_aprobador_consumido").closest(".mb-3").show();
            $("#reversion_aprobador_1_consumido").closest(".mb-3").hide();
            $("#reversion_aprobador_2_consumido").closest(".mb-3").hide();
            $("#reversion_aprobador_3_consumido").closest(".mb-3").hide();
          } else {
            // Ocultar el campo
            $("#jefe_aprobador_consumido").closest(".mb-3").hide();
            $("#reversion_aprobador_1_consumido").closest(".mb-3").show();
            $("#reversion_aprobador_2_consumido").closest(".mb-3").show();
            $("#reversion_aprobador_3_consumido").closest(".mb-3").show();
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

  document
    .getElementById("reversionForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      // Determina la acción del botón presionado
      const submitButton = event.submitter; // Captura el botón que disparó el evento
      const action = submitButton.value;

      // Selecciona la URL adecuada según la acción
      const ajaxUrl =
        action === "no_approve"
          ? base_url + "/Vacaciones_Revision/setRechazoReversion"
          : base_url + "/Vacaciones_Revision/setAprobarReversion";

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
              // Mensajes personalizados según la acción
              const message =
                action === "no_approve"
                  ? "La Renovacion ha sido rechazada"
                  : "La Renovacion ha sido aprobada";

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
});
