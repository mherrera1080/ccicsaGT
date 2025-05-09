let tablePersonal;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

document.addEventListener(
  "DOMContentLoaded",
  function () {
    let permisosMod = permisos[10] || {
      leer: 0,
      crear: 0,
      editar: 0,
      eliminar: 0,
    };

    let tablePersonal = $("#TablePersonal").DataTable({
      aProcessing: true,
      aServerSide: true,
      responsive: true,
      scrollX: true,
      language: {
        url: media_url + "/plugins/datatables/Spanish.json",
      },
      ajax: {
        url: base_url + "/Personal/getPersonalNominaBaja",
      },
      columns: [
        { data: "id_empleado" },
        { data: "codigo_empleado" },
        { data: "fecha_ingreso" },
        { data: "apellidos" },
        { data: "nombres" },
        { data: "identificacion" },
        { data: "puesto_contrato" },
        { data: "puesto_operativo" },
        { data: "departamento" },
        { data: "nombre_area" },
        { data: "estado" },
        {
          data: null,
          render: function (data, type, row) {
            let botones = "";
            if (permisosMod.editar == 1) {
              botones += `<button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${row.id_empleado}">
                   <i class="fa-solid fa-repeat"></i>
                  </button>`;
            } else {
              botones += `
            <button button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">
              <i class="fa-solid fa-repeat"></i>
            </button>`;
            }
            return botones;

          },
        }, // Opciones de acciones (Editar/Eliminar)
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

    $("#editModal").on("show.bs.modal", function (event) {
      var button = $(event.relatedTarget); // Botón que disparó el modal
      var id_empleado = button.data("id"); // Extraer información del atributo `data-id`

      var modal = $(this);
      modal.find("#id_empleado").val(id_empleado); // Asignar el ID al campo oculto del formulario
    });

    $(document).on("click", ".edit-btn", function () {
      const id = $(this).data("id");

      $.ajax({
        url: `${base_url}/Personal/getPersonalbyID/${id}`,
        method: "GET",
        dataType: "json",
        success: function (response) {
          if (response.status) {
            $("#id_empleado").val(response.data.id_empleado);
            $("#fecha_ingreso").val(response.data.fecha_ingreso);
            $("#primer_apellido").val(response.data.primer_apellido);
            $("#segundo_apellido").val(response.data.segundo_apellido);
            $("#apellidos").val(response.data.apellidos);
            $("#nombres").val(response.data.nombres);
            $("#identificacion").val(response.data.identificacion);
            $("#puesto_contrato").val(response.data.puesto_contrato);
            $("#puesto_operativo").val(response.data.puesto_operativo);
            $("#departamento").val(response.data.departamento);
            $("#area").val(response.data.area);
            $("#descripcion").val(response.data.descripcion);
          } else {
            alert(response.msg);
          }
        },
        error: function (error) {
          console.log("Error:", error);
        },
      });
    });

    // Manejar el envío del formulario para dar de baja al usuario
    document
      .querySelector("#editForm")
      .addEventListener("submit", function (event) {
        event.preventDefault(); // Prevenir el envío normal del formulario

        let formData = new FormData(this); // Crear un FormData con los datos del formulario
        const spinner = document.querySelector("#spinerSubmit ");
        const submitButton = document.querySelector("#btnSubmit");
        let ajaxUrl = base_url + "/Personal/setRecontratacion"; // URL del controlador para insertar/actualizar datos
        let request = window.XMLHttpRequest
          ? new XMLHttpRequest()
          : new ActiveXObject("Microsoft.XMLHTTP");

          submitButton.disabled = true;
      spinner.classList.remove("d-none");
        request.open("POST", ajaxUrl, true);
        request.send(formData);

        request.onreadystatechange = function () {
          if (request.readyState === 4 && request.status === 200) {
            let response = JSON.parse(request.responseText);
            if (response.status) {
              Swal.fire({
                title: "Operacion Exitosa!",
                text: "Se ha iniciado el Proceso de Recontratacion!",
                icon: "success",
                confirmButtonText: "OK",
              }).then((result) => {
                if (result.isConfirmed) {
                  // Recargar la página al presionar "Aceptar"
                  location.reload();
                }
              });
            } else {
              Swal.fire("Error", response.msg || "Ocurrió un error", "error");
            }
          }
        };
      });

    if (document.querySelector("#area")) {
      let ajaxUrl = base_url + "/Personal/getSelectAreas"; // Ajusta la URL según tu ruta
      let request = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");
      request.open("GET", ajaxUrl, true);
      request.send();
      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          document.querySelector("#area").innerHTML = request.responseText;
          $("#area").selectpicker("refresh");
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
  },
  false
);
