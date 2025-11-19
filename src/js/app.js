document.addEventListener('DOMContentLoaded', function() {
    
    // Contenedores DOM
    const slidersContainer = document.getElementById('sliders-container');
    const promocionesFotos = document.getElementById('promociones-fotos');
    const peliculasContainer = document.getElementById('peliculas-container');
    const peliculasFotos = document.getElementById ('peliculas-fotos');


// LOGICA PARA CARGAR ANUNCIOS Y PROMOCIONES

    // Función para construir los sliders
    function cargarSliders(sliders) {
        if (sliders.length === 0) {
            slidersContainer.innerHTML = '<p>No hay anuncios por ahora</p>';
            return;
        }

        let html = '';
        sliders.forEach(slider => {

            const imagenUrl = `uploads/anuncios/${slider.imagen}`; 
            
            html += `<img src="${imagenUrl}" alt="${slider.nombre}">
                     <div class="info">
                         <h3>${slider.nombre}</h3>`;
            
            
            if (slider.link) {
                html += `<a href="${slider.link}" target="_blank">Funciones</a>`;
            }

            html += `</div>`;
        });
        
        slidersContainer.innerHTML = html;
    }
    

    // Función para construir las promociones
    function cargarPromociones(promociones) {
        if (promociones.length === 0) {
            promocionesFotos.innerHTML = '<p>No hay promociones</p>';
            return;
        }

        let html = '';
        promociones.forEach(promo => {
            const imagenUrl = `uploads/anuncios/${promo.imagen}`; 
            
            
            if (promo.link) {
                html += `<a href="${promo.link}" target="_blank">
                             <img src="${imagenUrl}" alt="${promo.nombre}">
                         </a>`;
            } else {
                
                html += `<img src="${imagenUrl}" alt="${promo.nombre}">`;
            }
        });
        
        promocionesFotos.innerHTML = html;
    }


    // ==============================================
    // Petición AJAX (Fetch API)
    // ==============================================
    
    fetch('obtener_anuncios.php') 
        .then(response => {
            
            if (!response.ok) {
                throw new Error('Error al obtener los datos del servidor: ' + response.statusText);
            }
            return response.json(); 
        })
        .then(data => {
            cargarSliders(data.sliders);
            cargarPromociones(data.promociones);
        })
        .catch(error => {
            console.error('Error en la carga:', error);
            slidersContainer.innerHTML = '<p>Error al cargar el contenido.</p>';
        });



//LOGICA PARA CARGAR PELICULAS

    function cargarPeliculas(peliculas) {
        if (peliculas.length === 0) {
            peliculasContainer.innerHTML ='<p>No hay peliculas</p>';
            return;
        }
        let html = '';
        peliculas.forEach(peli => {
            const imagenUrl = `uploads/peliculas/${peli.imagen}`;
            html += `<img src="${imagenUrl}" alt="${peli.titulo}"> 
            <h3>${peli.titulo}</h3>
            <p>Duración: ${peli.duracion} min</p>
            <p class="sinopsis">Sinopsis: ${peli.sinopsis}</p>
            <p> Estreno: ${peli.estreno}</p>
            <p> Restricción: ${peli.restriccion}</p>
            <h3>Salas:</h3>
            `;// Falta agregar salas y generos
            })
        peliculasContainer.innerHTML = html;       
    }
//Peticion AJAX (Fetch API)
    fetch('obtener_peliculas.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los datos del servidor: ' + response.statusText);
            }
            return response.json();})
        .then(data =>{
            cargarPeliculas (data.peliculas);
        })
        .catch(error => {
            console.error('Error en la carga:', error);
            peliculasContainer.innerHTML = '<p>Error al cargar el contenido.</p>';
        });
    
});