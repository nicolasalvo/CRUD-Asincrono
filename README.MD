# Mini CRUD AJAX: Implementación del Botón Editar

En este documento explico cómo he añadido la funcionalidad para editar un usuario existente sin necesidad de recargar la página.

Mi idea era que cuando el usuario hiciera clic en "Editar", el formulario de "Agregar nuevo usuario" se transformara en un formulario de "Editar usuario", se rellenara con los datos de la persona seleccionada y, al enviarlo, actualizara la información en el servidor.

## El Flujo: ¿Cómo funciona paso a paso?

Para explicarlo, imaginé que la página web (el **frontend**, mi `main.js`) y el servidor (el **backend**, mi `api.php`) son dos personas que hablan por teléfono.

1.  **Usuario hace clic en "Editar"**: La página le dice al servidor: "Oye, dame los datos del usuario de la fila 3".
2.  **Servidor responde**: El servidor busca los datos y se los devuelve: "Claro, se llama 'Juan' y su email es 'juan@correo.com'".
3.  **La página se prepara**: El formulario cambia de "Agregar" a "Editar" y se rellena con los datos de Juan.
4.  **Usuario modifica y guarda**: El usuario cambia el nombre a "Juan Pérez" y hace clic en "Actualizar usuario".
5.  **La página informa al servidor**: La página llama de nuevo al servidor y le dice: "Actualiza al usuario de la fila 3 con este nuevo nombre: 'Juan Pérez'".
6.  **Servidor confirma**: El servidor guarda el cambio y me responde: "¡Hecho! Aquí tienes la lista actualizada de todos los usuarios".
7.  **La página se refresca**: La tabla se redibuja con la nueva información, ¡y todo sin recargar la página!

---

## Parte 1: El Frontend 

Hice los cambios principales en `assets/js/main.js` y un poco en `index_ajax.html`.

### 1. Añadir el botón "Editar" a cada fila

Cuando creo la tabla de usuarios, ahora también añado un botón de "Editar" junto al de "Eliminar". Le puse un atributo especial `data-posicion` para saber a qué fila pertenece.

**Fichero**: `c/xampp/htdocs/RA2_PR1/src/assets/js/main.js`
```javascript
function renderizarTablaDeUsuarios(arrayUsuarios) {
    // ... código para limpiar la tabla ...
    arrayUsuarios.forEach((usuario, posicionEnLista) => {
        const nodoFila = document.createElement('tr');
        nodoFila.innerHTML = `
<td>${posicionEnLista + 1}</td>
<td>${convertirATextoSeguro(usuario?.nombre ?? '')}</td>
<td>${convertirATextoSeguro(usuario?.email ?? '')}</td>
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
```

### 2. Escuchar el clic en "Editar"

He modificado el "escuchador de eventos" de la tabla para que, si se hace clic en un botón con la clase `boton-editar`, se active el "modo edición".

**Fichero**: `c/xampp/htdocs/RA2_PR1/src/assets/js/main.js`
```javascript
nodoCuerpoTablaUsuarios?.addEventListener('click', async (evento) => {
    // ...
    // Manejar botón de editar
    if (nodoBoton.classList.contains('boton-editar')) {
        // ... obtenemos los datos del usuario del servidor ...
        const usuarioAEditar = cuerpoJson.data[posicionUsuario];
        // ¡Activamos el modo edición!
        activarModoEdicion(posicionUsuario, usuarioAEditar);
    }
    // ...
});
```

### 3. Activar el "Modo Edición"

La función `activarModoEdicion` que creé se encarga de transformar el formulario:

-   Rellena los campos de nombre y email.
-   Cambia el texto del botón de "Agregar" a "Actualizar".
-   Cambia el título del formulario.

**Fichero**: `c/xampp/htdocs/RA2_PR1/src/assets/js/main.js`
```javascript
function activarModoEdicion(indiceUsuario, datosUsuario) {
    modoEdicion = true; // Una variable global que nos dice que estamos editando
    indiceUsuarioEnEdicion = indiceUsuario;
   
    // Llenar el formulario con los datos del usuario
    document.getElementById('campo-nombre').value = datosUsuario.nombre || '';
    document.getElementById('campo-email').value = datosUsuario.email || '';
   
    // Cambiar el texto del botón y el título del formulario
    document.getElementById('boton-agregar-usuario').textContent = 'Actualizar usuario';
    document.getElementById('titulo-formulario').textContent = 'Editar usuario';
}
```

### 4. Enviar los datos actualizados

Cuando el usuario pulsa "Actualizar usuario", el formulario se envía. Gracias a mi variable `modoEdicion`, el código sabe que no tiene que crear un usuario nuevo, sino actualizar uno existente.

Fíjate cómo le paso al servidor la acción `edit` y el `index` (la posición) del usuario que quiero cambiar.

**Fichero**: `c/xampp/htdocs/RA2_PR1/src/assets/js/main.js`
```javascript
formularioAltaUsuario?.addEventListener('submit', async (evento) => {
    // ...
    if (modoEdicion) {
        // Modo edición
        url = `${URL_API_SERVIDOR}?action=edit`; // ¡La acción correcta!
        datosUsuario.index = indiceUsuarioEnEdicion; // Le decimos cuál editar
        mensajeExito = 'Usuario actualizado correctamente.';
    } else {
        // Modo creación (como estaba antes)
        url = `${URL_API_SERVIDOR}?action=create`;
        mensajeExito = 'Usuario agregado correctamente.';
    }

    // Hacemos la llamada al servidor con los datos
    const respuestaHttp = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datosUsuario),
    });
    // ...
});
```

## Parte 2: El Backend 

### 1. Recibir y procesar la petición de edición

Añadí un nuevo bloque `if` en `api.php` que se activa solo si el método es `POST` y la acción es `edit`.

Este bloque:
-   Lee los datos que le envía el JavaScript (`nombre`, `email` y el `index`).
-   Valida que los datos sean correctos (que no estén vacíos, que el email sea válido, etc.).
-   Busca al usuario en la lista usando el `index`.
-   Reemplaza los datos antiguos por los nuevos.
-   Guarda toda la lista de usuarios de nuevo en el fichero `data.json`.
-   Devuelve la lista actualizada para que el frontend la pueda mostrar.

**Fichero**: `c/xampp/htdocs/RA2_PR1/src/api.php`
```php
// Metodo de editar usuario y/o correo
// Editar usuario: POST /api.php?action=edit
if ($metodoHttpRecibido === 'POST' && $accionSolicitada === 'edit') {
    // ... (aquí se leen los datos: index, nombre, email)

    // ... (aquí se valida que todo esté correcto)

    // Editamos y persistimos (guardamos el email normalizado)
    $listaUsuarios[$indiceUsuarioAEditar] = [
        'nombre' => $nombreUsuarioEditado,
        'email' => $correoUsuarioNormalizado,
    ];
    file_put_contents(
        $rutaArchivoDatosJson,
        json_encode($listaUsuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n"
    );
    responder_json_exito($listaUsuarios, 200); // Enviamos la lista actualizada
}
```