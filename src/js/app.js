document.addEventListener('DOMContentLoaded', function() {
    
    // Contenedores DOM
    const slidersContainer = document.getElementById('sliders-container');
    const promocionesFotos = document.getElementById('promociones-fotos');
    const paginaCartelera = document.getElementById('pagina-cartelera');


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

if (paginaCartelera) {
        console.log("Cargando cartelera...");
        
        fetch('/api/obtener_peliculas.php')
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('peliculas-contenido');
                
                // Validar si hay datos
                if (!data.peliculas || data.peliculas.length === 0) {
                    container.innerHTML = '<p style="text-align: center;">No hay películas en cartelera por ahora.</p>';
                    return;
                }

                let html = '';
                
                data.peliculas.forEach(peli => {
                    // 1. Formatear Duración (Matemática simple para reemplazar filtro Django)
                    const horas = Math.floor(peli.duracion_minutos / 60);
                    const minutos = peli.duracion_minutos % 60;
                    const duracionTexto = `${horas}h ${minutos}m`;

                    // 2. Formatear Sinopsis (Reemplazo de truncatechars)
                    let sinopsis = peli.sinopsis || '';
                    if (sinopsis.length > 150) {
                        sinopsis = sinopsis.substring(0, 150) + '...';
                    }

                    // 3. Formatear Fecha (Reemplazo de date:"d M Y")
                    // Agregamos hora para evitar desfases de zona horaria
                    const fechaObj = new Date(peli.fecha_estreno + 'T00:00:00'); 
                    const opcionesFecha = { day: 'numeric', month: 'short', year: 'numeric' };
                    const fechaTexto = fechaObj.toLocaleDateString('es-ES', opcionesFecha);

                    // 4. Imagen (Fallback si no existe)
                    const imgPath = peli.imagen ? `/uploads/peliculas/${peli.imagen}` : '/uploads/default_poster.jpg';

                    // 5. Generar HTML de Salas (Formatos)
                    let salasHtml = '';
                    if (peli.salas && peli.salas.length > 0) {
                        peli.salas.forEach(sala => {
                            salasHtml += `<span class="etiqueta-sala">${sala}</span>`;
                        });
                    } else {
                        salasHtml = '<span class="etiqueta-sala">N/A</span>';
                    }

                    // 6. Generar HTML de Géneros
                    let generosHtml = '';
                    if (peli.generos && peli.generos.length > 0) {
                        peli.generos.forEach(genero => {
                            generosHtml += `<span class="etiqueta-genero">${genero}</span>`;
                        });
                    } else {
                        generosHtml = '<span class="etiqueta-genero">N/A</span>';
                    }

                    // 7. Construir la Tarjeta HTML (Idéntica a tu template Django)
                    html += `
                    <div class="pelicula">
                        <img src="${imgPath}" alt="${peli.nombre}">
                        
                        <h3>${peli.nombre}</h3>
                        
                        <p>Duración: ${duracionTexto}</p>
                        
                        <p class="sinopsis-corta">Sinopsis: ${sinopsis}</p>
                        
                        <p>Estreno: ${fechaTexto}</p>
                        
                        <p>Restricción: <strong>${peli.restriccion}</strong></p>
                        
                        <h3>Formatos Disponibles:</h3>
                        <div class="lista-formatos">
                            ${salasHtml}
                        </div>
                        
                        <h3>Géneros:</h3>
                        <div class="lista-generos">
                            ${generosHtml}
                        </div>
                    </div>
                    `;
                });

                container.innerHTML = html;
            })
            .catch(err => {
                console.error("Error cargando películas:", err);
                document.getElementById('peliculas-contenido').innerHTML = '<p>Hubo un error al cargar la cartelera.</p>';
            });
    }
    
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
// =====================================================
// 3. LÓGICA ADMIN: GESTIÓN DE ANUNCIOS
// =====================================================
function initAdminAnuncios() {
    const form = document.getElementById('form-anuncio');
    if(!form) return;

    cargarTablaAnuncios();

    form.addEventListener('submit', e => {
        e.preventDefault();
        fetch('/api/anuncios_crud.php', { method: 'POST', body: new FormData(form) })
            .then(r => r.json())
            .then(d => {
                if(d.success) { alert('Guardado'); limpiarFormAnuncio(); cargarTablaAnuncios(); }
                else alert('Error: ' + d.error);
            });
    });
}

function cargarTablaAnuncios() {
    fetch('/api/anuncios_crud.php').then(r => r.json()).then(data => {
        let html = '';
        data.anuncios.forEach(a => {
            const img = a.imagen ? `/uploads/anuncios/${a.imagen}` : '';
            html += `<tr class="border-b hover:bg-gray-50">
                <td class="p-2"><img src="${img}" class="h-10 w-20 object-cover"></td>
                <td class="p-2">${a.nombre}</td>
                <td class="p-2"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">${a.tipo}</span></td>
                <td class="p-2">${a.vigencia}</td>
                <td class="p-2 text-center">
                    <button onclick="editarAnuncio('${a.id}')" class="text-blue-600 font-bold mr-2">Editar</button>
                    <button onclick="eliminarGenerico('/api/anuncios_crud.php', '${a.id}', cargarTablaAnuncios)" class="text-red-600 font-bold">Borrar</button>
                </td>
            </tr>`;
        });
        document.getElementById('tabla-anuncios-body').innerHTML = html;
    });
}

window.editarAnuncio = function(id) {
    fetch(`/api/anuncios_crud.php?id=${id}`).then(r => r.json()).then(d => {
        if(d.success) {
            const a = d.anuncio;
            document.getElementById('anuncio_id').value = a.id;
            document.getElementById('anuncio_nombre').value = a.nombre;
            document.getElementById('anuncio_tipo').value = a.tipo;
            document.getElementById('anuncio_vigencia').value = a.vigencia;
            document.getElementById('anuncio_link').value = a.link;
            document.getElementById('anuncio_imagen_actual').value = a.imagen;
            document.getElementById('form-title-anuncio').innerText = "Editar Anuncio";
            window.scrollTo(0,0);
        }
    });
}

window.limpiarFormAnuncio = function() {
    document.getElementById('form-anuncio').reset();
    document.getElementById('anuncio_id').value = '';
    document.getElementById('form-title-anuncio').innerText = "Agregar Nuevo Anuncio";
}


// =====================================================
// 4. LÓGICA ADMIN: GESTIÓN DE SALAS
// =====================================================
function initAdminSalas() {
    const form = document.getElementById('form-sala');
    if(!form) return;

    cargarTablaSalas();

    form.addEventListener('submit', e => {
        e.preventDefault();
        fetch('/api/salas_crud.php', { method: 'POST', body: new FormData(form) })
            .then(r => r.json())
            .then(d => { if(d.success) { alert('Guardado'); limpiarFormSala(); cargarTablaSalas(); } });
    });
}

function cargarTablaSalas() {
    fetch('/api/salas_crud.php').then(r => r.json()).then(data => {
        let html = '';
        data.salas.forEach(s => {
            html += `<tr class="border-b hover:bg-gray-50">
                <td class="p-2 font-bold">${s.nombre}</td>
                <td class="p-2 text-sm text-gray-600">${s.descripcion || ''}</td>
                <td class="p-2 text-center">
                    <button onclick="editarSala('${s.id}')" class="text-blue-600 mr-2">Editar</button>
                    <button onclick="eliminarGenerico('/api/salas_crud.php', '${s.id}', cargarTablaSalas)" class="text-red-600">Borrar</button>
                </td>
            </tr>`;
        });
        document.getElementById('tabla-salas-body').innerHTML = html;
    });
}

window.editarSala = function(id) {
    fetch(`/api/salas_crud.php?id=${id}`).then(r => r.json()).then(d => {
        const s = d.sala;
        document.getElementById('sala_id').value = s.id;
        document.getElementById('sala_nombre').value = s.nombre;
        document.getElementById('sala_descripcion').value = s.descripcion;
        document.getElementById('sala_imagen_actual').value = s.imagen;
        document.getElementById('form-title-sala').innerText = "Editar Sala";
        window.scrollTo(0,0);
    });
}

window.limpiarFormSala = function() {
    document.getElementById('form-sala').reset();
    document.getElementById('sala_id').value = '';
    document.getElementById('form-title-sala').innerText = "Agregar Nueva Sala";
}


// =====================================================
// 5. LÓGICA ADMIN: GESTIÓN DE GÉNEROS
// =====================================================
function initAdminGeneros() {
    const form = document.getElementById('form-genero');
    if(!form) return;

    cargarTablaGeneros();

    form.addEventListener('submit', e => {
        e.preventDefault();
        fetch('/api/generos_crud.php', { method: 'POST', body: new FormData(form) })
            .then(r => r.json())
            .then(d => { if(d.success) { alert('Guardado'); limpiarFormGenero(); cargarTablaGeneros(); } });
    });
}

function cargarTablaGeneros() {
    fetch('/api/generos_crud.php').then(r => r.json()).then(data => {
        let html = '';
        data.generos.forEach(g => {
            html += `<tr class="border-b">
                <td class="p-2 font-bold">${g.nombre}</td>
                <td class="p-2 text-center">
                    <button onclick="editarGenero('${g.id}')" class="text-blue-600 mr-2">Editar</button>
                    <button onclick="eliminarGenerico('/api/generos_crud.php', '${g.id}', cargarTablaGeneros)" class="text-red-600">Borrar</button>
                </td>
            </tr>`;
        });
        document.getElementById('tabla-generos-body').innerHTML = html;
    });
}

window.editarGenero = function(id) {
    fetch(`/api/generos_crud.php?id=${id}`).then(r => r.json()).then(d => {
        const g = d.genero;
        document.getElementById('genero_id').value = g.id;
        document.getElementById('genero_nombre').value = g.nombre;
        document.getElementById('genero_descripcion').value = g.descripcion;
        document.getElementById('form-title-genero').innerText = "Editar Género";
    });
}

window.limpiarFormGenero = function() {
    document.getElementById('form-genero').reset();
    document.getElementById('genero_id').value = '';
    document.getElementById('form-title-genero').innerText = "Agregar Nuevo Género";
}

// Función Helper para borrar (DRY)
window.eliminarGenerico = function(url, id, callbackRef) {
    if(!confirm('¿Estás seguro de eliminar?')) return;
    fetch(url, {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id: id})
    }).then(r => r.json()).then(d => {
        if(d.success) callbackRef();
        else alert('Error al eliminar');
    });
}
});
function initAdminPeliculas() {
    const form = document.getElementById('productForm');
    if(!form) return;
    const selGeneros = document.getElementById('generos');
    const selSalas = document.getElementById('salas');

    fetch('/api/peliculas_crud.php?action=options').then(r=>r.json()).then(d=>{
        if(d.success){
            selGeneros.innerHTML = d.generos.map(g=>`<option value="${g.id}">${g.nombre}</option>`).join('');
            selSalas.innerHTML = d.salas.map(s=>`<option value="${s.id}">${s.nombre}</option>`).join('');
        }
    });

    cargarPeliculas();

    form.addEventListener('submit', e => {
        e.preventDefault();
        fetch('/api/peliculas_crud.php', { method: 'POST', body: new FormData(form) })
            .then(r => r.json()).then(d => { if(d.success) { alert('Guardado'); limpiarForm(); cargarPeliculas(); } else alert('Error: '+d.error); });
    });
}

function cargarPeliculas() {
    fetch('/api/peliculas_crud.php').then(r=>r.json()).then(d=>{
        if(!d.peliculas) return;
        document.getElementById('peliculasContainer').innerHTML = d.peliculas.map(p=>`
            <div class="pelicula-item">
                <strong>${p.nombre}</strong> (${p.fecha_estreno})
                <div>
                    <button onclick="editarPelicula('${p.id}')" class="btn-edit">Editar</button>
                    <button onclick="eliminarGenerico('/api/peliculas_crud.php','${p.id}',cargarPeliculas)" class="btn-delete">Eliminar</button>
                </div>
            </div>`).join('');
    });
}

window.editarPelicula = function(id) {
    fetch(`/api/peliculas_crud.php?id=${id}`).then(r=>r.json()).then(d=>{
        const p = d.pelicula;
        document.getElementById('peli_id').value = p.id;
        document.getElementById('nombre').value = p.nombre;
        document.getElementById('sinopsis').value = p.sinopsis;
        document.getElementById('duracion').value = p.duracion_minutos;
        document.getElementById('fecha_estreno').value = p.fecha_estreno;
        document.getElementById('restriccion').value = p.restriccion;
        document.getElementById('peli_imagen_actual').value = p.imagen;
        
        // Marcar selects múltiples
        Array.from(document.getElementById('generos').options).forEach(o => o.selected = d.generos_ids.includes(o.value));
        Array.from(document.getElementById('salas').options).forEach(o => o.selected = d.salas_ids.includes(o.value));
        
        window.scrollTo(0,0);
    });
}

window.limpiarForm = function() {
    document.getElementById('productForm').reset();
    document.getElementById('peli_id').value = '';
}

// === ADMIN GENERICOS (Salas, Generos, Anuncios) ===
// Usa la misma lógica que te pasé en los mensajes anteriores para initAdminSalas, initAdminGeneros, etc.
// Asegúrate de incluir la función eliminarGenerico:
window.eliminarGenerico = function(url, id, callback) {
    if(!confirm('¿Eliminar?')) return;
    fetch(url, { method: 'DELETE', body: JSON.stringify({id: id}) }).then(r=>r.json()).then(d=>{
        if(d.success) callback();
        else alert('Error');
    });
}