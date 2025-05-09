document.addEventListener("DOMContentLoaded", function () {
  // Crear DataTable
  let permisosMod = permisos[8] || {
    leer: 0,
    crear: 0,
    editar: 0,
    eliminar: 0,
  };
  let tableRoles = $("#tableRoles").DataTable({
    aProcessing: true,
    aServerSide: true,
    responsive: true,
    language: {
      url: media_url + "/plugins/datatables/Spanish.json",
    },
    ajax: {
      url: base_url + "/Roles/getRoles",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1; // Mostrar el número de ítem (índice + 1)
        },
      },
      { data: "role_name" },
      {
        data: null,
        render: function (data, type, row) {
          let botones = "";
          if (permisosMod.editar == 1) {
            botones += `            <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="${row.id}">
                <i class="fas fa-pencil-square"></i>
            </button>`;
          } else {
            botones += `
          <button button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fas fa-pencil-square"></i>
          </button>`;
          }

          return botones;
        },
      },
    ],
    dom: "frtip",
    bDestroy: true,
    iDisplayLength: 10,
    order: [[0, "asc"]],
  });

  // Manejar el envío del formulario de crear rol
  document
    .querySelector("#createForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      let ajaxUrl = base_url + "/Roles/setRol";
      let request = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");

      request.open("POST", ajaxUrl, true);
      request.send(formData);

      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          let response = JSON.parse(request.responseText);
          if (response.status) {
            Swal.fire({
              title: "Operación Exitosa!",
              text: response.msg,
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then((result) => {
              if (result.isConfirmed) {
                // Recargar la página al presionar "Aceptar"
                location.reload();
              }
            });
            $("#createModal").modal("hide");
          } else {
            alert(response.msg);
          }
        }
      };
    });

  $("#createModal").on("show.bs.modal", function () {
    $("#createForm")[0].reset(); // Reinicia el formulario
    $("#id").val(""); // Asegúrate de que el campo oculto esté vacío
    $(".modal-title").text("Añadir Rol"); // Opcional: Cambia el título del modal
  });

  // Manejar el clic en el botón de editar para mostrar los datos
  $(document).on("click", ".edit-btn", function () {
    const id = $(this).data("id");
  
    $.ajax({
      url: `${base_url}/Roles/getRolbyID/${id}`,
      method: "GET",
      dataType: "json",
      success: function (response) {
        console.log(response); // Verificar la respuesta en la consola
        if (response.status) {
          $("#edit_id").val(response.data[0].id);
          $("#edit_role_name").val(response.data[0].role_name);
          // Limpiar la tabla antes de llenarla
          $("#editForm").empty();
          // Iterar sobre los módulos y permisos para llenar la tabla
          response.data.forEach(function (item) {
            // Llamamos a la función para obtener los checkboxes deshabilitados
            const { isDisabledCrear, isDisabledEditar, isDisabledEliminar, isDisabledLeer } = disableActionsBasedOnModule(item);
  
            const row = `
              <tr>
                  <td>${item.nombre}</td>
                  <td><input type="checkbox" ${item.crear == "1" ? "checked" : ""} ${isDisabledCrear} /></td>
                  <td><input type="checkbox" ${item.leer == "1" ? "checked" : ""} ${isDisabledLeer} /></td>
                  <td><input type="checkbox" ${item.editar == "1" ? "checked" : ""} ${isDisabledEditar} /></td>
                  <td><input type="checkbox" ${item.eliminar == "1" ? "checked" : ""} ${isDisabledEliminar} /></td>
              </tr>`;
            $("#editForm").append(row);
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
  
  function disableActionsBasedOnModule(item) {
    let isDisabledCrear = "";
    let isDisabledEditar = "";
    let isDisabledEliminar = "";
    let isDisabledLeer = "";
  
    // Bloquear acciones específicas para modulo_id === 1
    if (item.modulo_id == 1) {
      isDisabledCrear = "disabled";  // Bloquear "crear"
      isDisabledEditar = "disabled"; // Bloquear "editar"
      isDisabledEliminar = "disabled"; // Bloquear "eliminar"
    }
    if (item.modulo_id == 2) {
      isDisabledCrear = "disabled";  // Bloquear "crear"
      isDisabledEliminar = "disabled"
    }
    if (item.modulo_id == 3) {
      isDisabledCrear = "disabled";
    }
    if (item.modulo_id == 4) {
      isDisabledEliminar = "disabled";
    }
    if (item.modulo_id == 5) {
      isDisabledCrear = "disabled";  // Bloquear "crear"
      isDisabledEliminar = "disabled"
    }
    if (item.modulo_id == 6) {
      isDisabledCrear = "disabled";  // Bloquear "crear"
      isDisabledEliminar = "disabled"
    }
    if (item.modulo_id == 8) {
      isDisabledEliminar = "disabled"
    }
    if (item.modulo_id == 9) {
      isDisabledEditar = "disabled"
      isDisabledEliminar = "disabled"
    }
    if (item.modulo_id == 10) {
      isDisabledCrear = "disabled"
      isDisabledEliminar = "disabled"
    }
    if (item.modulo_id == 11) {
      isDisabledEditar = "disabled"
    }
    if (item.modulo_id == 12) {
      isDisabledCrear = "disabled"
    }
    if (item.modulo_id == 14) {
      isDisabledCrear = "disabled";  // Bloquear "crear"
      isDisabledEditar = "disabled"; // Bloquear "editar"
      isDisabledEliminar = "disabled"; // Bloquear "eliminar"
    }
    if (item.modulo_id == 15) {
      isDisabledCrear = "disabled";  // Bloquear "crear"
      isDisabledEditar = "disabled"; // Bloquear "editar"
      isDisabledEliminar = "disabled"; // Bloquear "eliminar"
    }
    if (item.modulo_id == 16) {
      isDisabledCrear = "disabled";  // Bloquear "crear"
      isDisabledEditar = "disabled"; // Bloquear "editar"
      isDisabledEliminar = "disabled"; // Bloquear "eliminar"
    }
    if (item.modulo_id == 17) {
      isDisabledCrear = "disabled";  // Bloquear "crear"
      isDisabledEditar = "disabled"; // Bloquear "editar"
      isDisabledEliminar = "disabled"; // Bloquear "eliminar"
    }
    if (item.modulo_id == 18) {
      isDisabledCrear = "disabled";  // Bloquear "crear"
      isDisabledEditar = "disabled"; // Bloquear "editar"
      isDisabledEliminar = "disabled"; // Bloquear "eliminar"
    }
    if (item.modulo_id == 19) {
      isDisabledCrear = "disabled";  // Bloquear "crear"
      isDisabledEditar = "disabled"; // Bloquear "editar"
      isDisabledEliminar = "disabled"; // Bloquear "eliminar"
    }
  
    return {
      isDisabledCrear,
      isDisabledEditar,
      isDisabledEliminar,
      isDisabledLeer
    };
  }
  
  

  // Manejar el envío del formulario de editar permisos
  $(document).on("click", "#submit", function () {
    const idRol = $("#edit_id").val(); // Obtener el ID del rol
    const permisos = []; // Crear un array para almacenar los permisos

    // Iterar sobre cada fila de permisos
    $("#editForm tr").each(function () {
      const moduloNombre = $(this).find("td").first().text(); // Obtener el nombre del módulo
      const crear = $(this).find("input[type='checkbox']").eq(0).is(":checked")
        ? 1
        : 0;
      const leer = $(this).find("input[type='checkbox']").eq(1).is(":checked")
        ? 1
        : 0;
      const editar = $(this).find("input[type='checkbox']").eq(2).is(":checked")
        ? 1
        : 0;
      const eliminar = $(this)
        .find("input[type='checkbox']")
        .eq(3)
        .is(":checked")
        ? 1
        : 0;

      permisos.push({ moduloNombre, crear, leer, editar, eliminar });
    });

    // Enviar los datos al servidor
    fetch(`${base_url}/Roles/updatePermissions`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ idRol, permisos }), // Enviar los datos como JSON
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          Swal.fire({
            title: "Operación Exitosa!",
            text: data.msg,
            icon: "success",
            confirmButtonText: "Aceptar",
          });
          // .then((result) => {
          //   if (result.isConfirmed) {
          //     // Recargar la página al presionar "Aceptar"
          //     location.reload();
          //   }
          // });
        } else {
          alert(data.msg);
        }
      })
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          // Limpiar la tabla antes de llenarla
          $("#editForm").empty();

          // Iterar sobre los módulos y permisos para llenar la tabla
          data.data.forEach(function (item) {
            const row = `
            <tr>
                <td>${item.nombre}</td>
                <td><input type="checkbox" 
                ${item.crear ? "checked" : ""} /></td>
                <td><input type="checkbox" 
                ${item.leer ? "checked" : ""} /></td>
                <td><input type="checkbox" 
                ${item.editar ? "checked" : ""} /></td>
                <td><input type="checkbox" ${
                  item.eliminar ? "checked" : ""
                } /></td>
            </tr>`;
            $("#editForm").append(row);
          });
        }
      })
      .catch((error) => console.log("Error:", error));
  });

  // Manejar el envío del formulario de editar rol
});
