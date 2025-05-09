document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelector("#addUserForm")
    .addEventListener("submit", function (event) {
      event.preventDefault(); // Prevenir el envío normal del formulario

      let formData = new FormData(this); // Crear un FormData con los datos del formulario

      for (let [key, value] of formData.entries()) {
        console.log(key, value); // Mostrar cada campo y su valor
      }

      let ajaxUrl = base_url + "/Info/setInfo"; // URL del controlador para insertar/actualizar datos
      let request = new XMLHttpRequest(); // Crear el objeto XMLHttpRequest

      request.open("POST", ajaxUrl, true);
      request.send(formData);

      request.onreadystatechange = function () {
        if (request.readyState === 4) {
          // Asegurarse de que la solicitud esté completa
          if (request.status === 200) {
            // Verificar que la respuesta sea exitosa
            let response = JSON.parse(request.responseText);
            if (response.status) {
              Swal.fire({
                title: "Operación exitosa",
                text: response.msg,
                icon: "success",
                confirmButtonText: "Aceptar",
              }).then((result) => {
                if (result.isConfirmed) {
                  location.reload(); // Recargar la página al presionar "Aceptar"
                }
              });
            } else {
              Swal.fire({
                title: "Error",
                text: msg,
                icon: "error",
                confirmButtonText: "Entendido",
              }).then((result) => {
                if (result.isConfirmed) {
                  location.reload(); // Recargar la página al presionar "Aceptar"
                }
              });
            }
          } else {
            // Manejo de error en caso de que la solicitud falle
            Swal.fire({
              title: "Error",
              text: "Hubo un problema con la solicitud. Por favor, inténtalo de nuevo.",
              icon: "error",
              confirmButtonText: "Entendido",
            });
          }
        }
      };
    });

    if (document.querySelector("#estudios")) {
      let ajaxUrl = base_url + "/Info/getSelectEstudios"; // Ajusta la URL según tu ruta
      let request = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");
      request.open("GET", ajaxUrl, true);
      request.send();
      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          document.querySelector("#estudios").innerHTML = request.responseText;
          $("#estudios").selectpicker("refresh");
        }
      };
    }

    if (document.querySelector("#id_estudios")) {
      let ajaxUrl = base_url + "/Info/getSelectEstudios"; // Ajusta la URL según tu ruta
      let request = new XMLHttpRequest();
      request.open("GET", ajaxUrl, true);
      request.send();
      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          const select = document.querySelector("#id_estudios");
          const currentValue = select.value; // Guardar el valor actual seleccionado
    
          // Insertar el listado completo de opciones
          select.innerHTML += request.responseText;
    
          // Restaurar el valor seleccionado por defecto
          select.value = currentValue;
    
          // Refrescar el selectpicker
          $("#id_estudios").selectpicker("refresh");
        }
      };
    }

    if (document.querySelector("#id_nivel_educativo")) {
      let ajaxUrl = base_url + "/Info/getSelectNivel"; // Ajusta la URL según tu ruta
      let request = new XMLHttpRequest();
      request.open("GET", ajaxUrl, true);
      request.send();
      request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
          const select = document.querySelector("#id_nivel_educativo");
          const currentValue = select.value; // Guardar el valor actual seleccionado
    
          // Insertar el listado completo de opciones
          select.innerHTML += request.responseText;
    
          // Restaurar el valor seleccionado por defecto
          select.value = currentValue;
    
          // Refrescar el selectpicker
          $("#id_nivel_educativo").selectpicker("refresh");
        }
      };
    }



  // FINAAAL
});
