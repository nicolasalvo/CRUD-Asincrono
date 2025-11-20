// -----------------------------------------------------------------------------
// Mini CRUD AJAX — Lado cliente (sin librerías)
// Archivo: /assets/js/main.js
// -----------------------------------------------------------------------------
/** URL absoluta o relativa del endpoint PHP (API del servidor) */
const URL_API_SERVIDOR = '/api.php';
/** Elementos de la interfaz que necesitamos manipular */
const nodoCuerpoTablaUsuarios = document.getElementById('tbody'); // <tbody> del listado
const nodoFilaEstadoVacio = document.getElementById('fila-estado-vacio'); // fila de "no hay datos"
const formularioAltaUsuario = document.getElementById('formCreate'); // <form> de alta
const nodoZonaMensajesEstado = document.getElementById('msg'); // <div> mensajes
const nodoBotonAgregarUsuario = document.getElementById('boton-agregar-usuario');
const nodoIndicadorCargando = document.getElementById('indicador-cargando');
 
/** Variables para el modo de edición */
let modoEdicion = false;
let indiceUsuarioEnEdicion = -1;
// -----------------------------------------------------------------------------
// BLOQUE: Gestión de mensajes de estado (éxito / error)
// -----------------------------------------------------------------------------
function mostrarMensajeDeEstado(tipoEstado, textoMensaje) {
    nodoZonaMensajesEstado.className = tipoEstado; // .ok | .error | ''
    nodoZonaMensajesEstado.textContent = textoMensaje;
    if (tipoEstado !== '') {
        setTimeout(() => {
            nodoZonaMensajesEstado.className = '';
            nodoZonaMensajesEstado.textContent = '';
        }, 2000);
    }
}
// -----------------------------------------------------------------------------
// BLOQUE: Indicador de carga + bloqueo de botón
// -----------------------------------------------------------------------------
function activarEstadoCargando() {
    if (nodoBotonAgregarUsuario) nodoBotonAgregarUsuario.disabled = true;
    if (nodoIndicadorCargando) nodoIndicadorCargando.hidden = false;
}
function desactivarEstadoCargando() {
    if (nodoBotonAgregarUsuario) nodoBotonAgregarUsuario.disabled = false;
    if (nodoIndicadorCargando) nodoIndicadorCargando.hidden = true;
}
// -----------------------------------------------------------------------------
// BLOQUE: Funciones para el modo de edición
// -----------------------------------------------------------------------------
function activarModoEdicion(indiceUsuario, datosUsuario) {
    modoEdicion = true;
    indiceUsuarioEnEdicion = indiceUsuario;
   
    // Llenar el formulario con los datos del usuario
    const campoNombre = document.getElementById('campo-nombre');
    const campoEmail = document.getElementById('campo-email');
   
    if (campoNombre) campoNombre.value = datosUsuario.nombre || '';
    if (campoEmail) campoEmail.value = datosUsuario.email || '';
   
    // Cambiar el texto del botón
    if (nodoBotonAgregarUsuario) {
        nodoBotonAgregarUsuario.textContent = 'Actualizar usuario';
    }
   
    // Cambiar el título del formulario
    const tituloFormulario = document.getElementById('titulo-formulario');
    if (tituloFormulario) {
        tituloFormulario.textContent = 'Editar usuario';
    }
   
    // Hacer scroll al formulario
    formularioAltaUsuario?.scrollIntoView({ behavior: 'smooth' });
}
 
function desactivarModoEdicion() {
    modoEdicion = false;
    indiceUsuarioEnEdicion = -1;
   
    // Limpiar el formulario
    formularioAltaUsuario?.reset();
   
    // Restaurar el texto del botón
    if (nodoBotonAgregarUsuario) {
        nodoBotonAgregarUsuario.textContent = 'Agregar usuario';
    }
   
    // Restaurar el título del formulario
    const tituloFormulario = document.getElementById('titulo-formulario');
    if (tituloFormulario) {
        tituloFormulario.textContent = 'Agregar nuevo usuario';
    }
}
// -----------------------------------------------------------------------------
// BLOQUE: Sanitización de texto
// -----------------------------------------------------------------------------
function convertirATextoSeguro(entradaPosiblementePeligrosa) {
    return String(entradaPosiblementePeligrosa)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');
}
// -----------------------------------------------------------------------------
// BLOQUE: Renderizado del listado de usuarios
// -----------------------------------------------------------------------------
function renderizarTablaDeUsuarios(arrayUsuarios) {
    nodoCuerpoTablaUsuarios.innerHTML = '';
    // Mostrar u ocultar la fila de estado vacío según haya datos o no
    if (Array.isArray(arrayUsuarios) && arrayUsuarios.length > 0) {
        if (nodoFilaEstadoVacio) nodoFilaEstadoVacio.hidden = true;
    } else {
        if (nodoFilaEstadoVacio) nodoFilaEstadoVacio.hidden = false;
        return; // no hay filas que pintar
    }
    arrayUsuarios.forEach((usuario, posicionEnLista) => {
        const nodoFila = document.createElement('tr');
        nodoFila.innerHTML = `
<td>${posicionEnLista + 1}</td>
<td>${convertirATextoSeguro(usuario?.nombre ?? '')}</td>
<td>${convertirATextoSeguro(usuario?.email ?? '')}</td>
<td>${convertirATextoSeguro(usuario?.rol ?? '')}</td>
<td>
<button
type="button"
class="boton-editar"
data-posicion="${posicionEnLista}"
aria-label="Editar usuario ${posicionEnLista + 1}">
Editar
</button>
<button
type="button"
class="boton-eliminar"
data-posicion="${posicionEnLista}"
aria-label="Eliminar usuario ${posicionEnLista + 1}">
Eliminar
</button>
</td>
`;
        nodoCuerpoTablaUsuarios.appendChild(nodoFila);
    });
}
// -----------------------------------------------------------------------------
// BLOQUE: Carga inicial y refresco del listado (GET list)
// -----------------------------------------------------------------------------
async function obtenerYMostrarListadoDeUsuarios() {
    try {
        const respuestaHttp = await fetch(`${URL_API_SERVIDOR}?action=list`);
        const cuerpoJson = await respuestaHttp.json();
        if (!cuerpoJson.ok) {
            throw new Error(cuerpoJson.error || 'No fue posible obtener el listado.');
        }
        renderizarTablaDeUsuarios(cuerpoJson.data);
    } catch (error) {
        mostrarMensajeDeEstado('error', error.message);
    }
}
// -----------------------------------------------------------------------------
// BLOQUE: Alta y edición de usuario sin recargar la página
// -----------------------------------------------------------------------------
formularioAltaUsuario?.addEventListener('submit', async (evento) => {
    evento.preventDefault();
    const datosFormulario = new FormData(formularioAltaUsuario);
    const datosUsuario = {
        nombre: String(datosFormulario.get('nombre') || '').trim(),
        email: String(datosFormulario.get('email') || '').trim(),
        password: String(datosFormulario.get('password') || '').trim(),
        rol: String(datosFormulario.get('rol') || '').trim(),
    };
    // Validación HTML5 rápida (por si el navegador no la lanza)
    if (!datosUsuario.nombre || !datosUsuario.email || !datosUsuario.password || !datosUsuario.rol) {
        mostrarMensajeDeEstado('error', 'Todos los campos son obligatorios.');
        return;
    }
   
    try {
        activarEstadoCargando();
       
        let url, mensajeExito;
        if (modoEdicion) {
            // Modo edición
            url = `${URL_API_SERVIDOR}?action=edit`;
            datosUsuario.index = indiceUsuarioEnEdicion;
            mensajeExito = 'Usuario actualizado correctamente.';
        } else {
            // Modo creación
            url = `${URL_API_SERVIDOR}?action=create`;
            mensajeExito = 'Usuario agregado correctamente.';
        }
       
        const respuestaHttp = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datosUsuario),
        });
        const cuerpoJson = await respuestaHttp.json();
        if (!cuerpoJson.ok) {
            throw new Error(cuerpoJson.error || `No fue posible ${modoEdicion ? 'actualizar' : 'crear'} el usuario.`);
        }
        renderizarTablaDeUsuarios(cuerpoJson.data);
        desactivarModoEdicion();
        mostrarMensajeDeEstado('ok', mensajeExito);
    } catch (error) {
        mostrarMensajeDeEstado('error', error.message);
    } finally {
        desactivarEstadoCargando();
    }
});
//botones editar y eliminar
nodoCuerpoTablaUsuarios?.addEventListener('click', async (evento) => {
    const nodoBoton = evento.target.closest('button[data-posicion]');
    if (!nodoBoton) return;
   
    const posicionUsuario = parseInt(nodoBoton.dataset.posicion, 10);
    if (!Number.isInteger(posicionUsuario)) return;
   
    // Manejar botón de editar
    if (nodoBoton.classList.contains('boton-editar')) {
        try {
            // Obtener datos actuales del usuario
            const respuestaHttp = await fetch(`${URL_API_SERVIDOR}?action=list`);
            const cuerpoJson = await respuestaHttp.json();
            if (!cuerpoJson.ok) {
                throw new Error(cuerpoJson.error || 'No fue posible obtener los datos del usuario.');
            }
           
            const usuarioAEditar = cuerpoJson.data[posicionUsuario];
            if (!usuarioAEditar) {
                throw new Error('El usuario seleccionado no existe.');
            }
           
            activarModoEdicion(posicionUsuario, usuarioAEditar);
        } catch (error) {
            mostrarMensajeDeEstado('error', error.message);
        }
        return;
    }
   
    // Manejar botón de eliminar
    if (nodoBoton.classList.contains('boton-eliminar')) {
        if (!window.confirm('¿Deseas eliminar este usuario?')) return;
       
        try {
            const respuestaHttp = await fetch(`${URL_API_SERVIDOR}?action=delete`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ index: posicionUsuario }),
            });
            const cuerpoJson = await respuestaHttp.json();
            if (!cuerpoJson.ok) {
                throw new Error(cuerpoJson.error || 'No fue posible eliminar el usuario.');
            }
            renderizarTablaDeUsuarios(cuerpoJson.data);
           
            mostrarMensajeDeEstado('ok', 'Usuario eliminado correctamente.');
        } catch (error) {
            mostrarMensajeDeEstado('error', error.message);
        }
    }
});
obtenerYMostrarListadoDeUsuarios();

const btnLogout = document.getElementById('btn-logout');
const userRoleStored = localStorage.getItem('userRole');
if (btnLogout) {
    btnLogout.hidden = !userRoleStored;
    btnLogout.addEventListener('click', () => {
        // Eliminar role y cualquier dato de sesión necesario
        localStorage.removeItem('userRole');
        // Redirigir al login
        window.location.href = '/login.html';
    });
}