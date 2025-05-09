let TableBackups;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

document.addEventListener("DOMContentLoaded", function () {
  TableBackups = $("#TableBackups").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Sistemas/registroBackUps",
    },
    columns: [
      { data: "id" },
      { data: "nombre_archivo" },
      { data: "peso" },
      { data: "ruta" },
      { data: "fecha_creacion" }, // ocultamos esta columna en la tabla
      { data: "usuario" },
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

  document
    .getElementById("formBackup")
    .addEventListener("submit", function (e) {
      e.preventDefault();

      const form = e.target;
      const formData = new FormData(form);

      fetch(base_url + "/Config/generar_backup.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((result) => {
          try {
            const json = JSON.parse(result);
            if (json.status) {
              Swal.fire("Éxito", json.msg, "success");
              $("#exampleModal").modal("hide");
              TableBackups.ajax.reload();
            } else {
              Swal.fire("Error", json.msg, "error");
            }
          } catch (e) {
            // Si no es JSON, muestra como texto plano
            Swal.fire("Aviso", result, "info");
          }
        })
        .catch((error) => {
          Swal.fire("Error", "Error al enviar la solicitud.", "error");
          console.error("Error:", error);
        });
    });

  // FIN DEL SCRIPT
});
