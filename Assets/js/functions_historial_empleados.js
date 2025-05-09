
document.addEventListener("DOMContentLoaded", function () {
  // Crear DataTable
  let tableHistorial = $("#tableHistorial").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Bitacoras/getHistorial",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          // Mostrar el número de ítem (índice + 1)
          return meta.row + 1;
        },
      },
      { data: "apellidos" },
      { data: "nombres" },
      { data: "fecha_alta" },
      { data: "salario_inicial" },
      { data: "puesto_contrato_inicio" },
      { data: "puesto_operativo_inicio" },
      { data: "departamento_inicio" },
      { data: "fecha_baja" },
      { data: "fecha_salida" },
      { data: "salario_final" },
      { data: "puesto_contrato_final" },
      { data: "puesto_operativo_final" },
      { data: "departamento_final" },
      { data: "caso" },
      { data: "razon_baja" },
      { data: "comentario" },
      { data: "observaciones" },
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
    layout: {
      bottomEnd: {
        paging: {
          firstLast: false,
        },
      },
    },
    bDestroy: true,
    iDisplayLength: 25,
    order: [[0, "desc"]],
  });
}); // FIN DEL JS
