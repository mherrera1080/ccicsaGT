let tablePersonal;

document.addEventListener("DOMContentLoaded", function () {

  let permisosMod = permisos[3] || { leer: 0, crear: 0, editar: 0, eliminar: 0 };
  // Inicializar DataTable
  tablePersonal = $("#tablePersonal").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    scrollX: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Personal/getPersonalNominaAlta",
      error: function (xhr, error, thrown) {
        console.error("Error al cargar datos:", error, thrown);
      },
    },
    columns: [
      { data: "id_empleado" },
      { data: "codigo_empleado" },
      { data: "fecha_ingreso" },
      { data: "apellidos" },
      { data: "nombres" },
      { data: "identificacion" },
      { data: "correo_empresarial" },
      { data: "puesto_contrato" },
      { data: "puesto_operativo" },
      { data: "departamento" },
      { data: "area_laboral" },
      {
        data: null,
        render: function (data, type, row) {
            let botones = "";

                botones += `<button class="btn btn-dark btn-sm" onclick="window.location.href='${base_url}/Personal/Avance/${row.id_empleado}'">
                                <i class="fas fa-user"></i>
                            </button>`;

            if (permisosMod.editar == 1) {
                botones += `<button class="btn btn-warning btn-sm" onclick="window.location.href='${base_url}/Personal/Info/${row.id_empleado}'">
                                <i class="fas fa-user-pen"></i>
                            </button>`;
            }

            if (permisosMod.eliminar == 1) {
                botones += `<button class="btn btn-danger btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#exampleModal" data-id="${row.id_empleado}">
                                <i class="fas fa-person-arrow-down-to-line"></i>
                            </button>`;
            }

            return botones;
        },
    },
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
    iDisplayLength: 25,
    order: [[0, "desc"]],
  });

  // Cargar opciones de "Razón de Baja" en el select
  if (document.querySelector("#razon_baja")) {
    let ajaxUrl = base_url + "/Personal/getSelectBaja";
    let request = new XMLHttpRequest();
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#razon_baja").innerHTML = request.responseText;
        $("#razon_baja").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#linea_negocio")) {
    let ajaxUrl = base_url + "/Personal/getSelectlinea_negocio"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#linea_negocio").innerHTML =
          request.responseText;
        $("#linea_negocio").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#puesto_contrato")) {
    let ajaxUrl = base_url + "/Personal/getSelectPuestos"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#puesto_contrato").innerHTML =
          request.responseText;
        $("#puesto_contrato").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#puesto_operativo")) {
    let ajaxUrl = base_url + "/Personal/getSelectPuestos"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#puesto_operativo").innerHTML =
          request.responseText;
        $("#puesto_operativo").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#lider_proceso")) {
    let ajaxUrl = base_url + "/Personal/getSelectJefesLideres"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#lider_proceso").innerHTML =
          request.responseText;
        $("#lider_proceso").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#jefe_inmediato")) {
    let ajaxUrl = base_url + "/Personal/getSelectJefesLideres"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#jefe_inmediato").innerHTML =
          request.responseText;
        $("#jefe_inmediato").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#departamento")) {
    let ajaxUrl = base_url + "/Personal/getSelectDepartamento"; // Ajusta la URL según tu ruta
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#departamento").innerHTML =
          request.responseText;
        $("#departamento").selectpicker("refresh");
      }
    };
  }

  // Captura el evento de apertura del modal y asigna el ID al campo oculto
  $("#exampleModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id_empleado = button.data("id");
    var modal = $(this);
    modal.find("#id_empleado").val(id_empleado);
  });

  // Manejar el envío del formulario para dar de baja al usuario
  document
    .querySelector("#AddBajaForm")
    .addEventListener("submit", function (event) {
      event.preventDefault(); // Prevenir el envío normal del formulario

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Personal/setBajaUsuario";
      let request = new XMLHttpRequest();

      request.open("POST", ajaxUrl, true);
      request.send(formData);

      request.onreadystatechange = function () {
        if (request.readyState === 4) {
          if (request.status === 200) {
            let response = JSON.parse(request.responseText);
            if (response.status) {
              Swal.fire({
                title: "Operación Exitosa!",
                text: "Se ha dado de baja al usuario",
                icon: "success",
              });
              $("#exampleModal").modal("hide");
              tablePersonal.ajax.reload();
            } else {
              Swal.fire("Error", response.msg || "Ocurrió un error", "error");
            }
          } else {
            Swal.fire("Error", "No se pudo completar la solicitud", "error");
          }
        }
      };
    });
});
