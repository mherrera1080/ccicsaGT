document.addEventListener(
  "DOMContentLoaded",
  function () {
    // Crear DataTable
    let tableReclutamiento = $("#tableReclutamiento").DataTable({
      aProcessing: true,
      aServerSide: true,
      responsive: true,
      scrollX: true,
      language: {
        url: media_url + "/plugins/datatables/Spanish.json",
      },
      ajax: {
        url: base_url + "/Aprobacion/getAprobaciones",
      },
      columns: [
        {
          data: null,
          render: function (data, type, row, meta) {
            // Mostrar el número de ítem (índice + 1)
            return meta.row + 1;
          },
        },
        { data: "fecha_ingreso" },
        { data: "apellidos" },
        { data: "nombres" },
        { data: "identificacion" },
        { data: "puesto_contrato" },
        { data: "puesto_operativo" },
        { data: "departamento" },
        { data: "area_laboral" },
        { data: "aprobacion" },
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

    // Manejar el clic en el botón de editar para mostrar los datos
    $(document).on("click", ".edit-btn", function () {
      const idEmpresa = $(this).data("id");

      $.ajax({
        url: `${base_url}/Aprobacion/getAprobacionesbyID/${idEmpresa}`,
        method: "GET",
        dataType: "json",
        success: function (response) {
          if (response.status) {
            $("#id_aprobaciones").val(response.data.id_aprobaciones);
            $("#fecha_ingreso").val(response.data.fecha_ingreso);
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

    $("#editModal").on("show.bs.modal", function () {
      $("#editForm")[0].reset(); // Reinicia el formulario
    });

    document
      .getElementById("editForm")
      .addEventListener("submit", function (event) {
        event.preventDefault(); // Evita el envío del formulario por defecto

        // Obtiene el valor del botón que fue presionado
        let action = event.submitter.value;

        // Determina la URL de destino según la acción
        let ajaxUrl =
          action === "no_approve"
            ? base_url + "/Aprobacion/setRechazoEmpleado"
            : base_url + "/Aprobacion/setAprobarEmpleado";

        // Crea el objeto FormData a partir del formulario
        let formData = new FormData(this);
        let request = window.XMLHttpRequest
          ? new XMLHttpRequest()
          : new ActiveXObject("Microsoft.XMLHTTP");

        // Configura y envía la solicitud AJAX
        request.open("POST", ajaxUrl, true);
        request.send(formData);

        // Maneja la respuesta del servidor
        request.onreadystatechange = function () {
          if (request.readyState === 4 && request.status === 200) {
            let response = JSON.parse(request.responseText);
            alert(response.msg);
            if (response.status) {
              $("#editModal").modal("hide");
              tableReclutamiento.ajax.reload();
            }
          }
        };
      });

    if (document.querySelector("#area")) {
      let ajaxUrl = base_url + "/Aprobacion/getAreas_laborales"; // Ajusta la URL según tu ruta
      let request = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");
      request.open("GET", ajaxUrl, true);
      request.send();
      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          document.querySelector("#area").innerHTML =
            request.responseText;
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

    document
      .querySelector("#addUserForm")
      .addEventListener("submit", function (event) {
        event.preventDefault(); // Prevenir el envío normal del formulario

        let formData = new FormData(this); // Crear un FormData con los datos del formulario
        let ajaxUrl = base_url + "/Aprobacion/setReclutamiento"; // URL del controlador para insertar/actualizar datos

        let request = new XMLHttpRequest();
        request.open("POST", ajaxUrl, true);
        request.send(formData);

        request.onreadystatechange = function () {
          if (request.readyState === 4 && request.status === 200) {
            let response = JSON.parse(request.responseText);

            if (response.status) {
              Swal.fire({
                title: "Datos guardados correctamente",
                icon: "success",
                confirmButtonText: "Aceptar",
              }).then((result) => {
                if (result.isConfirmed) {
                  // Recargar la página al presionar "Aceptar"
                  location.reload();
                }
              });
            } else {
              Swal.fire("Atención", response.msg, "error"); // Mostrar mensaje de error
            }
          } else if (request.readyState === 4) {
            // Si el estado es 4 pero no es 200, manejar el error
            Swal.fire("Error", "Hubo un problema con el servidor", "error");
          }
        };
        request.onerror = function () {
          Swal.fire(
            "Error",
            "No se pudo establecer conexión con el servidor",
            "error"
          );
        };
      });

    $("#addUserModal").on("show.bs.modal", function () {
      // Limpiar campos del formulario
      $("#addUserForm")[0].reset(); // Reinicia el formulario
      $("#id_empleado").val(""); // Asegúrate de que el campo oculto esté vacío
    });
  },
  false
);
