document.addEventListener("DOMContentLoaded", function () {
  console.log("Perfil cargado correctamente");
});

// Selección de elementos
const dropArea = document.getElementById("dropArea");
const fileInput = document.getElementById("fileInput");
const preview = document.getElementById("preview");

// Mostrar selector de archivos al hacer clic en el área de arrastre
dropArea.addEventListener("click", () => {
  console.log("Área de arrastre clickeada");
  fileInput.click();
});

// Detectar archivos seleccionados
fileInput.addEventListener("change", handleFiles);

// Manejar arrastre y suelte de archivos
dropArea.addEventListener("dragover", (e) => {
  e.preventDefault();
  console.log("Archivo en arrastre sobre área");
  dropArea.classList.add("active");
});

dropArea.addEventListener("dragleave", () => {
  console.log("Archivo dejó el área de arrastre");
  dropArea.classList.remove("active");
});

dropArea.addEventListener("drop", (e) => {
  e.preventDefault();
  console.log("Archivo soltado en el área de arrastre");
  dropArea.classList.remove("active");
  const files = e.dataTransfer.files;
  if (files.length) {
    fileInput.files = files;
    handleFiles();
  }
});

// Mostrar vista previa de la imagen seleccionada
function handleFiles() {
  console.log("Manejando archivos seleccionados");
  const file = fileInput.files[0];
  if (file && file.type.startsWith("image/")) {
    const reader = new FileReader();
    reader.onload = (e) => {
      console.log("Mostrando vista previa");
      preview.src = e.target.result;
      preview.style.display = "block";
    };
    reader.readAsDataURL(file);
  } else {
    alert("Por favor, seleccione un archivo de imagen válido.");
  }
}

// Enviar formulario mediante AJAX
document
  .querySelector("#uploadForm")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    console.log("Formulario de subida enviado");

    const formData = new FormData(this);
    const ajaxUrl = base_url + "/Personal/subirFotoPerfil";
    const request = new XMLHttpRequest();

    request.open("POST", ajaxUrl, true);
    request.send(formData);

    request.onreadystatechange = function () {
      if (request.readyState === 4) {
        console.log("Respuesta recibida");
        if (request.status === 200) {
          const response = JSON.parse(request.responseText);
          console.log("Respuesta JSON:", response);
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
            $("#upIMG").modal("hide"); // Cierra el modal después de cargar la imagen
          } else {
            Swal.fire("Error", response.msg || "Ocurrió un error", "error");
          }
        } else {
          alert("Ocurrió un error al subir la imagen.");
        }
      }
    };
  });

// Rellenar formulario de edición al hacer clic en el botón de edición
$(document).on("click", ".edit-btn", function () {
  const id_empleado = $(this).data("id");
  console.log("Editando empleado con ID:", id_empleado);

  $.ajax({
    url: `${base_url}/Personal/getPersonalbyID/${id_empleado}`,
    method: "GET",
    dataType: "json",
    success: function (response) {
      console.log("Respuesta AJAX de edición:", response);
      if (response.status) {
        $("#id_empleado").val(response.data.id_empleado);
        $("#nombres").val(response.data.nombres);
      } else {
        alert(response.msg);
      }
    },
    error: function (error) {
      console.log("Error en AJAX:", error);
    },
  });
});

if (document.querySelector("#area_laboral")) {
  let ajaxUrl = base_url + "/Personal/getSelectAreas"; // Ajusta la URL según tu ruta
  let request = new XMLHttpRequest();
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      const select = document.querySelector("#area_laboral");
      const currentValue = select.value; // Guardar el valor actual seleccionado

      // Insertar el listado completo de opciones
      select.innerHTML += request.responseText;

      // Restaurar el valor seleccionado por defecto
      select.value = currentValue;

      // Refrescar el selectpicker
      $("#area_laboral").selectpicker("refresh");
    }
  };
}

if (document.querySelector("#puesto_contrato")) {
  let ajaxUrl = base_url + "/Personal/getSelectPuestos"; // Ajusta la URL según tu ruta
  let request = new XMLHttpRequest();
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      const select = document.querySelector("#puesto_contrato");
      const currentValue = select.value; // Guardar el valor actual seleccionado

      // Insertar el listado completo de opciones
      select.innerHTML += request.responseText;

      // Restaurar el valor seleccionado por defecto
      select.value = currentValue;

      // Refrescar el selectpicker
      $("#puesto_contrato").selectpicker("refresh");
    }
  };
}

if (document.querySelector("#puesto_operativo")) {
  let ajaxUrl = base_url + "/Personal/getSelectPuestos"; // Ajusta la URL según tu ruta
  let request = new XMLHttpRequest();
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      const select = document.querySelector("#puesto_operativo");
      const currentValue = select.value; // Guardar el valor actual seleccionado

      // Insertar el listado completo de opciones
      select.innerHTML += request.responseText;

      // Restaurar el valor seleccionado por defecto
      select.value = currentValue;

      // Refrescar el selectpicker
      $("#puesto_operativo").selectpicker("refresh");
    }
  };
}

if (document.querySelector("#lider_proceso")) {
  let ajaxUrl = base_url + "/Personal/getSelectJefesLideres"; // Ajusta la URL según tu ruta
  let request = new XMLHttpRequest();
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      const select = document.querySelector("#lider_proceso");
      const currentValue = select.value; // Guardar el valor actual seleccionado

      // Insertar el listado completo de opciones
      select.innerHTML += request.responseText;

      // Restaurar el valor seleccionado por defecto
      select.value = currentValue;

      // Refrescar el selectpicker
      $("#lider_proceso").selectpicker("refresh");
    }
  };
}

if (document.querySelector("#jefe_inmediato")) {
  let ajaxUrl = base_url + "/Personal/getSelectJefesLideres"; // Ajusta la URL según tu ruta
  let request = new XMLHttpRequest();
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      const select = document.querySelector("#jefe_inmediato");
      const currentValue = select.value; // Guardar el valor actual seleccionado

      // Insertar el listado completo de opciones
      select.innerHTML += request.responseText;

      // Restaurar el valor seleccionado por defecto
      select.value = currentValue;

      // Refrescar el selectpicker
      $("#jefe_inmediato").selectpicker("refresh");
    }
  };
}

if (document.querySelector("#departamento")) {
  let ajaxUrl = base_url + "/Personal/getSelectDepartamento"; // Ajusta la URL según tu ruta
  let request = new XMLHttpRequest();
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState === 4 && request.status === 200) {
      const select = document.querySelector("#departamento");
      const currentValue = select.value; // Guardar el valor actual seleccionado

      // Insertar el listado completo de opciones
      select.innerHTML += request.responseText;

      // Restaurar el valor seleccionado por defecto
      select.value = currentValue;

      // Refrescar el selectpicker
      $("#departamento").selectpicker("refresh");
    }
  };
}

// Manejar el envío del formulario para dar de baja al usuario
document
  .querySelector("#editPersonal")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevenir el envío normal del formulario

    let formData = new FormData(this);
    let ajaxUrl = base_url + "/Personal/updateInfo";
    let request = new XMLHttpRequest();

    request.open("POST", ajaxUrl, true);
    request.send(formData);

    request.onreadystatechange = function () {
      if (request.readyState === 4) {
        if (request.status === 200) {
          let response = JSON.parse(request.responseText);
          if (response.status) {
            
            subirEvaluacionCompetencia(formData);  // Llamar a la función para subir el archivo

          } else {
            Swal.fire("Error", response.msg || "Ocurrió un error", "error");
          }
        } else {
          Swal.fire("Error", "No se pudo completar la solicitud", "error");
        }
      }
    };
  });

function subirEvaluacionCompetencia(formData) {
  let ajaxUrl = base_url + "/Personal/subirEvaluacionCompetencia"; // Controlador para subir el archivo PDF
  let request = new XMLHttpRequest();

  request.open("POST", ajaxUrl, true);
  request.send(formData); // Enviar el archivo junto con los otros datos

  request.onreadystatechange = function () {
    if (request.readyState === 4) {
      if (request.status === 200) {
        let response = JSON.parse(request.responseText);
        if (response.status) {
          Swal.fire({
            title: "Informacion Actualizada!",
            text: "Se ha Actualizado al Empleado Correctamente",
            icon: "success",
          });
        } else {
          Swal.fire(
            "Error",
            response.msg || "Ocurrió un error al subir el archivo",
            "error"
          );
        }
      } else {
        Swal.fire(
          "Error",
          "No se pudo completar la solicitud de archivo",
          "error"
        );
      }
    }
  };
}
