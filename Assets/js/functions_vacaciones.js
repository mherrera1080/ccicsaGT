let tableVacaciones;

document.addEventListener("DOMContentLoaded", function () {

  tableVacaciones = $("#tableVacaciones").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Vacaciones/getSolicitudesbyID",
      error: function (xhr, error, thrown) {
        console.error("Error al cargar datos:", error, thrown);
      },
    },
    columns: [
      { data: "solicitud_id" },
      { data: "fecha_solicitud" },
      { data: "responsable_aprobacion" },
      { data: "estado" },
      {
        data: "estado",
        render: function (data, type, row) {
          if (!data || data.trim() === "Pendiente") {
            return `
                <button type="button" class="btn btn-warning update-btn" data-bs-toggle="modal" data-bs-target="#updateSolicitudModal" data-id="${row.solicitud_id}">
                    <i class="fas fa-pencil-square"></i>
                </button>
                <button type="button" class="btn btn-danger canceled-btn" data-bs-toggle="modal" data-bs-target="#canceledSolicitudModal" data-id="${row.solicitud_id}">
                    <i class="fas fa-times-circle"></i>
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
});
