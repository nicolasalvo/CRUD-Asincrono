# Mini CRUD AJAX: Implementación del Botón Editar

En este documento explico cómo he añadido la funcionalidad para editar un usuario existente sin necesidad de recargar la página.

Mi idea era que cuando el usuario hiciera clic en "Editar", el formulario de "Agregar nuevo usuario" se transformara en un formulario de "Editar usuario", se rellenara con los datos de la persona seleccionada y, al enviarlo, actualizara la información en el servidor.

## El Flujo: ¿Cómo lo he hecho funcionar?

Para explicarlo, imaginé que la página web (el **frontend**, mi `main.js`) y el servidor (el **backend**, mi `api.php`) son dos personas que hablan por teléfono.

1.  **Usuario hace clic en "Editar"**: La página le dice al servidor: "Oye, dame los datos del usuario de la fila 3".
2.  **Servidor responde**: El servidor busca los datos y se los devuelve: "Claro, se llama 'Juan' y su email es 'juan@correo.com'".
3.  **La página se prepara**: El formulario cambia de "Agregar" a "Editar" y se rellena con los datos de Juan.
4.  **Usuario modifica y guarda**: El usuario cambia el nombre a "Juan Pérez" y hace clic en "Actualizar usuario".
5.  **La página informa al servidor**: La página llama de nuevo al servidor y le dice: "Actualiza al usuario de la fila 3 con este nuevo nombre: 'Juan Pérez'".
6.  **Servidor confirma**: El servidor guarda el cambio y me responde: "¡Hecho! Aquí tienes la lista actualizada de todos los usuarios".
7.  **La página se refresca**: La tabla se redibuja con la nueva información, ¡y todo sin recargar la página!

---

## Parte 1: El Frontend (Lo que ve el usuario)

Aquí es donde ocurre la "magia" que el usuario puede ver. Hice los cambios principales en mi fichero `assets/js/main.js`.

### 1. Añadir el botón "Editar" a cada fila

Lo primero fue modificar la función `renderizarTablaDeUsuarios`. Dentro del bucle que recorre los usuarios, donde construyo el HTML de cada fila, añadí un nuevo botón. A este botón le puse la clase `boton-editar` y, lo más importante, un atributo `data-posicion` al que le asigno la posición del usuario en la lista. Así, cada botón "sabe" a qué fila pertenece.

### 2. Escuchar el clic en "Editar"

Luego, en el `addEventListener` que escucha los clics en la tabla, añadí una nueva condición. Ahora compruebo si el botón que se ha pulsado contiene la clase `boton-editar`. Si es así, significa que el usuario quiere editar. En ese momento, llamo a una nueva función que he creado, `activarModoEdicion`, y le paso la posición y los datos del usuario que se va a modificar.

### 3. Activar el "Modo Edición"

La función `activarModoEdicion` que creé se encarga de transformar el formulario. Primero, pongo una variable global que he llamado `modoEdicion` a `true`. Esto me servirá como un interruptor para que el resto del código sepa que estamos editando. Después, relleno los campos del formulario con el nombre y el email del usuario. Finalmente, cambio el texto del botón de "Agregar usuario" a "Actualizar usuario" y el título del formulario a "Editar usuario".

### 4. Enviar los datos actualizados

El último paso en el frontend fue modificar el `addEventListener` del formulario. Ahora, antes de enviar los datos, compruebo si mi variable `modoEdicion` es `true`. Si lo es, significa que estamos actualizando. En ese caso, preparo la URL para que apunte a la acción `edit` y añado el `index` del usuario a los datos que se van a enviar. Si `modoEdicion` es `false`, todo funciona como antes, creando un usuario nuevo con la acción `create`.

---

## Parte 2: El Backend (El trabajo sucio en el servidor)

Mi servidor `api.php` ahora tiene que ser capaz de entender la orden `action=edit`.

### 1. Recibir y procesar la petición de edición

Para ello, añadí un nuevo bloque `if` en `api.php`. Esta nueva sección se activa solo si el método de la petición es `POST` y la acción solicitada es `edit`. Dentro de este bloque, leo los datos que me llegan (nombre, email y el `index` del usuario a editar). Tras validar que los datos son correctos (que no están vacíos, que el email es válido, etc.), busco al usuario en mi lista usando el `index`. Una vez encontrado, simplemente reemplazo sus datos antiguos por los nuevos, guardo la lista completa de usuarios de nuevo en el fichero `data.json` y devuelvo la lista actualizada para que el frontend la pueda mostrar.

¡Y eso es todo! Conectando estas piezas del frontend y el backend, conseguí una funcionalidad de edición moderna y fluida para el usuario.