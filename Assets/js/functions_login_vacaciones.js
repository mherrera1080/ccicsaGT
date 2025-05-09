document.addEventListener("DOMContentLoaded", function () {
    const emailModal = new bootstrap.Modal(document.getElementById("emailModal"), {
        backdrop: "static",
        keyboard: false,
    });
    const tokenModal = new bootstrap.Modal(document.getElementById("tokenModal"), {
        backdrop: "static",
        keyboard: false,
    });

    const emailForm = document.getElementById("emailForm");
    const tokenForm = document.getElementById("tokenForm");
    const emailInput = document.getElementById("correo_empresarial");
    const correoTokenInput = document.getElementById("correoToken");
    const emailBtn = document.getElementById("emailSubmitBtn");
    const emailSpinner = document.getElementById("emailSpinner");
    const tokenBtn = document.getElementById("tokenSubmitBtn");
    const tokenSpinner = document.getElementById("tokenSpinner");

    // Mostrar modal de correo al cargar la página
    emailModal.show();

    // Enviar token al correo
    emailForm.addEventListener("submit", async function (e) {
        e.preventDefault();
        emailBtn.disabled = true;
        emailSpinner.classList.remove("d-none");

        try {
            const response = await fetch(base_url + "/vacaciones/enviarCorreoToken", {
                method: "POST",
                body: new FormData(emailForm),
            });

            const data = await response.json();

            if (data.status) {
                Swal.fire({
                    icon: "success",
                    title: "Código Enviado",
                    text: data.msg,
                    timer: 2500,
                    showConfirmButton: false
                });
                correoTokenInput.value = emailInput.value;
                emailModal.hide();
                tokenModal.show();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.msg
                });
            }
        } catch (error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Error de conexión",
                text: "Hubo un problema al enviar el correo. Inténtalo de nuevo."
            });
        } finally {
            emailBtn.disabled = false;
            emailSpinner.classList.add("d-none");
        }
    });

    // Validar el token
    tokenForm.addEventListener("submit", async function (e) {
        e.preventDefault();
        tokenBtn.disabled = true;
        tokenSpinner.classList.remove("d-none");

        try {
            const response = await fetch(base_url + "/vacaciones/validarToken", {
                method: "POST",
                body: new FormData(tokenForm),
            });

            const data = await response.json();

            if (data.status) {
                Swal.fire({
                    icon: "success",
                    title: "Acceso permitido",
                    text: data.msg,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    tokenModal.hide();
                    window.location.href = base_url + "/vacaciones/Solicitud";
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Código incorrecto",
                    text: data.msg
                });
            }
        } catch (error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Error de conexión",
                text: "Hubo un problema al validar el token. Inténtalo de nuevo."
            });
        } finally {
            tokenBtn.disabled = false;
            tokenSpinner.classList.add("d-none");
        }
    });
});
