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
    // =====================================================
// LÓGICA PÚBLICA: CARGAR CONFITERÍA
// =====================================================
if (document.getElementById('pagina-confiteria')) {
    fetch('/api/obtener_productos.php')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('confiteria-contenido');
            if (!data.success || Object.keys(data.productos).length === 0) {
                container.innerHTML = '<p class="text-center">No hay productos disponibles.</p>';
                return;
            }

            let html = '';
            // Recorremos las categorías (Combos, Bebidas, etc.)
            for (const [categoria, productos] of Object.entries(data.productos)) {
                html += `
                    <section class="categoria-section">
                        <h2 class="categoria-title">${categoria}</h2>
                        <div class="productos-grid">
                `;
                
                productos.forEach(prod => {
                    const imgPath = prod.imagen ? `/uploads/productos/${prod.imagen}` : '/uploads/default.png';
                    html += `
                        <div class="producto-card">
                            <img src="${imgPath}" alt="${prod.nombre}">
                            <h3>${prod.nombre}</h3>
                            <p>${prod.descripcion || ''}</p>
                            <p class="precio">S/. ${prod.precio}</p>
                            <button class="bg-red-600 text-white px-4 py-1 rounded mt-2 hover:bg-red-700">Agregar</button>
                        </div>
                    `;
                });

                html += `</div></section>`;
            }
            container.innerHTML = html;
        })
        .catch(err => console.error(err));
}

// =====================================================
// LÓGICA ADMIN: GESTIÓN DE PRODUCTOS
// =====================================================
function initAdminProductos() {
    const tablaBody = document.getElementById('tabla-productos-body');
    const form = document.getElementById('form-producto');

    // 1. Cargar Productos en la tabla
    cargarTabla();

    function cargarTabla() {
        fetch('/api/productos_crud.php')
            .then(res => res.json())
            .then(data => {
                let html = '';
                data.productos.forEach(prod => {
                    const imgPath = prod.imagen ? `/uploads/productos/${prod.imagen}` : '/uploads/default.png';
                    html += `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-2"><img src="${imgPath}" class="w-16 h-16 object-cover rounded"></td>
                            <td class="p-2 font-bold">${prod.nombre}</td>
                            <td class="p-2"><span class="bg-gray-200 px-2 py-1 rounded text-xs">${prod.categoria}</span></td>
                            <td class="p-2">S/. ${prod.precio}</td>
                            <td class="p-2 flex gap-2">
                                <button onclick="editarProducto('${prod.id}')" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Editar</button>
                                <button onclick="eliminarProducto('${prod.id}')" class="bg-red-500 text-white px-3 py-1 rounded text-sm">Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
                tablaBody.innerHTML = html;
            });
    }

    // 2. Manejar Envío del Formulario (Crear/Editar)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch('/api/productos_crud.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                limpiarFormulario();
                cargarTabla();
            } else {
                alert('Error: ' + data.error);
            }
        });
    });
}

// Funciones globales para que el HTML las vea (onclick)
window.editarProducto = function(id) {
    fetch(`/api/productos_crud.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                const p = data.producto;
                document.getElementById('prod_id').value = p.id;
                document.getElementById('prod_nombre').value = p.nombre;
                document.getElementById('prod_precio').value = p.precio;
                document.getElementById('prod_categoria').value = p.categoria;
                document.getElementById('prod_descripcion').value = p.descripcion;
                document.getElementById('prod_imagen_actual').value = p.imagen;
                document.getElementById('prod_disponible').checked = (p.disponible == 1);
                
                document.getElementById('form-title').innerText = "Editar Producto: " + p.nombre;
                window.scrollTo(0,0); // Subir para ver el form
            }
        });
};

window.eliminarProducto = function(id) {
    if(!confirm('¿Estás seguro de eliminar este producto?')) return;

    fetch('/api/productos_crud.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            initAdminProductos(); // Recargar tabla
        } else {
            alert('Error al eliminar');
        }
    });
};

window.limpiarFormulario = function() {
    document.getElementById('form-producto').reset();
    document.getElementById('prod_id').value = '';
    document.getElementById('prod_imagen_actual').value = '';
    document.getElementById('form-title').innerText = "Agregar Nuevo Producto";
};
});