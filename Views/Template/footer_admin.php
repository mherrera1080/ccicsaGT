</body>

<script>
    const media_url = "<?= media(); ?>";
    const base_url = "<?= base_url(); ?>";
</script>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>

<!-- Archivos JavaScript personalizados -->
<script src="<?= media(); ?>/js/<?= $data['page_functions_js']; ?>"></script>
<script src="Assets/js/scripts.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables y Extensiones -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="<?= media(); ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= media(); ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.colVis.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const logoutButton = document.getElementById("logoutButton");

        if (!logoutButton) return;

        logoutButton.addEventListener("click", () => {
            fetch(`${base_url}/Dashboard/logout`)
                .then(response => {
                    if (response.ok) {
                        window.location.href = `${base_url}/login`;
                    } else {
                        showError();
                    }
                })
                .catch(showError);
        });

        function showError() {
            Swal.fire("Atención", "Error al cerrar sesión", "error");
        }
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const logoutButton = document.getElementById("logoutButtonOperativa");

        if (logoutButton) {
            logoutButton.onclick = function () {
                fetch(base_url + '/Dashboard/logoutOperativa')
                    .then(response => {
                        if (response.ok) {
                            window.location.href = base_url + '/Vacaciones/Login';
                        } else {
                            Swal.fire("Atención", "Error al cerrar sesión", "error");
                        }
                    })
                    .catch(() => {
                        Swal.fire("Atención", "Error al cerrar sesión", "error");
                    });
            };
        }
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchEmpleado');
        const resultsContainer = document.getElementById('suggestions');

        // Función para mostrar resultados
        function displayResults(results) {
            resultsContainer.innerHTML = ''; // Limpiar resultados previos
            resultsContainer.style.display = 'none';

            if (results.length > 0) {
                resultsContainer.style.display = 'block';
                results.forEach(employee => {
                    const resultItem = document.createElement('div');
                    resultItem.classList.add('result-item', 'p-2', 'border-bottom');
                    resultItem.textContent = `${employee.nombres} ${employee.apellidos} - ${employee.identificacion}`;

                    // Al hacer clic en el resultado, redirigir al usuario a la página del empleado
                    resultItem.addEventListener('click', () => {
                        window.location.href = `${base_url}/Personal/Perfil/${employee.id_empleado}`;
                    });

                    resultsContainer.appendChild(resultItem);
                });
            } else {
                const noResultItem = document.createElement('div');
                noResultItem.classList.add('result-item', 'p-2');
                noResultItem.textContent = 'No se encontraron resultados.';
                resultsContainer.appendChild(noResultItem);
            }
        }

        // Evento input para detectar cambios en el campo de búsqueda
        searchInput.addEventListener('input', () => {
            const termino = searchInput.value.trim();

            if (termino.length > 2) { // Realizar búsqueda solo si el término tiene más de 2 caracteres
                fetch(`${base_url}/Personal/buscarPersonal`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `termino=${encodeURIComponent(termino)}`
                })
                    .then(response => response.json())
                    .then(data => displayResults(data))
                    .catch(error => console.error('Error:', error));
            } else {
                resultsContainer.innerHTML = '';
                resultsContainer.style.display = 'none';
            }
        });

        // Cerrar el contenedor si el usuario hace clic fuera
        document.addEventListener('click', (e) => {
            if (!resultsContainer.contains(e.target) && e.target !== searchInput) {
                resultsContainer.innerHTML = '';
                resultsContainer.style.display = 'none';
            }
        });
    });

</script>
