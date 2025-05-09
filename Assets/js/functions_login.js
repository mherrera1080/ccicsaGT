document.addEventListener("DOMContentLoaded", function () {
  const modalEspera = new bootstrap.Modal(
    document.getElementById("modalEspera")
  );

  const tokenForm = document.getElementById("tokenForm");
  const tokenInput = document.getElementById("token");
  const correoInput = document.getElementById("correoToken");
  const btnValidar = document.querySelector(".btn-validar");
  const infoSpinner = document.querySelector("#infoSpinner");

  const infoSubmitBtn = document.getElementById("infoSubmitBtn");
  const tokenSpinner = document.querySelector("#tokenSpinner"); // Asegúrate de agregar un spinner en el HTML

  const formLogin = document.getElementById("formLogin");

  if (formLogin) {
    formLogin.onsubmit = async function (e) {
      e.preventDefault();
      infoSubmitBtn.disabled = true;
      infoSpinner.classList.remove("d-none");

      let correo_empresarial = document.querySelector(
        "#correo_empresarial"
      ).value;
      let password = document.querySelector("#password").value;

      if (correo_empresarial === "" || password === "") {
        Swal.fire("Por favor", "Escribe Correo y Contraseña.", "error");
        return false;
      }

      try {
        const response = await fetch(base_url + "/Login/loginUser", {
          method: "POST",
          body: new FormData(formLogin),
        });

        const objData = await response.json();

        if (objData.status) {
          correoInput.value = correo_empresarial; // 🔥 Guarda el correo en el input oculto
          modalEspera.show(); // Abre el modal
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: objData.msg || "Error en el inicio de sesión",
          });
          infoSubmitBtn.disabled = false;
          infoSpinner.classList.add("d-none");
        }
      } catch (error) {
        Swal.fire("Atención", "Error en el proceso", "error");
      }
    };
  }

  tokenForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    btnValidar.disabled = true;
    tokenSpinner?.classList.remove("d-none");

    try {
      const response = await fetch(base_url + "/Login/validarToken", {
        method: "POST",
        body: new FormData(tokenForm),
        headers: {
          Accept: "application/json",
        },
      });

      if (!response.ok) throw new Error("Error en la respuesta del servidor");

      const data = await response.json();

      if (data.status) {
        Swal.fire({
          icon: "success",
          title: "Acceso permitido",
          text: data.msg,
          timer: 2000,
          showConfirmButton: false,
        }).then(() => {
          modalEspera.hide();
          window.location.href = data.redirect; // Redirección dinámica
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Código incorrecto",
          text: data.msg,
        });
        btnValidar.disabled = false;
        tokenSpinner.classList.add("d-none");
      }
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Error de conexión",
        text: "Hubo un problema al validar el token. Inténtalo de nuevo.",
      });
    } finally {
      btnValidar.disabled = false;
      tokenSpinner.classList.add("d-none");
    }
  });

  // Toggle Password Visibility
  const togglePassword = document.querySelector("#togglePassword");
  const password = document.querySelector("#password");

  togglePassword.addEventListener("click", function () {
    const type =
      password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    this.classList.toggle("fa-eye-slash");
  });
});
