document.addEventListener("DOMContentLoaded", function () {
  // Realizamos la llamada AJAX al controlador

  let chartInstanceRH = null;

  const ctx = document.getElementById("rhBAR")?.getContext("2d");
  function formatMonthLabels(labels) {
    return labels.map((label) => {
      const date = new Date(label + "-01"); // Convertir a fecha
      const formattedDate = new Intl.DateTimeFormat("es-ES", {
        month: "long",
        year: "numeric",
      })
        .format(date)
        .replace(" de", ""); // Eliminar "de"

      // Convertir la primera letra a mayúscula
      return formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1);
    });
  }

  function fetchChartData(url) {
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          const formattedLabels = formatMonthLabels(data.data.labels);
          const chartConfig = {
            type: "bar",
            data: {
              labels: formattedLabels, // Etiquetas traducidas
              datasets: [
                {
                  label: "Nuevos Ingresos",
                  data: data.data.contrataciones,
                  backgroundColor: "rgba(75, 192, 192, 0.7)",
                },
                {
                  label: "Bajas",
                  data: data.data.bajas,
                  backgroundColor: "rgba(255, 99, 132, 0.7)",
                },
                {
                  label: "Recontrataciones",
                  data: data.data.recontrataciones,
                  backgroundColor: "rgba(153, 102, 255, 0.7)",
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: true, position: "top" },
                title: { display: true, text: "Movimiento de Colaboradores" },
              },
              scales: {
                y: {
                  beginAtZero: true,
                  title: { display: true, text: "Cantidad de Movimientos" },
                  ticks: { stepSize: 1, max: 50 },
                },
                x: { stacked: false },
              },
              interaction: { mode: "index" },
            },
          };

          // Destruir gráfico anterior si existe
          if (chartInstanceRH) {
            chartInstanceRH.destroy();
          }

          // Crear nuevo gráfico
          if (ctx) {
            chartInstanceRH = new Chart(ctx, chartConfig);
          }
        } else {
          console.warn("No se encontraron datos para el gráfico.");
        }
      })
      .catch((error) => console.error("Error fetching data:", error));
  }

  // Cargar datos del año actual al inicio
  fetchChartData(base_url + "/Dashboard/barColaboradores");

  // Manejar cambio de año en el formulario
  document
    .getElementById("filtroAnio")
    ?.addEventListener("submit", function (event) {
      event.preventDefault();
      const anioSeleccionado = document.getElementById("anio")?.value;
      fetchChartData(base_url + "/Dashboard/barRH?anio=" + anioSeleccionado);
    });

  let chartInstanceVacaciones = null;
  const vacaciones = document.getElementById("vacacionesBar")?.getContext("2d");
  function solicitudesVacacionesData(url) {
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (data.status) {
          const formattedLabels = formatMonthLabels(data.data.labels);
          const chartConfig = {
            type: "bar",
            data: {
              labels: formattedLabels, // Etiquetas traducidas
              datasets: [
                {
                  label: "Pendientes",
                  data: data.data.pendientes,
                  backgroundColor: "rgba(255, 237, 23, 0.8)",
                },
                {
                  label: "Aprobadas",
                  data: data.data.aprobaciones,
                  backgroundColor: "rgba(71, 210, 14, 0.8)",
                },
                {
                  label: "Rechazado",
                  data: data.data.rechazadas,
                  backgroundColor: "rgba(230, 7, 7, 0.8)",
                },
                {
                  label: "Cancelado",
                  data: data.data.canceladas,
                  backgroundColor: "rgba(255, 8, 160, 0.8)",
                },
                {
                  label: "Revertido",
                  data: data.data.revertidas,
                  backgroundColor: "rgba(231, 0, 255, 1)",
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: true, position: "top" },
                title: { display: true, text: "Solicitud de Vacaciones" },
              },
              scales: {
                y: {
                  beginAtZero: true,
                  title: { display: true, text: "No. de Solicitudes" },
                  ticks: { stepSize: 1, max: 30 },
                },
                x: { stacked: false },
              },
              interaction: { mode: "index" },
            },
          };

          // Destruir gráfico anterior si existe
          if (chartInstanceVacaciones) {
            chartInstanceVacaciones.destroy();
          }

          // Crear nuevo gráfico
          if (vacaciones) {
            chartInstanceVacaciones = new Chart(vacaciones, chartConfig);
          }
        } else {
          console.warn("No se encontraron datos para el gráfico.");
        }
      })
      .catch((error) => console.error("Error fetching data:", error));
  }

  solicitudesVacacionesData(base_url + "/Dashboard/barSolicitudesVacaciones");

  document
    .getElementById("solicitudAño")
    ?.addEventListener("submit", function (event) {
      event.preventDefault();
      const anioSeleccionado = document.getElementById("anio")?.value;
      solicitudesVacacionesData(
        base_url + "/Dashboard/barVacacionesYear?anio=" + anioSeleccionado
      );
    });

  if (document.querySelector("#anio")) {
    let ajaxUrl = base_url + "/Dashboard/getSelectAnio";
    let request = new XMLHttpRequest();
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#anio").innerHTML = request.responseText;
        $("#anio").selectpicker("refresh");
      }
    };
  }

  if (document.querySelector("#anio_vacaciones")) {
    let ajaxUrl = base_url + "/Dashboard/getSelectAnioVacaciones";
    let request = new XMLHttpRequest();
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#anio_vacaciones").innerHTML =
          request.responseText;
        $("#anio_vacaciones").selectpicker("refresh");
      }
    };
  }

  let chartInstanceBajas = null;
  const bajas = document.getElementById("bajasPie")?.getContext("2d");
  function bajasColaboradoresData(url) {
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (chartInstanceBajas) {
          chartInstanceBajas.destroy(); // Siempre destruir el gráfico anterior
        }

        if (data.status && data.data.labels.length > 0) {
          // Configuración del gráfico con datos
          const chartConfig = {
            type: "pie",
            data: {
              labels: data.data.labels,
              datasets: [
                {
                  label: "Total",
                  data: data.data.data,
                  backgroundColor: generarColoresDinamicos(
                    data.data.data.length
                  ),
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: true,
                  position: "bottom",
                },
                title: {
                  display: true,
                  text: "Total de Bajas " + data.data.total_historial,
                },
              },
            },
          };

          if (bajas) {
            chartInstanceBajas = new Chart(bajas, chartConfig);
          }
        } else {
          // Si no hay datos, mostrar un gráfico vacío con un mensaje
          console.warn("No hay datos para el mes seleccionado.");

          const emptyConfig = {
            type: "pie",
            data: {
              labels: ["Sin datos"],
              datasets: [
                {
                  label: "Solicitudes",
                  data: [1], // Usamos 1 para que el gráfico no se rompa
                  backgroundColor: ["rgba(200, 200, 200, 0.5)"], // Color gris claro
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                title: {
                  display: true,
                  text: "No hay datos disponibles",
                },
              },
            },
          };

          if (bajas) {
            chartInstanceBajas = new Chart(bajas, emptyConfig);
          }
        }
      })
      .catch((error) => console.error("Error fetching data:", error));
  }

  bajasColaboradoresData(base_url + "/Dashboard/pieRazonesBajas");

  document
    .getElementById("filtroMesBajas")
    ?.addEventListener("submit", function (event) {
      event.preventDefault();
      const mesBaja = document.getElementById("mesBaja")?.value;
      bajasColaboradoresData(
        base_url + "/Dashboard/pieRazonesBajasMes?mesBaja=" + mesBaja
      );
    });

  let chartInstanceReclutamiento = null;
  const reclutamiento = document
    .getElementById("reclutamientoDonut")
    ?.getContext("2d");

  function reclutamientoColaboradoresData(url) {
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (chartInstanceReclutamiento) {
          chartInstanceReclutamiento.destroy(); // Destruir gráfico anterior
        }
        if (data.status && data.data.labels.length > 0) {
          const chartConfig = {
            type: "doughnut", // Cambio a Donut Chart
            data: {
              labels:
                data.status && data.data.labels.length > 0
                  ? data.data.labels
                  : ["Sin datos"],
              datasets: [
                {
                  label: "Total",
                  data:
                    data.status && data.data.labels.length > 0
                      ? data.data.data
                      : [1], // Se evita que el gráfico se rompa
                  backgroundColor: generarColoresDinamicos(
                    data.data.data.length
                  ),
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              cutout: "60%", // Grosor del anillo
              plugins: {
                legend: {
                  display: true,
                  position: "bottom",
                },
                title: {
                  display: true,
                  text:
                    data.status && data.data.labels.length > 0
                      ? "Total de Solicitudes " + data.data.totalSolicitudes
                      : "No hay datos disponibles",
                },
              },
            },
          };

          if (reclutamiento) {
            chartInstanceReclutamiento = new Chart(reclutamiento, chartConfig);
          }
        } else {
          // Si no hay datos, mostrar un gráfico vacío con un mensaje
          console.warn("No hay datos para el mes seleccionado.");

          const emptyConfig = {
            type: "pie",
            data: {
              labels: ["Sin datos"],
              datasets: [
                {
                  label: "Solicitudes",
                  data: [1], // Usamos 1 para que el gráfico no se rompa
                  backgroundColor: ["rgba(200, 200, 200, 0.5)"], // Color gris claro
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                title: {
                  display: true,
                  text: "No hay datos disponibles",
                },
              },
            },
          };

          if (reclutamiento) {
            chartInstanceReclutamiento = new Chart(reclutamiento, emptyConfig);
          }
        }
      })
      .catch((error) => console.error("Error fetching data:", error));
  }

  reclutamientoColaboradoresData(base_url + "/Dashboard/pieReclutamientos");

  document
    .getElementById("filtroReclutamientoMes")
    ?.addEventListener("submit", function (event) {
      event.preventDefault();
      const mesReclutamiento =
        document.getElementById("mesReclutamiento")?.value;
      reclutamientoColaboradoresData(
        base_url +
          "/Dashboard/pieReclutamientosMes?mesReclutamiento=" +
          mesReclutamiento
      );
    });

  let chartInstanceAltasAreas = null;
  const altasAreas = document
    .getElementById("areasAltasDonut")
    ?.getContext("2d");
  function altasAreasData(url) {
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (chartInstanceAltasAreas) {
          chartInstanceAltasAreas.destroy(); // Siempre destruir el gráfico anterior
        }

        if (data.status && data.data.labels.length > 0) {
          // Configuración del gráfico con datos
          const chartConfig = {
            type: "doughnut", // Cambio a Donut Chart
            data: {
              labels: data.data.labels,
              datasets: [
                {
                  label: "Total",
                  data: data.data.data,
                  backgroundColor: generarColoresDinamicos(
                    data.data.data.length
                  ),
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: true,
                  position: "bottom",
                },
                title: {
                  display: true,
                  text: "Total de Altas " + data.data.total_historial,
                },
              },
            },
          };

          if (altasAreas) {
            chartInstanceAltasAreas = new Chart(altasAreas, chartConfig);
          }
        } else {
          // Si no hay datos, mostrar un gráfico vacío con un mensaje
          console.warn("No hay datos para el mes seleccionado.");

          const emptyConfig = {
            type: "pie",
            data: {
              labels: ["Sin datos"],
              datasets: [
                {
                  label: "Solicitudes",
                  data: [1], // Usamos 1 para que el gráfico no se rompa
                  backgroundColor: ["rgba(200, 200, 200, 0.5)"], // Color gris claro
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                title: {
                  display: true,
                  text: "No hay datos disponibles",
                },
              },
            },
          };

          if (altasAreas) {
            chartInstanceAltasAreas = new Chart(altasAreas, emptyConfig);
          }
        }
      })
      .catch((error) => console.error("Error fetching data:", error));
  }

  altasAreasData(base_url + "/Dashboard/pieAreasAltas");

  function generarColoresDinamicos(dataLength) {
    return Array.from({ length: dataLength }, (_, i) => {
      const hue = ((i * 360) / dataLength) % 360; // Distribución uniforme del matiz
      return `hsla(${hue}, 70%, 50%, 0.7)`;
    });
  }

  let chartInstanceBajasAreas = null;
  const bajasAreas = document
    .getElementById("areasBajasDonut")
    ?.getContext("2d");
  function bajasAreasData(url) {
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (chartInstanceBajasAreas) {
          chartInstanceBajasAreas.destroy(); // Siempre destruir el gráfico anterior
        }

        if (data.status && data.data.labels.length > 0) {
          // Configuración del gráfico con datos
          const chartConfig = {
            type: "doughnut", // Cambio a Donut Chart
            data: {
              labels: data.data.labels,
              datasets: [
                {
                  label: "Total",
                  data: data.data.data,
                  backgroundColor: generarColoresDinamicos(
                    data.data.data.length
                  ),
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: true,
                  position: "bottom",
                },
                title: {
                  display: true,
                  text: "Total de Bajas " + data.data.total_historial,
                },
              },
            },
          };

          if (bajasAreas) {
            chartInstanceBajasAreas = new Chart(bajasAreas, chartConfig);
          }
        } else {
          // Si no hay datos, mostrar un gráfico vacío con un mensaje
          console.warn("No hay datos para el mes seleccionado.");

          const emptyConfig = {
            type: "pie",
            data: {
              labels: ["Sin datos"],
              datasets: [
                {
                  label: "Solicitudes",
                  data: [1], // Usamos 1 para que el gráfico no se rompa
                  backgroundColor: ["rgba(200, 200, 200, 0.5)"], // Color gris claro
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                title: {
                  display: true,
                  text: "No hay datos disponibles",
                },
              },
            },
          };

          if (bajasAreas) {
            chartInstanceBajasAreas = new Chart(bajasAreas, emptyConfig);
          }
        }
      })
      .catch((error) => console.error("Error fetching data:", error));
  }

  bajasAreasData(base_url + "/Dashboard/pieAreasBajas");

  document
    .getElementById("areasMes")
    ?.addEventListener("submit", function (event) {
      event.preventDefault();
      const mesArea = document.getElementById("mesArea")?.value;
      altasAreasData(
        base_url + "/Dashboard/pieAreasAltasMes?mesArea=" + mesArea
      );
      bajasAreasData(
        base_url + "/Dashboard/pieAreasBajasMes?mesArea=" + mesArea
      );
    });

  $(document).ready(function () {
    function cargarTablaGeneral() {
      return $("#tableDocumentos").DataTable({
        info: false,
        paging: false,
        searching: false,
        language: {
          url: media_url + "/plugins/datatables/Spanish.json",
        },
        ajax: {
          url: base_url + "/Dashboard/getDocumentosTable",
        },
        columns: [
          {
            title: "No.",
            data: null,
            render: function (data, type, row, meta) {
              return meta.row + 1;
            },
          },
          { title: "Colaborador", data: "nombre_completo" },
          { title: "%", data: "porcentaje_completo" },
        ],
        order: [[0, "asc"]],
      });
    }

    function cargarTablaPorColaborador(id_empleado) {
      return $("#tableDocumentos").DataTable({
        destroy: true, // Destruye la tabla anterior antes de inicializar una nueva
        info: false,
        paging: false,
        searching: false,
        language: {
          url: media_url + "/plugins/datatables/Spanish.json",
        },
        ajax: {
          url: base_url + "/Dashboard/getDocumentosbyID/" + id_empleado,
        },
        columns: [
          {
            title: "No.",
            data: null,
            render: function (data, type, row, meta) {
              return meta.row + 1;
            },
          },
          { title: "Documento", data: "nombre_documento" },
          {
            title: "Archivo",
            data: "ubicacion",
            render: function (data, type, row) {
              if (!data || data.trim() === "") {
                return `<h6><strong>Sin documento</strong></h6>`;
              } else {
                return `
                    <a href="${base_url}/${data}" class="btn btn-danger" target="_blank">
                      <i class="fas fa-file-pdf"></i>
                    </a>
                  `;
              }
            },
          },
        ],
        order: [[0, "asc"]],
      });
    }

    // Cargar la tabla general al iniciar
    let tablaActual = cargarTablaGeneral();

    // Evento de filtro
    $("#filtroColaborador").on("submit", function (e) {
      e.preventDefault();
      let id_empleado = $("#id_empleado").val();

      if (id_empleado) {
        tablaActual.destroy();
        tablaActual = cargarTablaPorColaborador(id_empleado);
        $("#btnRestaurar").show(); // Mostrar botón de restaurar
      }
    });

    // Botón de restaurar
    $("#btnRestaurar").on("click", function () {
      tablaActual.destroy();
      tablaActual = cargarTablaGeneral();
      $("#btnRestaurar").hide(); // Ocultar botón de restaurar
      $("#id_empleado").val(""); // Resetear select de empleados
    });

    // Ocultar el botón al inicio
    $("#btnRestaurar").hide();
  });

  if (document.querySelector("#id_empleado")) {
    let ajaxUrl = base_url + "/Dashboard/getSelectColaboradores";
    let request = new XMLHttpRequest();
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState === 4 && request.status === 200) {
        document.querySelector("#id_empleado").innerHTML = request.responseText;
        $("#id_empleado").selectpicker("refresh");
      }
    };
  }

  let chartInstanceAreasVacaciones = null;
  const areasVacaciones = document
    .getElementById("areasVacaciones")
    ?.getContext("2d");
  function areasVacacionesData(url) {
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (chartInstanceAreasVacaciones) {
          chartInstanceAreasVacaciones.destroy(); // Siempre destruir el gráfico anterior
        }

        if (data.status && data.data.labels.length > 0) {
          // Configuración del gráfico con datos
          const chartConfig = {
            type: "pie",
            data: {
              labels: data.data.labels,
              datasets: [
                {
                  label: "Total",
                  data: data.data.data,
                  backgroundColor: generarColoresDinamicos(
                    data.data.data.length
                  ),
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: true,
                  position: "bottom",
                },
                title: {
                  display: true,
                  text: "Total de Solicitudes " + data.data.total_historial,
                },
              },
            },
          };

          if (areasVacaciones) {
            chartInstanceAreasVacaciones = new Chart(
              areasVacaciones,
              chartConfig
            );
          }
        } else {
          // Si no hay datos, mostrar un gráfico vacío con un mensaje
          console.warn("No hay datos para el mes seleccionado.");

          const emptyConfig = {
            type: "pie",
            data: {
              labels: ["Sin datos"],
              datasets: [
                {
                  label: "Solicitudes",
                  data: [1], // Usamos 1 para que el gráfico no se rompa
                  backgroundColor: ["rgba(200, 200, 200, 0.5)"], // Color gris claro
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                title: {
                  display: true,
                  text: "No hay datos disponibles",
                },
              },
            },
          };

          if (areasVacaciones) {
            chartInstanceAreasVacaciones = new Chart(
              areasVacaciones,
              emptyConfig
            );
          }
        }
      })
      .catch((error) => console.error("Error fetching data:", error));
  }

  areasVacacionesData(base_url + "/Dashboard/pieAreasVacaciones");

  document
    .getElementById("filtroAreaVacaciones")
    ?.addEventListener("submit", function (event) {
      event.preventDefault();
      const mesVacaciones = document.getElementById("mesVacaciones")?.value;
      areasVacacionesData(
        base_url +
          "/Dashboard/pieAreasVacacionesbyMes?mesVacaciones=" +
          mesVacaciones
      );
    });

  let chartInstanceRechazosVacaciones = null;
  const rechazosVacaciones = document
    .getElementById("rechazosVacaciones")
    ?.getContext("2d");
  function rechazosVacacionesData(url) {
    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (chartInstanceRechazosVacaciones) {
          chartInstanceRechazosVacaciones.destroy(); // Siempre destruir el gráfico anterior
        }

        if (data.status && data.data.labels.length > 0) {
          // Configuración del gráfico con datos
          const chartConfig = {
            type: "pie",
            data: {
              labels: data.data.labels,
              datasets: [
                {
                  label: "Total",
                  data: data.data.data,
                  backgroundColor: generarColoresDinamicos(
                    data.data.data.length
                  ),
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: true,
                  position: "bottom",
                },
                title: {
                  display: true,
                  text: "Total de Solicitudes " + data.data.total_historial,
                },
              },
            },
          };

          if (rechazosVacaciones) {
            chartInstanceRechazosVacaciones = new Chart(
              rechazosVacaciones,
              chartConfig
            );
          }
        } else {
          // Si no hay datos, mostrar un gráfico vacío con un mensaje
          console.warn("No hay datos para el mes seleccionado.");

          const emptyConfig = {
            type: "pie",
            data: {
              labels: ["Sin datos"],
              datasets: [
                {
                  label: "Solicitudes",
                  data: [1], // Usamos 1 para que el gráfico no se rompa
                  backgroundColor: ["rgba(200, 200, 200, 0.5)"], // Color gris claro
                  borderColor: "#fff",
                  borderWidth: 2,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                title: {
                  display: true,
                  text: "No hay datos disponibles",
                },
              },
            },
          };

          if (rechazosVacaciones) {
            chartInstanceRechazosVacaciones = new Chart(
              rechazosVacaciones,
              emptyConfig
            );
          }
        }
      })
      .catch((error) => console.error("Error fetching data:", error));
  }

  rechazosVacacionesData(base_url + "/Dashboard/pieRechazoSolicitudes");

  document
    .getElementById("filtroRechazoVacaciones")
    ?.addEventListener("submit", function (event) {
      event.preventDefault();
      const mesRechazos = document.getElementById("mesRechazos")?.value;
      rechazosVacacionesData(
        base_url +
          "/Dashboard/pieRechazoSolicitudesbyMES?mesRechazos=" +
          mesRechazos
      );
    });

  let inactivityTime = 300000; // 5 minutos en milisegundos
  let inactivityTimer;

  // Función para reiniciar el temporizador
  function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
      location.reload(); // Recargar la página después de 5 minutos de inactividad
    }, inactivityTime);
  }

  // Escuchar eventos de actividad del usuario
  document.addEventListener("mousemove", resetInactivityTimer);
  document.addEventListener("keydown", resetInactivityTimer);
  document.addEventListener("click", resetInactivityTimer);
  document.addEventListener("scroll", resetInactivityTimer);

  // Iniciar el temporizador al cargar la página
  resetInactivityTimer();

  // FINAL
});
