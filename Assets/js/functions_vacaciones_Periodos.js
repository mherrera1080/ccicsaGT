let tableVacaciones;

document.addEventListener("DOMContentLoaded", function () {
  // Inicializar DataTable con todos los periodos al cargar la página
  tableVacaciones = $("#tableVacaciones").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
      emptyTable: "No hay solicitudes registradas.",
    },
    ajax: {
      url: base_url + "/Vacaciones_Revision/getAllPeriodos",
      type: "POST",
      data: function (d) {
        // Aquí enviamos el id_empleado (vacío inicialmente, cuando no hay selección)
        d.id_empleado = $("#id_empleado").val(); // Enviar id_empleado como parámetro
      },
    },
    columns: [
      { data: null, render: (data, type, row, meta) => meta.row + 1 },
      { data: "nombre_completo" },
      { data: "fecha_inicio" },
      { data: "fecha_fin" },
      { data: "dias_totales" },
      { data: "dias_consumidos" },
      { data: "dias_disponibles" },
    ],
    dom: "B",
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
    destroy: true,
    pageLength: 50,
    order: [[0, "desc"]],
    ordering: false,
    paging: false,
  });

  // In your Javascript (external .js resource or <script> tag)
  $(document).ready(function () {
    $(".js-example-basic-single").select2();
  });

  if (document.querySelector("#id_empleado")) {
    let ajaxUrl = base_url + "/Vacaciones_Revision/getSelectEmpleado";
    let request = new XMLHttpRequest();
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#id_empleado").innerHTML = request.responseText;
        $("#id_empleado").selectpicker("refresh");
      }
    };
  }

  // Capturar el evento de envío del formulario
  $("#formFiltrar").on("submit", function (e) {
    e.preventDefault(); // Evita que la página se recargue

    let idEmpleado = $("#id_empleado").val();

    if (idEmpleado) {
      // Recargar DataTable con el ID del empleado
      tableVacaciones.ajax.reload(); // Recargar la tabla con el nuevo filtro de id_empleado
    }
  });
});
