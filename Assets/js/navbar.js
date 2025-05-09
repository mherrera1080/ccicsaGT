

window.addEventListener('DOMContentLoaded', event => {
    // Selecciona los elementos de la barra lateral y el botón
    let btn = document.querySelector('#btn'); // Botón que alterna la barra lateral
    let sidebar = document.querySelector('.sidebar'); // La barra lateral
    
    // Persistir el estado de la barra lateral entre refrescos
    if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        sidebar.classList.add('active'); // Si el estado guardado es true, añade la clase 'active'
    }
    
    // Verifica si el botón de la barra lateral existe
    if (btn) {
        btn.addEventListener('click', event => {
            event.preventDefault(); // Previene el comportamiento por defecto del botón
            
            // Alterna la clase 'active' en la barra lateral
            sidebar.classList.toggle('active');
            
            // Guarda el estado de la barra lateral en localStorage
            localStorage.setItem('sb|sidebar-toggle', sidebar.classList.contains('active'));
        });
    }
  });
  