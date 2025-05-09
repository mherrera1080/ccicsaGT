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
