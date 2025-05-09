document.addEventListener("DOMContentLoaded", function () {
  // Crear DataTable
  let tableMovimientos = $("#tableMovimientos").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Bitacoras/getMovimientos",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "identificacion" },
      { data: "apellidos" },
      { data: "nombres" },
      { data: "puesto_contrato" },
      { data: "puesto_operativo" },
      { data: "jefe_inmediato" },
      { data: "lider_proceso" },
      { data: "fecha_cambio" },
      { data: "responsable" },
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
      {
        extend: "colvis",
        text: "<i class='fas fa-columns'></i> Columnas",
        titleAttr: "Mostrar/Ocultar Columnas",
        className: "btn btn-primary",
      },
    ],
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "desc"]],
  });
}); // FIN DEL JS
