let TableRegistros;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

document.addEventListener("DOMContentLoaded", function () {
  TableRegistros = $("#TableRegistros").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Sistemas/registroActividad",
    },
    columns: [
      { data: "id_actividad" },
      { data: "no_empleado" },
      { data: "empleado" },
      { data: "modulo" },
      { data: "fecha" },
      { data: "ip" },
      { data: "hostname" },
      { data: "accion", visible: false }, // ocultamos esta columna en la tabla
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

  // Evento para expandir/cerrar detalles
  $("#TableRegistros tbody").on("click", "tr", function () {
    let tr = $(this);
    let row = TableRegistros.row(tr);

    if (row.child.isShown()) {
      // Si está abierto, se cierra
      row.child.hide();
      tr.removeClass("shown");
    } else {
      // Si está cerrado, se abre con el contenido de "accion"
      let data = row.data();
      row
        .child(
          '<div class="p-2"><strong>Acción realizada:</strong><br>' +
            data.accion +
            "</div>"
        )
        .show();
      tr.addClass("shown");
    }
  });
});
