document.addEventListener('DOMContentLoaded', () => {
    // =====================================================
    // 1. LÓGICA PÚBLICA (Frontend)
    // =====================================================
    
    // Detectar página de Cartelera
    if (document.getElementById('pagina-cartelera')) {
        cargarCarteleraPublica();
    }
    
    // Detectar página de Confitería
    if (document.getElementById('pagina-confiteria')) {
        cargarConfiteriaPublica();
    }
    
    // Detectar Home (si existen los contenedores de sliders)
    if (document.getElementById('sliders-container')) {
        cargarHomePublico();
    }
    
    // Detectar página de Salas Públicas
    if (document.getElementById('pagina-salas')) {
        cargarSalasPublicas();
    }
});

/* -------------------------------------------------------------------------- */
/* FUNCIONES PÚBLICAS                           */
/* -------------------------------------------------------------------------- */

function cargarCarteleraPublica() {
    console.log("Cargando cartelera...");
    fetch('/api/obtener_peliculas.php')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('peliculas-contenido');
            if (!data.peliculas || data.peliculas.length === 0) {
                container.innerHTML = '<p class="col-span-full text-center text-gray-500">No hay películas en cartelera por ahora.</p>';
                return;
            }

            let html = '';
            data.peliculas.forEach(peli => {
                // Formateos
                const horas = Math.floor(peli.duracion_minutos / 60);
                const minutos = peli.duracion_minutos % 60;
                const duracionTexto = `${horas}h ${minutos}m`;
                
                let sinopsis = peli.sinopsis || '';
                if (sinopsis.length > 150) sinopsis = sinopsis.substring(0, 150) + '...';

                // Imagen con fallback
                const imgPath = peli.imagen ? `/uploads/peliculas/${peli.imagen}` : '/uploads/default_poster.jpg';

                // Salas y Géneros
                let salasHtml = (peli.salas && peli.salas.length) 
                    ? peli.salas.map(s => `<span class="etiqueta-sala">${s}</span>`).join('') 
                    : '<span class="etiqueta-sala">N/A</span>';
                
                let generosHtml = (peli.generos && peli.generos.length) 
                    ? peli.generos.map(g => `<span class="etiqueta-genero">${g}</span>`).join('') 
                    : '<span class="etiqueta-genero">N/A</span>';

                html += `
                <div class="pelicula">
                    <img src="${imgPath}" alt="${peli.nombre}">
                    <h3>${peli.nombre}</h3>
                    <p>Duración: ${duracionTexto}</p>
                    <p class="sinopsis-corta">Sinopsis: ${sinopsis}</p>
                    <p>Estreno: ${peli.fecha_estreno}</p>
                    <p>Restricción: <strong>${peli.restriccion}</strong></p>
                    
                    <h3>Formatos Disponibles:</h3>
                    <div class="lista-formatos">${salasHtml}</div>
                    
                    <h3>Géneros:</h3>
                    <div class="lista-generos">${generosHtml}</div>
                </div>`;
            });
            container.innerHTML = html;
        })
        .catch(err => {
            console.error("Error:", err);
            document.getElementById('peliculas-contenido').innerHTML = '<p>Error al cargar cartelera.</p>';
        });
}

function cargarConfiteriaPublica() {
    const container = document.getElementById('confiteria-contenido');
    if(!container) return;

    console.log("Cargando confitería...");
    
    // Usamos la API que agrupa por categorías
    fetch('/api/obtener_productos.php') 
        .then(res => res.json())
        .then(data => {
            
            if (!data.success || !data.productos || Object.keys(data.productos).length === 0) {
                container.innerHTML = '<p class="text-center">No hay productos disponibles por el momento.</p>';
                return;
            }

            let htmlCompleto = '';

            // Recorremos el objeto agrupado: { "COMBO": [...], "SNACK": [...] }
            for (const [categoria, listaProductos] of Object.entries(data.productos)) {
                
                // 1. Crear Título de Categoría
                htmlCompleto += `
                    <section class="categoria">
                        <h3>
                            ${categoria}
                        </h3>
                        <div class="productos">
                `;

                // 2. Recorrer productos de ESTA categoría
                listaProductos.forEach(prod => {
                    const img = prod.imagen ? `/uploads/productos/${prod.imagen}` : '/uploads/default.png';
                    
                    htmlCompleto += `
                        <div class="item">
                                <img src="${img}" alt="${prod.nombre}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                <p>${prod.nombre}</p>
                                <span>S/. ${prod.precio}</span>

                        
                    `;
                });

                htmlCompleto += `</div></section>`;
            }

            container.innerHTML = htmlCompleto;
        })
        .catch(err => {
            console.error("Error cargando confitería:", err);
            container.innerHTML = '<p class="text-center text-red-500">Error al cargar los productos.</p>';
        });
}

function cargarHomePublico() {
    const slidersContainer = document.getElementById('sliders-container');
    const promocionesFotos = document.getElementById('promociones-fotos'); // Asegúrate de tener este ID en tu HTML

    fetch('/api/anuncios_crud.php') // Usamos la API que ya devuelve todos los anuncios
        .then(res => res.json())
        .then(data => {
            if (!data.success || !data.anuncios) return;

            // Filtrar por tipo
            const sliders = data.anuncios.filter(a => a.tipo === 'SLIDER');
            const promociones = data.anuncios.filter(a => a.tipo === 'PROMOCION');

            // Render Sliders
            if (sliders.length > 0) {
                let htmlS = '';
                sliders.forEach(s => {
                    const img = s.imagen ? `/uploads/anuncios/${s.imagen}` : '';
                    htmlS += `<div class="slide"><img src="${img}" alt="${s.nombre}" style="width:100%; max-height:400px; object-fit:cover;"></div>`;
                });
                slidersContainer.innerHTML = htmlS;
            }

            // Render Promociones
            if (promocionesFotos && promociones.length > 0) {
                let htmlP = '';
                promociones.forEach(p => {
                    const img = p.imagen ? `/uploads/anuncios/${p.imagen}` : '';
                    htmlP += `<div class="promo"><img src="${img}" alt="${p.nombre}" style="width:100%;"></div>`;
                });
                promocionesFotos.innerHTML = htmlP;
            }
        });
}

function cargarSalasPublicas() {
    fetch('/api/salas_crud.php')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('salas-contenido');
            if (!data.success || !data.salas) return;
            
            let html = '';
            data.salas.forEach(sala => {
                const img = sala.imagen ? `/uploads/salas/${sala.imagen}` : '';
                html += `
                <div class="sala-card">
                    <div class="sala-header"><h3>${sala.nombre}</h3></div>
                    <div class="sala-body">
                        <img src="${img}" alt="${sala.nombre}" style="background-color: #0d47a1;">
                        <div class="sala-info"><p>${sala.descripcion || ''}</p></div>
                    </div>
                </div>`;
            });
            container.innerHTML = html;
        });
}


/* -------------------------------------------------------------------------- */
/* FUNCIONES ADMIN                              */
/* -------------------------------------------------------------------------- */

// === 1. ADMIN PELICULAS ===
function initAdminPeliculas() {
    const form = document.getElementById('productForm'); // ID del formulario en admin_peliculas.php
    if (!form) return;

    const selGeneros = document.getElementById('generos');
    const selSalas = document.getElementById('salas');

    // Cargar opciones para los selects múltiples
    fetch('/api/peliculas_crud.php?action=options').then(r => r.json()).then(d => {
        if (d.success) {
            selGeneros.innerHTML = d.generos.map(g => `<option value="${g.id}">${g.nombre}</option>`).join('');
            selSalas.innerHTML = d.salas.map(s => `<option value="${s.id}">${s.nombre}</option>`).join('');
        }
    });

    cargarPeliculasAdmin();

    form.addEventListener('submit', e => {
        e.preventDefault();
        fetch('/api/peliculas_crud.php', { method: 'POST', body: new FormData(form) })
            .then(r => r.json())
            .then(d => {
                if (d.success) { alert('Guardado'); limpiarFormPelicula(); cargarPeliculasAdmin(); }
                else alert('Error: ' + d.error);
            })
            .catch(err => alert('Error de red'));
    });
}

function cargarPeliculasAdmin() {
    const container = document.getElementById('peliculasContainer');
    if (!container) return;
    
    container.innerHTML = '<p>Cargando...</p>';
    fetch('/api/peliculas_crud.php').then(r => r.json()).then(d => {
        if (!d.peliculas || d.peliculas.length === 0) {
            container.innerHTML = '<p>No hay películas.</p>';
            return;
        }
        
        // Usamos estructura similar a tu HTML original de admin
        let html = '';
        d.peliculas.forEach(p => {
            // Nota: La lista principal GET a veces trae datos básicos. 
            // Si necesitas la imagen en la lista, asegúrate que el PHP la devuelva en el SELECT.
            html += `
            <div class="pelicula-item">
                <div class="peliculas-info">
                    <strong>${p.nombre}</strong> <br>
                    <small>Estreno: ${p.fecha_estreno}</small>
                </div>
                <div class="actions">
                    <button onclick="editarPelicula('${p.id}')" class="btn-edit">Editar</button>
                    <button onclick="eliminarGenerico('/api/peliculas_crud.php','${p.id}',cargarPeliculasAdmin)" class="btn-delete">Eliminar</button>
                </div>
            </div>`;
        });
        container.innerHTML = html;
    });
}

window.editarPelicula = function(id) {
    fetch(`/api/peliculas_crud.php?id=${id}`).then(r => r.json()).then(d => {
        if (d.success) {
            const p = d.pelicula;
            document.getElementById('peli_id').value = p.id;
            document.getElementById('nombre').value = p.nombre;
            document.getElementById('sinopsis').value = p.sinopsis;
            document.getElementById('duracion').value = p.duracion_minutos;
            document.getElementById('fecha_estreno').value = p.fecha_estreno;
            document.getElementById('restriccion').value = p.restriccion;
            document.getElementById('peli_imagen_actual').value = p.imagen;
            
            if (document.getElementById('preview-txt')) {
                document.getElementById('preview-txt').innerText = p.imagen ? "Imagen actual: " + p.imagen : "";
            }
            
            // Marcar selects múltiples
            // data.generos_ids y data.salas_ids vienen del backend
            const selGeneros = document.getElementById('generos');
            Array.from(selGeneros.options).forEach(o => o.selected = d.generos_ids.includes(o.value));
            
            const selSalas = document.getElementById('salas');
            Array.from(selSalas.options).forEach(o => o.selected = d.salas_ids.includes(o.value));
            
            document.getElementById('btn-guardar').innerText = "Actualizar Película";
            window.scrollTo(0, 0);
        }
    });
}

window.limpiarFormPelicula = function() {
    document.getElementById('productForm').reset();
    document.getElementById('peli_id').value = '';
    document.getElementById('peli_imagen_actual').value = '';
    document.getElementById('preview-txt').innerText = "";
    document.getElementById('btn-guardar').innerText = "Agregar Película";
}


// === 2. ADMIN PRODUCTOS ===
function initAdminProductos() {
    const form = document.getElementById('form-producto'); 
    if(!form) return;
    
    cargarTablaProductos();
    
    form.addEventListener('submit', e => {
        e.preventDefault();
        fetch('/api/productos_crud.php', { method: 'POST', body: new FormData(form) })
            .then(r => r.json())
            .then(d => { if(d.success) { alert('Guardado'); limpiarFormProducto(); cargarTablaProductos(); } else alert(d.error); });
    });
}

function cargarTablaProductos() {
    fetch('/api/productos_crud.php').then(r => r.json()).then(d => {
        let html = '';
        if(d.productos) d.productos.forEach(p => {
            // Mostrar estado Disponible/No Disponible
            const estado = (p.disponible == 1) ? '<span style="color:green;">Disponible</span>' : '<span style="color:red;">No Disponible</span>';
            
            html += `<tr>
                <td style="padding:10px;">${p.nombre}</td>
                <td style="padding:10px;">${p.precio}</td>
                <td style="padding:10px;">${estado}</td>
                <td style="padding:10px;">
                    <button onclick="editarProducto('${p.id}')">Editar</button> 
                    <button onclick="eliminarGenerico('/api/productos_crud.php', '${p.id}', cargarTablaProductos)">Borrar</button>
                </td>
            </tr>`;
        });
        document.getElementById('tabla-productos-body').innerHTML = html;
    });
}

window.editarProducto = function(id) { 
    fetch(`/api/productos_crud.php?id=${id}`).then(r => r.json()).then(d => { 
        const p = d.producto; 
        document.getElementById('prod_id').value = p.id; 
        document.getElementById('prod_nombre').value = p.nombre; 
        document.getElementById('prod_precio').value = p.precio; 
        document.getElementById('prod_categoria').value = p.categoria; 
        document.getElementById('prod_descripcion').value = p.descripcion; 
        document.getElementById('prod_imagen_actual').value = p.imagen; 
        
        // Marcar o desmarcar el checkbox según la BD
        document.getElementById('prod_disponible').checked = (p.disponible == 1);

        window.scrollTo(0,0); 
    }); 
}

window.limpiarFormProducto = function() { 
    document.getElementById('form-producto').reset(); 
    document.getElementById('prod_id').value = ''; 
    document.getElementById('prod_disponible').checked = true; // Resetear a true por defecto
}
// === 3. ADMIN ANUNCIOS ===
function initAdminAnuncios() {
    const form = document.getElementById('form-promocion');
    if (!form) return;
    cargarListaAnuncios();
    form.addEventListener('submit', e => {
        e.preventDefault();
        fetch('/api/anuncios_crud.php', { method: 'POST', body: new FormData(form) })
            .then(r => r.json()).then(d => { if(d.success){ alert('Guardado'); limpiarFormAnuncio(); cargarListaAnuncios(); } else alert(d.error); });
    });
}
function cargarListaAnuncios() {
    fetch('/api/anuncios_crud.php').then(r => r.json()).then(d => {
        let html = '';
        if(d.anuncios) d.anuncios.forEach(a => {
            const img = a.imagen ? `/uploads/anuncios/${a.imagen}` : '';
            html += `<div class="promotion-item"><img src="${img}"> <strong>${a.nombre}</strong> <div><button onclick="editarAnuncio('${a.id}')" class="btn-edit">Editar</button><button onclick="eliminarGenerico('/api/anuncios_crud.php','${a.id}',cargarListaAnuncios)" class="btn-delete">Borrar</button></div></div>`;
        });
        document.getElementById('lista-anuncios-container').innerHTML = html;
    });
}
window.editarAnuncio = function(id) {
    fetch(`/api/anuncios_crud.php?id=${id}`).then(r => r.json()).then(d => {
        const a = d.anuncio;
        document.getElementById('anuncio_id').value = a.id;
        document.getElementById('titulo').value = a.nombre;
        document.getElementById('tipo').value = a.tipo;
        document.getElementById('link').value = a.link;
        document.getElementById('vigencia').value = a.vigencia;
        document.getElementById('anuncio_imagen_actual').value = a.imagen;
        window.scrollTo(0,0);
    });
}
window.limpiarFormAnuncio = function() { document.getElementById('form-promocion').reset(); document.getElementById('anuncio_id').value = ''; }


// === 4. ADMIN SALAS ===
function initAdminSalas() {
    const form = document.getElementById('form-sala');
    if (!form) return;
    cargarTablaSalas();
    form.addEventListener('submit', e => {
        e.preventDefault();
        fetch('/api/salas_crud.php', { method: 'POST', body: new FormData(form) })
            .then(r => r.json()).then(d => { if(d.success){ alert('Guardado'); limpiarFormSala(); cargarTablaSalas(); } });
    });
}
function cargarTablaSalas() {
    fetch('/api/salas_crud.php').then(r => r.json()).then(d => {
        let html = '';
        if(d.salas) d.salas.forEach(s => { html += `<tr><td class="p-2">${s.nombre}</td><td class="p-2"><button onclick="editarSala('${s.id}')" class="btn-edit">Editar</button> <button onclick="eliminarGenerico('/api/salas_crud.php','${s.id}',cargarTablaSalas)" class="btn-delete">Borrar</button></td></tr>`; });
        document.getElementById('tabla-salas-body').innerHTML = html;
    });
}
window.editarSala = function(id) { fetch(`/api/salas_crud.php?id=${id}`).then(r => r.json()).then(d => { const s = d.sala; document.getElementById('sala_id').value = s.id; document.getElementById('sala_nombre').value = s.nombre; document.getElementById('sala_descripcion').value = s.descripcion; document.getElementById('sala_imagen_actual').value = s.imagen; window.scrollTo(0,0); }); }
window.limpiarFormSala = function() { document.getElementById('form-sala').reset(); document.getElementById('sala_id').value = ''; }


// === 5. ADMIN GENEROS ===
function initAdminGeneros() {
    const form = document.getElementById('form-genero');
    if (!form) return;
    cargarTablaGeneros();
    form.addEventListener('submit', e => {
        e.preventDefault();
        fetch('/api/generos_crud.php', { method: 'POST', body: new FormData(form) })
            .then(r => r.json()).then(d => { if(d.success){ alert('Guardado'); limpiarFormGenero(); cargarTablaGeneros(); } });
    });
}
function cargarTablaGeneros() {
    fetch('/api/generos_crud.php').then(r => r.json()).then(d => {
        let html = '';
        if(d.generos) d.generos.forEach(g => { html += `<tr><td class="p-2">${g.nombre}</td><td class="p-2"><button onclick="editarGenero('${g.id}')" class="btn-edit">Editar</button> <button onclick="eliminarGenerico('/api/generos_crud.php','${g.id}',cargarTablaGeneros)" class="btn-delete">Borrar</button></td></tr>`; });
        document.getElementById('tabla-generos-body').innerHTML = html;
    });
}
window.editarGenero = function(id) { fetch(`/api/generos_crud.php?id=${id}`).then(r => r.json()).then(d => { const g = d.genero; document.getElementById('genero_id').value = g.id; document.getElementById('genero_nombre').value = g.nombre; document.getElementById('genero_descripcion').value = g.descripcion; window.scrollTo(0,0); }); }
window.limpiarFormGenero = function() { document.getElementById('form-genero').reset(); document.getElementById('genero_id').value = ''; }


// === HELPER GLOBAL ===
window.eliminarGenerico = function(url, id, callback) {
    if (!confirm('¿Confirmar eliminación?')) return;
    fetch(url, {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id: id })
    }).then(r => r.json()).then(d => {
        if (d.success) callback();
        else alert('Error al eliminar: ' + (d.error || 'Desconocido'));
    });
}