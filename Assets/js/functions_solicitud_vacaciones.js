let tableSolicitud;

document.addEventListener("DOMContentLoaded", function () {
  let id_empleado = document.querySelector("#id_empleado").value;
  let formulario_vacaciones = document.querySelector(
    "#formulario_vacaciones"
  ).value;

  // Inicializa DataTable
  tableSolicitud = $("#tableSolicitud").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    searching: false,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Vacaciones/getSolicitudesbyID/" + id_empleado,
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
      { data: "solicitud_id" },
      { data: "fecha_solicitud" },
      { data: "categorias" },
      { data: "dias" },
      { data: "fecha_aprobacion" },
      {
        data: "estado",
        render: function (data, type, row) {
          if (!data || data.trim() === "Pendiente Aprob. 1") {
            return `
            <button type="button" class="btn btn-warning update-btn" data-bs-toggle="modal" data-bs-target="#updateSolicitudModal" data-id="${row.solicitud_id}">
                <i class="fas fa-pencil-square"></i>
            </button>
            <button type="button" class="btn btn-danger canceled-btn" data-bs-toggle="modal" data-bs-target="#canceledSolicitudModal" data-id="${row.solicitud_id}">
                <i class="fas fa-times-circle"></i>
            </button>
            `;
          } else if (!data || data.trim() === "Pendiente") {
            return `
            <button type="button" class="btn btn-warning update-btn" data-bs-toggle="modal" data-bs-target="#updateSolicitudModal" data-id="${row.solicitud_id}">
                <i class="fas fa-pencil-square"></i>
            </button>
            <button type="button" class="btn btn-danger canceled-btn" data-bs-toggle="modal" data-bs-target="#canceledSolicitudModal" data-id="${row.solicitud_id}">
                <i class="fas fa-times-circle"></i>
            </button>
            `;
          } else if (!data || data.trim() === "Reversion") {
            return `
            <button type="button" class="btn btn-info info-btn" data-bs-toggle="modal" data-bs-target="#infoOperativaModal" data-id="${row.solicitud_id}">
                <i class="fa fa-check" aria-hidden="true"></i>
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
          } else if (data && data.trim().includes("Pendiente Aprob.")) {
            return `
            <button type="button" class="btn btn-info info-btn" data-bs-toggle="modal" data-bs-target="#infoOperativaModal" data-id="${row.solicitud_id}">
                <i class="fa fa-check" aria-hidden="true"></i>
            </button>
            `;
          } else if (
            data?.trim() === "Aprobado" &&
            formulario_vacaciones.trim() === "Operativa"
          ) {
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
          } else if (
            data?.trim() === "Rechazado" &&
            formulario_vacaciones.trim() === "Operativa"
          ) {
            return `
            <button type="button" class="btn btn-danger reject-btn" data-bs-toggle="modal" data-bs-target="#rejectSolicitud" data-id="${row.solicitud_id}">
                <i class="fa fa-times" aria-hidden="true"></i>
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
    .querySelector("button[type='submit']")
    .addEventListener("click", function () {
      document.getElementById("tokenSpinner").classList.remove("d-none");
    });

  $(document).on("click", ".info-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones/getSolicitudbyIDOperativa/${id_solicitud}`,
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
          const tablaFechas = $("#tablaFechasEditInfo tbody");
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
      url: `${base_url}/Vacaciones/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          $("#jefe_aprobador_cancel").val(data.responsable_aprobador);
          $("#revision_aprobador_1_info").val(data.nombre_aprobador_1);
          $("#revision_aprobador_2_info").val(data.nombre_aprobador_2);
          $("#revision_aprobador_3_info").val(data.nombre_aprobador_3);

          if (data.responsable_aprobador != null ) {
            // Mostrar el campo
            $("#jefe_aprobador_revertido").closest(".mb-3").show();
            $("#revision_aprobador_1_info").closest(".mb-3").hide();
            $("#revision_aprobador_2_info").closest(".mb-3").hide();
            $("#revision_aprobador_3_info").closest(".mb-3").hide();
          } else {
            // Ocultar el campo
            $("#jefe_aprobador_revertido").closest(".mb-3").hide();
            $("#revision_aprobador_1_info").closest(".mb-3").show();
            $("#revision_aprobador_2_info").closest(".mb-3").show();
            $("#revision_aprobador_3_info").closest(".mb-3").show();
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

  $(document).on("click", ".pendiente-btn", function () {
    const id_empleado = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones/getSolicitudbyIDPendiente/${id_empleado}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;
          // Establecer los valores de los campos en el modal
          $("#id_solicitud_pendiente").val(data.id_solicitud);
          $("#nombre_completo_pendiente").val(data.nombre_completo);

          // Llenar la tabla de fechas
          const tablaFechas = $("#tablaFechasPendiente tbody");
          tablaFechas.empty();
          const id_solicitud = data.id_solicitud;
          const fechas = data.fecha_solicitud;
          const dias_pendientes = data.dias_pendientes;
          const estado = data.estado;
          tablaFechas.append(`
                <tr>
                  <td>${id_solicitud}</td>
                  <td>${fechas}</td>
                  <td>${dias_pendientes}</td>
                  <td>${estado}</td>
                </tr>
              `);
        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });
  });

  if (document.querySelector("#responsable_aprobacion")) {
    let ajaxUrl = base_url + "/Vacaciones/getSelectAprobador"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#responsable_aprobacion").innerHTML =
          request.responseText;
        $("#responsable_aprobacion").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#id_categoria")) {
    let ajaxUrl = base_url + "/Vacaciones/getSelectCategorias"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#id_categoria").innerHTML =
          request.responseText;
        $("#id_categoria").selectpicker("refresh");
      }
    };
  }

  // --- Modal: Agregar y quitar fechas ---
  const tablaFechas = document
    .getElementById("tablaFechas")
    .querySelector("tbody");

  // Función para agregar nueva fila
  const agregarFechaBtn = document.getElementById("agregarFecha");

  agregarFechaBtn.addEventListener("click", () => {
    const nuevaFila = document.createElement("tr");
    nuevaFila.innerHTML = `
    <td>
        <input type="date" class="form-control" name="fechas[]" required>
    </td>
    <td>
        <select class="form-control valor-select" name="valor[]" required>
            <option value="1">Día Completo</option>
            <option value="0.5">Medio Día</option>
        </select>
    </td>
    <td>
        <select class="form-control dia-select" name="dia[]" >
            <option value="Completo" selected>Día Completo</option>
        </select>
    </td>
    <td>
        <button type="button" class="btn btn-danger eliminarFila">
            <i class="fas fa-trash-alt"></i>
        </button>
    </td>`;
    tablaFechas.appendChild(nuevaFila);

    const valorSelect = nuevaFila.querySelector(".valor-select");
    const diaSelect = nuevaFila.querySelector(".dia-select");

    // Evento para manejar el cambio de valor
    valorSelect.addEventListener("change", () => {
      if (valorSelect.value === "1") {
        diaSelect.innerHTML = `
        <option value="Completo" selected>Día Completo</option>
      `;
        diaSelect.prop("", true);
      } else {
        diaSelect.innerHTML = `
        <option value="AM">A.M.</option>
        <option value="PM">P.M.</option>
      `;
        diaSelect.disabled = false;
      }
    });

    // Botón para eliminar fila
    const eliminarBtn = nuevaFila.querySelector(".eliminarFila");
    eliminarBtn.addEventListener("click", () => {
      nuevaFila.remove();
    });
  });

  // --- Enviar formulario ---
  document
    .getElementById("SolicitudForm")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      // Recoge los valores de las fechas y los días
      const fechas = Array.from(
        document.querySelectorAll("input[name='fechas[]']")
      ).map((input) => input.value);

      const valores = Array.from(
        document.querySelectorAll("select[name='valor[]']")
      ).map((select) => select.value);

      const dias = Array.from(
        document.querySelectorAll("select[name='dia[]']")
      ).map((select) => select.value);

      // Verifica si se han agregado fechas
      if (fechas.length === 0) {
        alert("Debes agregar al menos una fecha.");
        return;
      }

      // Envía la solicitud vía AJAX
      const formData = new FormData(this);
      const spinner = document.querySelector("#spinerSubmit ");
      const submitButton = document.querySelector("#btnSubmit");

      submitButton.disabled = true;
      spinner.classList.remove("d-none");

      fechas.forEach((fecha, index) => {
        formData.append(`fechas[${index}]`, fecha);
        formData.append(`valor[${index}]`, valores[index]);
        formData.append(`dia[${index}]`, dias[index]);
      });

      fetch(base_url + "/Vacaciones/guardarSolicitud", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status) {
            Swal.fire({
              title: "Éxito",
              text: data.message,
              icon: "success", // Icono de éxito
              confirmButtonText: "OK",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
            $("#solicitudVacaciones").modal("hide");
            tableSolicitud.ajax.reload();
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
          Swal.fire({
            title: "Error",
            text: "Ocurrió un problema al procesar la solicitud.",
            icon: "error",
            confirmButtonText: "Entendido",
          });
          console.error("Error:", error);
        });
    });

  // ACTUALIZAAR

  $(document).on("click", ".update-btn", function () {
    const idSolicitud = $(this).data("id");
    $.ajax({
      url: `${base_url}/Vacaciones/getSolicitudbyID/${idSolicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          cargarDatosEdicion(response.data);
          $("#updateSolicitudModal").modal("show");
        } else {
          alert("No se encontraron datos.");
        }
      },
      error: function (error) {
        console.error("Error en AJAX:", error);
      },
    });
  });

  // Función para cargar datos en el formulario de edición
  function cargarDatosEdicion(data) {
    // Llenar los campos de la solicitud principal
    $("#edit_id_solicitud").val(data.id_solicitud);
    $("#edit_id_empleado").val(data.id_empleado);
    $("#edit_responsable_aprobacion").val(data.responsable_aprobacion);
    $("#edit_comentario_solicitud").val(data.comentario_solicitud);
    $("#revision_aprobador_1").val(data.revision_aprobador_1);
    $("#edit_id_categoria").val(data.id_categoria);

    // Limpiar la tabla de fechas
    const tablaFechasEdit = $("#tablaFechasEdit tbody");
    tablaFechasEdit.empty();

    // Cargar las fechas, valores y días en la tabla
    data.fechas.forEach((fecha, index) => {
      const valor = data.valores[index];
      const dia = data.dias[index];

      // Añadir fila
      tablaFechasEdit.append(`
            <tr>
                <td>
                    <input type="date" class="form-control" name="fechas[]" value="${fecha}" required>
                </td>
                <td>
                    <select class="form-control valor-select" name="valor[]" required>
                        <option value="1" ${
                          valor === "1" ? "selected" : ""
                        }>Día Completo</option>
                        <option value="0.5" ${
                          valor === "0.5" ? "selected" : ""
                        }>Medio Día</option>
                    </select>
                </td>
                <td>
                    <select class="form-control dia-select" name="dia[]" ${
                      valor === "1" ? "disabled" : ""
                    }>
                        <option value="Completo" ${
                          dia === "Completo" ? "selected" : ""
                        }>Día Completo</option>
                        <option value="AM" ${
                          dia === "AM" ? "selected" : ""
                        }>A.M.</option>
                        <option value="PM" ${
                          dia === "PM" ? "selected" : ""
                        }>P.M.</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger eliminarFecha">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `);
    });

    // Habilitar/deshabilitar selector "Día" al cambiar el valor "Día Completo" o "Medio Día"
    $("#tablaFechasEdit")
      .find(".valor-select")
      .each(function () {
        const valorSelect = $(this);
        valorSelect.on("change", function () {
          const row = valorSelect.closest("tr");
          const diaCell = row.find(".dia-select").parent();

          // Cambiar el selector de días dinámicamente
          if (valorSelect.val() === "1") {
            diaCell.html(`
                  <select class="form-control dia-select" name="dia[]" >
                      <option value="Completo" >Día Completo</option>
                  </select>
              `);
          } else {
            diaCell.html(`
                  <select class="form-control dia-select" name="dia[]">
                      <option value="AM">A.M.</option>
                      <option value="PM">P.M.</option>
                  </select>
              `);
          }
        });
      });
  }

  // Evento para eliminar filas dinámicamente
  $("#tablaFechasEdit").on("click", ".eliminarFila", function () {
    $(this).closest("tr").remove();
  });

  $(document).on("click", ".eliminarFecha", function () {
    const row = $(this).closest("tr"); // Fila actual
    const id_solicitud = $("#edit_id_solicitud").val(); // ID de la solicitud
    const fecha = row.find("input[name='fechas[]']").val(); // Fecha seleccionada

    if (confirm(`¿Estás seguro de eliminar la fecha ${fecha}?`)) {
      $.ajax({
        url: `${base_url}/Vacaciones/eliminarFecha`,
        method: "POST",
        data: {
          id_solicitud: id_solicitud,
          fecha: fecha,
        },
        dataType: "json",
        success: function (response) {
          if (response.status) {
            alert(response.message); // Mensaje de éxito
            row.remove(); // Eliminar la fila de la tabla
          } else {
            alert(response.message); // Mensaje de error
          }
        },
        error: function (error) {
          console.error("Error al eliminar la fecha:", error);
          alert("Ocurrió un error al intentar eliminar la fecha.");
        },
      });
    }
  });

  // --- Actualizar la solicitud ---

  if (document.querySelector("#edit_responsable_aprobacion")) {
    let ajaxUrl = base_url + "/Vacaciones/getSelectAprobador"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#edit_responsable_aprobacion").innerHTML =
          request.responseText;
        $("#edit_responsable_aprobacion").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#edit_id_categoria")) {
    let ajaxUrl = base_url + "/Vacaciones/getSelectCategorias"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#edit_id_categoria").innerHTML =
          request.responseText;
        $("#edit_id_categoria").selectpicker("refresh");
      }
    };
  }

  $("#solicitudVacaciones").on("hidden.bs.modal", function () {
    // Limpiar campos del formulario
    $("#SolicitudForm")[0].reset(); // Reinicia el formulario
    $("#tablaFechas tbody").empty();
  });

  $("#updateSolicitudModal").on("hidden.bs.modal", function () {
    // Limpiar campos del formulario
    $(this).find("form")[0].reset(); // Resetea todos los campos del formulario
    $("#tablaFechasEdit tbody").empty();
  });

  document
    .getElementById("updateSolicitudForm")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      // Recoge los valores de las fechas, valores y días
      const fechas = Array.from(
        document.querySelectorAll("#tablaFechasEdit input[name='fechas[]']")
      ).map((input) => input.value);

      const valores = Array.from(
        document.querySelectorAll("#tablaFechasEdit select[name='valor[]']")
      ).map((select) => select.value);

      const dias = Array.from(
        document.querySelectorAll("#tablaFechasEdit select[name='dia[]']")
      ).map((select) => select.value);

      // Verifica si se han agregado fechas
      if (fechas.length === 0) {
        alert("Debes agregar al menos una fecha.");
        return;
      }

      // Envía la solicitud vía AJAX
      const formData = new FormData(this);

      const spinner = document.querySelector("#spinerUpdate");
      const submitButton = document.querySelector("#btnSubmitUpdate");

      submitButton.disabled = true;
      spinner.classList.remove("d-none");

      fechas.forEach((fecha, index) => {
        formData.append(`fechas[${index}]`, fecha);
        formData.append(`valor[${index}]`, valores[index]);
        formData.append(`dia[${index}]`, dias[index]);
      });

      fetch(base_url + "/Vacaciones/actualizarSolicitud", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status) {
            Swal.fire({
              title: "Éxito",
              text: data.message,
              icon: "success", // Icono de éxito
              confirmButtonText: "OK",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
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
          Swal.fire({
            title: "Error",
            text: "Ocurrió un problema al procesar la solicitud.",
            icon: "error",
            confirmButtonText: "Entendido",
          });
          console.error("Error:", error);
        });
    });

  $("#agregarFechaEdit").on("click", function () {
    $("#tablaFechasEdit tbody").append(`
          <tr>
              <td>
                  <input type="date" class="form-control" name="fechas[]" >
              </td>
              <td>
                  <select class="form-control valor-select" name="valor[]">
                      <option value="1">Día Completo</option>
                      <option value="0.5">Medio Día</option>
                  </select>
              </td>
              <td>
                  <select class="form-control dia-select" name="dia[]">
                      <option value="Completo">Día Completo</option>
                  </select>
              </td>
              <td>
                  <button type="button" class="btn btn-danger eliminarFila">
                      <i class="fas fa-trash-alt"></i>
                  </button>
              </td>
          </tr>
      `);

    // Añadir evento de cambio al nuevo selector de valores
    const nuevaFila = $("#tablaFechasEdit tbody tr").last();
    const valorSelect = nuevaFila.find(".valor-select");
    const diaSelect = nuevaFila.find(".dia-select");

    valorSelect.on("change", function () {
      if (valorSelect.val() === "1") {
        diaSelect.html(`
              <option value="Completo">Día Completo</option>
          `);
        diaSelect.prop("", true);
      } else {
        diaSelect.html(`
              <option value="AM">A.M.</option>
              <option value="PM">P.M.</option>
          `);
        diaSelect.prop("disabled", false);
      }
    });
  });

  //CANCELAAAAAAAAAAAAAAAAAAAAAR

  document
    .querySelector("#formCancelSolicitud")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const comentario = document
        .querySelector("#cancelComentario")
        .value.trim();
      if (!comentario) {
        alert("Debes ingresar una razón de cancelación.");
        return;
      }

      const formData = new FormData(this);
      const spinner = document.querySelector("#spinerCancel");
      const submitButton = document.querySelector("#btnSubmitCancel");

      submitButton.disabled = true;
      spinner.classList.remove("d-none");

      fetch(`${base_url}/Vacaciones/cancelarSolicitud`, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status) {
            Swal.fire({
              title: "Éxito",
              text: data.message,
              icon: "success", // Icono de éxito
              confirmButtonText: "OK",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
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
        .catch((error) => console.error("Error:", error));
    });

  document.addEventListener("click", function (e) {
    if (e.target.closest(".canceled-btn")) {
      const id_solicitud = e.target.closest(".canceled-btn").dataset.id;

      fetch(`${base_url}/Vacaciones/getSolicitudbyID/${id_solicitud}`, {
        method: "GET",
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status) {
            document.querySelector("#id_solicitud").value =
              data.data.id_solicitud;
          } else {
            alert(data.msg);
          }
        })
        .catch((error) => console.error("Error:", error));
    }
  });

  // Cargar los datos

  $(document).on("click", ".cancel-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones/getSolicitudID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#nombre_completo_cancel").val(data.nombre_completo);
          $("#asunto_cancel").val(data.asunto);
          $("#solicitud_cancel").val(data.comentario_solicitud);
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
      url: `${base_url}/Vacaciones/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          $("#jefe_aprobador_cancel").val(data.responsable_aprobador);
          $("#revision_aprobador_1_cancel").val(data.nombre_aprobador_1);

          if (data.responsable_aprobador != null ) {
            // Mostrar el campo
            $("#jefe_aprobador_cancel").closest(".mb-3").show();
            $("#revision_aprobador_1_cancel").closest(".mb-3").hide();
          } else {
            // Ocultar el campo
            $("#jefe_aprobador_cancel").closest(".mb-3").hide();
            $("#revision_aprobador_1_cancel").closest(".mb-3").show();
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



  // Rechazado
  $(document).on("click", ".reject-btn", function () {
    const id_solicitud = $(this).data("id");

    $.ajax({
      url: `${base_url}/Vacaciones/getSolicitudID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_solicitud").val(data.id_solicitud);
          $("#nombre_completo_reject").val(data.nombre_completo);
          $("#nombre_completo_reject_operativa").val(data.nombre_completo);
          $("#asunto_reject").val(data.asunto);
          $("#asunto_reject_operativa").val(data.asunto);
          $("#comentario_solicitud_reject").val(data.comentario_solicitud);
          $("#comentario_respuesta_reject").val(data.comentario_respuesta);

          // Llenar la tabla de fechas
          const tablaFechas = $("#tablaFechasReject tbody");
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
      url: `${base_url}/Vacaciones/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#jefe_aprobador_reject").val(data.responsable_aprobador);
          $("#revision_aprobador_1_reject").val(data.nombre_aprobador_1);
          $("#revision_aprobador_2_reject").val(data.nombre_aprobador_2);
          $("#revision_aprobador_3_reject").val(data.nombre_aprobador_3);

          if (data.responsable_aprobador != null ) {
            // Mostrar el campo
            $("#jefe_aprobador_reject").closest(".mb-3").show();
            $("#revision_aprobador_1_reject").closest(".mb-3").hide();
            $("#revision_aprobador_2_reject").closest(".mb-3").hide();
            $("#revision_aprobador_3_reject").closest(".mb-3").hide();
          } else {
            // Ocultar el campo
            $("#jefe_aprobador_reject").closest(".mb-3").hide();
            $("#revision_aprobador_1_reject").closest(".mb-3").show();
            $("#revision_aprobador_2_reject").closest(".mb-3").show();
            $("#revision_aprobador_3_reject").closest(".mb-3").show();
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
      url: `${base_url}/Vacaciones/getSolicitudID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_solicitud_consumido_operativo").val(data.id_solicitud);
          $("#nombre_completo_consumido").val(data.nombre_completo);
          $("#nombre_completo_consumido_operativa").val(data.nombre_completo);
          $("#asunto_consumido").val(data.asunto);
          $("#asunto_consumido_operativa").val(data.asunto);
          $("#comentario_solicitud_consumido").val(data.comentario_solicitud);
          $("#comentario_solicitud_consumido_operativa").val(data.comentario_solicitud);
          $("#comentario_respuesta_consumido").val(data.comentario_respuesta);

          $("#btnReversionOperativa").data("id", data.id_solicitud);

          // Llenar la tabla de fechas
          const tablaFechasConsumido = $("#tablaFechasConsumido tbody");
          tablaFechasConsumido.empty();
          const fechas = data.fechas.split(","); // Separar fechas
          const valores = data.valores.split(","); // Separar valores
          const dias = data.dias.split(","); // Separar días
          fechas.forEach((fecha, index) => {
            tablaFechasConsumido.append(`
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
      url: `${base_url}/Vacaciones/getJefesbyID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#jefe_aprobador_consumido").val(data.responsable_aprobador);
          $("#revision_aprobador_1_consumido").val(data.nombre_aprobador_1);
          $("#revision_aprobador_2_consumido").val(data.nombre_aprobador_2);
          $("#revision_aprobador_3_consumido").val(data.nombre_aprobador_3);

          if (data.responsable_aprobador != null ) {
            // Mostrar el campo
            $("#jefe_aprobador_consumido").closest(".mb-3").show();
            $("#revision_aprobador_1_consumido").closest(".mb-3").hide();
            $("#revision_aprobador_2_consumido").closest(".mb-3").hide();
            $("#revision_aprobador_3_consumido").closest(".mb-3").hide();
          } else {
            // Ocultar el campo
            $("#jefe_aprobador_consumido").closest(".mb-3").hide();
            $("#revision_aprobador_1_consumido").closest(".mb-3").show();
            $("#revision_aprobador_2_consumido").closest(".mb-3").show();
            $("#revision_aprobador_3_consumido").closest(".mb-3").show();
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

  $(document).on("click", "#btnReversionOperativa", function () {
    const id_solicitud = $(this).data("id");
    $.ajax({
      url: `${base_url}/Vacaciones/getSolicitudID/${id_solicitud}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        if (response.status) {
          const data = response.data;

          // Establecer los valores de los campos en el modal
          $("#id_numero_solicitud_reversion_operativa").val(data.id_solicitud);
          $("#nombre_completo_reversion_operativa").val(data.nombre_completo);
          $("#fecha_solicitud_reversion_operativa").val(data.fecha_solicitud);
          $("#asunto_reversion_operativa").val(data.asunto);
          $("#comentario_solicitud_reversion").val(data.comentario_solicitud);

        } else {
          alert(response.msg);
        }
      },
      error: function (error) {
        console.log("Error:", error);
      },
    });

    // Asignar el ID al modal de reversión (si hay un campo oculto)
    $("#reversionOperativa").find("#id_solicitud_reversion").val(id_solicitud);
  });

    //REVERSION

    document
    .querySelector("#ReversionForm")
    .addEventListener("submit", function (e) {
      e.preventDefault();
  
      const formData = new FormData(this);
      const spinner = document.querySelector("#spinerReversion");
      const submitButton = document.querySelector("#btnSubmitReversion");
  
      submitButton.disabled = true;
      spinner.classList.remove("d-none");
  
      fetch(`${base_url}/Vacaciones/revertirSolicitud`, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status) {
            Swal.fire({
              title: "Éxito",
              text: data.message,
              icon: "success", // Icono de éxito
              confirmButtonText: "OK",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
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
        .catch((error) => console.error("Error:", error));
    });

    $(document).on("click", ".revertido-btn", function () {
      const id_solicitud = $(this).data("id");
  
      $.ajax({
        url: `${base_url}/Vacaciones/getSolicitudbyIDOperativa/${id_solicitud}`,
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
            const tablaFechas = $("#tablaFechasRevertido tbody");
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
  
    $(document).on("click", ".revertido-btn", function () {
      const id_solicitud = $(this).data("id");
  
      $.ajax({
        url: `${base_url}/Vacaciones/getJefesbyID/${id_solicitud}`,
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
  
            if (data.responsable_aprobador != null ) {
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


  // OPERATIVO

  if (document.querySelector("#revision_aprobador_1_operativa")) {
    let ajaxUrl = base_url + "/Vacaciones/getSelectCordinadorArea"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#revision_aprobador_1_operativa").innerHTML =
          request.responseText;
        $("#revision_aprobador_1_operativa").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#id_categoria_operativa")) {
    let ajaxUrl = base_url + "/Vacaciones/getSelectCategorias"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#id_categoria_operativa").innerHTML =
          request.responseText;
        $("#id_categoria_operativa").selectpicker("refresh");
      }
    };
  }

  const tablaFechasOperativa = document
    .getElementById("tablaFechasOperativa")
    .querySelector("tbody");

  // Función para agregar nueva fila
  const agregarFechaOperatiBtn = document.getElementById(
    "agregarFechaOperativa"
  );

  agregarFechaOperatiBtn.addEventListener("click", () => {
    const nuevaFila = document.createElement("tr");
    nuevaFila.innerHTML = `
  <td>
      <input type="date" class="form-control" name="fechas[]" required>
  </td>
  <td>
      <select class="form-control valor-select" name="valor[]" required>
          <option value="1">Día Completo</option>
          <option value="0.5">Medio Día</option>
      </select>
  </td>
  <td>
      <select class="form-control dia-select" name="dia[]" >
          <option value="Completo" selected>Día Completo</option>
      </select>
  </td>
  <td>
      <button type="button" class="btn btn-danger eliminarFila">
          <i class="fas fa-trash-alt"></i>
      </button>
  </td>`;
    tablaFechasOperativa.appendChild(nuevaFila);

    const valorSelect = nuevaFila.querySelector(".valor-select");
    const diaSelect = nuevaFila.querySelector(".dia-select");

    // Evento para manejar el cambio de valor
    valorSelect.addEventListener("change", () => {
      if (valorSelect.value === "1") {
        diaSelect.innerHTML = `
      <option value="Completo" selected>Día Completo</option>
    `;
        diaSelect.prop("", true);
      } else {
        diaSelect.innerHTML = `
      <option value="AM">A.M.</option>
      <option value="PM">P.M.</option>
    `;
        diaSelect.disabled = false;
      }
    });

    // Botón para eliminar fila
    const eliminarBtn = nuevaFila.querySelector(".eliminarFila");
    eliminarBtn.addEventListener("click", () => {
      nuevaFila.remove();
    });
  });

  // --- Enviar formulario ---
  document
    .getElementById("SolicitudOperativaForm")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      // Recoge los valores de las fechas y los días
      const fechas = Array.from(
        document.querySelectorAll("input[name='fechas[]']")
      ).map((input) => input.value);

      const valores = Array.from(
        document.querySelectorAll("select[name='valor[]']")
      ).map((select) => select.value);

      const dias = Array.from(
        document.querySelectorAll("select[name='dia[]']")
      ).map((select) => select.value);

      // Verifica si se han agregado fechas
      if (fechas.length === 0) {
        alert("Debes agregar al menos una fecha.");
        return;
      }

      // Envía la solicitud vía AJAX
      const formData = new FormData(this);
      const spinner = document.querySelector("#spinerSubmitOperativa");
      const submitButton = document.querySelector("#btnSubmitOperativa");

      submitButton.disabled = true;
      spinner.classList.remove("d-none");

      fechas.forEach((fecha, index) => {
        formData.append(`fechas[${index}]`, fecha);
        formData.append(`valor[${index}]`, valores[index]);
        formData.append(`dia[${index}]`, dias[index]);
      });

      fetch(base_url + "/Vacaciones/guardarSolicitudOperativa", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status) {
            Swal.fire({
              title: "Éxito",
              text: data.message,
              icon: "success", // Icono de éxito
              confirmButtonText: "OK",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
            $("#formularioCorporativos").modal("hide");
            tableSolicitud.ajax.reload();
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
          Swal.fire({
            title: "Error",
            text: "Ocurrió un problema al procesar la solicitud.",
            icon: "error",
            confirmButtonText: "Entendido",
          });
          console.error("Error:", error);
        });
    });

  // FINAL
});
