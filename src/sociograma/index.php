<?php
require __DIR__ . '/includes/functions.php';

session_start();
include __DIR__ . '/includes/header.php';

// Priorizar valores pasados en scope, luego los guardados en sesión y por último $_POST
$old_field = $old_field ?? $_SESSION['old_field'] ?? $_POST ?? [];
$errors = $errors ?? $_SESSION['errors'] ?? [];

// Limpiar sesión para que los valores no persistan indefinidamente
unset($_SESSION['old_field'], $_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <fieldset class="fieldCompleto">
        <div class="inicio">
            <h2>Bienvenido al sociograma</h2>
            <p>Hoy realizaremos una actividad para conocer mejor cómo nos relacionamos dentro del grupo. El objetivo es identificar amistades, preferencias, apoyos y formas de comunicación entre todos. No hay respuestas “buenas” o “malas”: lo importante es ser honestos y respetuosos al marcar a las personas con las que te sientes más cómodo, con quienes trabajas mejor o en quienes confías.
                <br><br>La información que recojamos nos ayudará a fortalecer la convivencia, mejorar el trabajo en equipo y entender mejor las dinámicas del grupo. ¡Participa con tranquilidad y sin presiones!
            </p>
        </div>
    <form class="identificacion" action="process.php" method="POST" novalidate>
            <fieldset class="field">
                <legend>
                    <h3 class="fieldTitulo">Identificación del alumno</h3>
                </legend>
                <div class="questionBlock">
                    <p>Nombre *</p>
                    <label class="sr-only">Nombre</label>
                    <input name="nombre" class="inputQuestion identificacionNombre" type="text" placeholder="Nombre" value="<?= old_field('nombre', $old_field) ?>" required>
                    <?= field_error('nombre', $errors) ?>
                </div>
                <div class="questionBlock">
                    <p>Apellidos *</p>
                    <label class="sr-only">Apellidos</label>
                    <input name="apellidos" class="inputQuestion identificacionNombre" type="text" placeholder="Apellidos" value="<?= old_field('apellidos', $old_field) ?>" required>
                    <?= field_error('apellidos', $errors) ?>
                </div>
                <div class="questionBlock">
                    <p>Curso *</p>
                    <label class="sr-only">Curso</label>
                    <input name="curso" class="inputQuestion identificacionCurso" type="text" placeholder="Curso" value="<?= old_field('curso', $old_field) ?>" required>
                    <?= field_error('curso', $errors) ?>
                </div>
                <div class="questionBlock">
                    <p>Correo electrónico *</p>
                    <label class="sr-only">Correo Electrónico</label>
                    <input name="correoElectronico" class="inputQuestion identificacionCorreo" type="email" placeholder="Correo Electrónico" value="<?= old_field('correoElectronico', $old_field) ?>" required>
                    <?= field_error('correoElectronico', $errors) ?>
                </div>
                <div class="questionBlock">
                    <p>Fecha de nacimiento *</p>
                    <input name="fechaNacimiento" class="inputQuestion identificacionFecha" type="date" placeholder="Fecha" value="<?= old_field('fechaNacimiento', $old_field) ?>" required>
                    <?= field_error('fechaNacimiento', $errors) ?>
                </div>
            </fieldset>
            <fieldset class="field">
                <legend>
                    <h3 class="fieldTitulo">Preferencias de colaboración</h3>
                </legend>
                <div class="questionBlock">
                    <h4 class="fieldQuestion">1. ¿Con quién te gusta trabajar o sentarte en clase?</h4>
                    <input name="companero_trabajo_nombre" class="inputQuestion" type="text" placeholder="Nombre" value="<?= old_field('companero_trabajo_nombre', $old_field) ?>" required>
                    <?= field_error('companero_trabajo_nombre', $errors) ?>
                    <input name="companero_trabajo_apellidos" class="inputQuestion" type="text" placeholder="Apellidos" value="<?= old_field('companero_trabajo_apellidos', $old_field) ?>" required>
                    <?= field_error('companero_trabajo_apellidos', $errors) ?>
                    <input name="companero_trabajo_curso" class="inputQuestion" type="text" placeholder="Curso" value="<?= old_field('companero_trabajo_curso', $old_field) ?>" required>
                    <?= field_error('companero_trabajo_curso', $errors) ?>
                </div>

                <div class="questionBlock">
                    <h4 class="fieldQuestion">2. ¿Quiénes son tus amigos dentro del grupo?</h4>
                    <input name="amigo_nombre" class="inputQuestion" type="text" placeholder="Nombre" value="<?= old_field('amigo_nombre', $old_field) ?>" required>
                    <?= field_error('amigo_nombre', $errors) ?>
                    <input name="amigo_apellidos" class="inputQuestion" type="text" placeholder="Apellidos" value="<?= old_field('amigo_apellidos', $old_field) ?>" required>
                    <?= field_error('amigo_apellidos', $errors) ?>
                    <input name="amigo_curso" class="inputQuestion" type="text" placeholder="Curso" value="<?= old_field('amigo_curso', $old_field) ?>" required>
                    <?= field_error('amigo_curso', $errors) ?>
                </div>

                <div class="questionBlock">
                    <h4 class="fieldQuestion">3. ¿En quién confías si tienes un problema?</h4>
                    <input name="confianza_nombre" class="inputQuestion" type="text" placeholder="Nombre" value="<?= old_field('confianza_nombre', $old_field) ?>" required>
                    <?= field_error('confianza_nombre', $errors) ?>
                    <input name="confianza_apellidos" class="inputQuestion" type="text" placeholder="Apellidos" value="<?= old_field('confianza_apellidos', $old_field) ?>" required>
                    <?= field_error('confianza_apellidos', $errors) ?>
                    <input name="confianza_curso" class="inputQuestion" type="text" placeholder="Curso" value="<?= old_field('confianza_curso', $old_field) ?>" required>
                    <?= field_error('confianza_curso', $errors) ?>
                </div>

                <div class="questionBlock">
                    <h4 class="fieldQuestion">4. Si tuvieras que decir que clase te motiva más. ¿Cuál sería?</h4>
                    <div>
                        <input name="clase_preferida" type="radio" id="clase_entorn" class="identificacionNombre" value="Entorn Servidor" <?= old_field('clase_preferida', $old_field) === 'Entorn Servidor' ? 'checked' : '' ?> >
                        <label for="clase_entorn">Entorn Servidor</label>
                    </div>
                    <div>
                        <input name="clase_preferida" type="radio" id="clase_despl" class="identificacionNombre" value="Desplegament Aplicacions" <?= old_field('clase_preferida', $old_field) === 'Desplegament Aplicacions' ? 'checked' : '' ?> >
                        <label for="clase_despl">Desplegament Aplicacions</label>
                    </div>
                    <div>
                        <input name="clase_preferida" type="radio" id="clase_client" class="identificacionNombre" value="Entorn Client" <?= old_field('clase_preferida', $old_field) === 'Entorn Client' ? 'checked' : '' ?> >
                        <label for="clase_client">Entorn Client</label>
                    </div>
                    <div>
                        <input name="clase_preferida" type="radio" id="clase_disseny" class="identificacionNombre" value="Disseny Interficies" <?= old_field('clase_preferida', $old_field) === 'Disseny Interficies' ? 'checked' : '' ?> >
                        <label for="clase_disseny">Disseny Interfícies</label>
                    </div>
                    <div>
                        <input name="clase_preferida" type="radio" id="clase_ipo" class="identificacionNombre" value="Itinerari Personal" <?= old_field('clase_preferida', $old_field) === 'Itinerari Personal' ? 'checked' : '' ?> >
                        <label for="clase_ipo">Itinerari Personal Ocupabilitat (IPO)</label>
                    </div>
                    <?= field_error('clase_preferida', $errors) ?>
                </div>

            </fieldset>

            <fieldset class="field">
                <legend>
                    <h3 class="fieldTitulo">Rol y Habilidades</h3>
                </legend>
                <div class="questionBlock">
                    <h4 class="fieldQuestion">¿Cuál es tu rol preferido en clase?</h4>
                    <div>
                        <input name="rol_preferido" type="radio" id="rol_pref_frontend" class="identificacionNombre" value="Frontend" <?= old_field('rol_preferido', $old_field) === 'Frontend' ? 'checked' : '' ?> >
                        <label for="rol_pref_frontend">Frontend</label>
                    </div>
                    <div>
                        <input name="rol_preferido" type="radio" id="rol_pref_backend" class="identificacionNombre" value="Backend" <?= old_field('rol_preferido', $old_field) === 'Backend' ? 'checked' : '' ?> >
                        <label for="rol_pref_backend">Backend</label>
                    </div>
                    <div>
                        <input name="rol_preferido" type="radio" id="rol_pref_fullstack" class="identificacionNombre" value="Fullstack" <?= old_field('rol_preferido', $old_field) === 'Fullstack' ? 'checked' : '' ?> >
                        <label for="rol_pref_fullstack">Fullstack</label>
                    </div>
                    <div>
                        <input name="rol_preferido" type="radio" id="rol_pref_devops" class="identificacionNombre" value="Devops" <?= old_field('rol_preferido', $old_field) === 'Devops' ? 'checked' : '' ?> >
                        <label for="rol_pref_devops">Devops</label>
                    </div>
                    <?= field_error('rol_preferido', $errors) ?>
                </div>

                <div class="questionBlock">
                    <h4 class="fieldQuestion">¿Cuál es tu rol main?</h4>
                    <div>
                        <input name="rol_main" type="radio" id="rol_main_frontend" class="identificacionNombre" value="Frontend" <?= old_field('rol_main', $old_field) === 'Frontend' ? 'checked' : '' ?> >
                        <label for="rol_main_frontend">Frontend</label>
                    </div>
                    <div>
                        <input name="rol_main" type="radio" id="rol_main_backend" class="identificacionNombre" value="Backend" <?= old_field('rol_main', $old_field) === 'Backend' ? 'checked' : '' ?> >
                        <label for="rol_main_backend">Backend</label>
                    </div>
                    <div>
                        <input name="rol_main" type="radio" id="rol_main_fullstack" class="identificacionNombre" value="Fullstack" <?= old_field('rol_main', $old_field) === 'Fullstack' ? 'checked' : '' ?> >
                        <label for="rol_main_fullstack">Fullstack</label>
                    </div>
                    <div>
                        <input name="rol_main" type="radio" id="rol_main_devops" class="identificacionNombre" value="Devops" <?= old_field('rol_main', $old_field) === 'Devops' ? 'checked' : '' ?> >
                        <label for="rol_main_devops">Devops</label>
                    </div>
                    <?= field_error('rol_main', $errors) ?>
                </div>

                <div class="questionBlock">
                    <h4 class="fieldQuestion">Del 0 al 100, cuál es tu grado se satisfacción en el proyecto grupal.</h4>
                    <input name="numeroGrado" class="inputQuestion fieldNumber" type="number" min="0" max="100" placeholder="Número entre 0 y 100" value="<?= old_field('numeroGrado', $old_field) ?>" required>
                    <?= field_error('numeroGrado', $errors) ?>
                </div>

            </fieldset>

            <fieldset class="field">
                <legend>
                    <h3 class="fieldTitulo">Dinámica y Comunicación</h3>
                </legend>
                <div class="questionBlock">
                    <h4 class="fieldQuestion">¿Qué tipo de comunicación prefieres para trabajar en equipo?</h4>
                    <div>
                        <input name="tipo_comunicacion" type="radio" id="com_sincrona" class="identificacionNombre" value="Sincrona" <?= old_field('tipo_comunicacion', $old_field) === 'Sincrona' ? 'checked' : '' ?> >
                        <label for="com_sincrona">Síncrona (en tiempo real, como videollamadas o reuniones)</label>
                    </div>
                    <div>
                        <input name="tipo_comunicacion" type="radio" id="com_asincrona" class="identificacionNombre" value="Asincrona" <?= old_field('tipo_comunicacion', $old_field) === 'Asincrona' ? 'checked' : '' ?> >
                        <label for="com_asincrona">Asíncrona (mensajes, correos o tareas que se responden después)</label>
                    </div>
                    <div>
                        <input name="tipo_comunicacion" type="radio" id="com_mixta" class="identificacionNombre" value="Mixta" <?= old_field('tipo_comunicacion', $old_field) === 'Mixta' ? 'checked' : '' ?> >
                        <label for="com_mixta">Mixta (una combinación de ambas)</label>
                    </div>
                    <?= field_error('tipo_comunicacion', $errors) ?>
                </div>

                <div class="questionBlock">
                    <h4 class="fieldQuestion">¿Qué herramientas sueles utilizar o prefieres para organizar el trabajo en equipo? (puedes seleccionar más de una)</h4>
                    <checkboxes>
                        <div>
                            <input name="herramientas[]" type="checkbox" id="herr_github" class="identificacionCheckbox" value="GitHub" <?= in_array('GitHub', $old_field['herramientas'] ?? [], true) ? 'checked' : '' ?> >
                            <label for="herr_github">GitHub</label>
                        </div>
                        <div>
                            <input name="herramientas[]" type="checkbox" id="herr_discord" class="identificacionCheckbox" value="Discord" <?= in_array('Discord', $old_field['herramientas'] ?? [], true) ? 'checked' : '' ?> >
                            <label for="herr_discord">Discord</label>
                        </div>
                        <div>
                            <input name="herramientas[]" type="checkbox" id="herr_slack" class="identificacionCheckbox" value="Slack" <?= in_array('Slack', $old_field['herramientas'] ?? [], true) ? 'checked' : '' ?> >
                            <label for="herr_slack">Slack</label>
                        </div>
                        <div>
                            <input name="herramientas[]" type="checkbox" id="herr_teams" class="identificacionCheckbox" value="Microsoft Teams" <?= in_array('Microsoft Teams', $old_field['herramientas'] ?? [], true) ? 'checked' : '' ?> >
                            <label for="herr_teams">Microsoft Teams</label>
                        </div>
                        <div>
                            <input name="herramientas[]" type="checkbox" id="herr_drive" class="identificacionCheckbox" value="Google Drive" <?= in_array('Google Drive', $old_field['herramientas'] ?? [], true) ? 'checked' : '' ?> >
                            <label for="herr_drive">Google Drive</label>
                        </div>
                        <div>
                            <input name="herramientas[]" type="checkbox" id="herr_otra" class="identificacionCheckbox" value="Otra" <?= in_array('Otra', $old_field['herramientas'] ?? [], true) ? 'checked' : '' ?> >
                            <label for="herr_otra">Otra (especificar)</label>
                            <input name="herramienta_otra" class="inputQuestion" type="text" placeholder="Especificar otra herramienta" value="<?= old_field('herramienta_otra', $old_field) ?>">
                        </div>
                    <?= field_error('herramientas', $errors) ?>
                </div>

            </fieldset>

            <fieldset class="field">
                <legend>
                    <h3 class="fieldTitulo">Organización y bienestar</h3>
                </legend>

                <div class="questionBlock">
                    <h4 class="fieldQuestion">¿Cómo valorarías tu nivel de gestión del tiempo al trabajar en proyectos o tareas de grupo?</h4>
                    <div>
                        <input name="gestion_tiempo" type="radio" id="gestion_baja" class="identificacionNombre" value="Baja" <?= old_field('gestion_tiempo', $old_field) === 'Baja' ? 'checked' : '' ?> >
                        <label for="gestion_baja">Baja (me cuesta organizarme o cumplir plazos)</label>
                    </div>
                    <div>
                        <input name="gestion_tiempo" type="radio" id="gestion_media" class="identificacionNombre" value="Media" <?= old_field('gestion_tiempo', $old_field) === 'Media' ? 'checked' : '' ?> >
                        <label for="gestion_media">Media (a veces me organizo bien, pero puedo mejorar)</label>
                    </div>
                    <div>
                        <input name="gestion_tiempo" type="radio" id="gestion_alta" class="identificacionNombre" value="Alta" <?= old_field('gestion_tiempo', $old_field) === 'Alta' ? 'checked' : '' ?> >
                        <label for="gestion_alta">Alta (soy constante y cumplo los tiempos con facilidad)</label>
                    </div>
                    <?= field_error('gestion_tiempo', $errors) ?>
                </div>

                <div class="questionBlock">
                    <h4 class="fieldQuestion">En una escala del 1 al 5, donde 1 es "nada de estrés" y 5 es "mucho estrés", ¿cómo te has sentido durante este proyecto o trabajo en equipo?</h4>
                    <div>
                        <input name="numeroEstres" class="inputQuestion fieldNumber" type="number" min="1" max="5" placeholder="Número entre 1 y 5" value="<?= old_field('numeroEstres', $old_field) ?>" required>
                        <?= field_error('numeroEstres', $errors) ?>
                    </div>
                </div>

            </fieldset>

            <fieldset class="field">
                <legend>
                    <h3 class="fieldTitulo">Logística</h3>
                </legend>

                <div class="questionBlock">
                    <h4 class="fieldQuestion">Elige el color principal de tu equipo (por ejemplo, para representar tu grupo o identificarte visualmente)</h4>
                    <div>
                        <input name="color_equipo" type="color" id="color_equipo" class="identificacionNombre" value="<?= old_field('color_equipo', $old_field) ?: '#ff0000' ?>">
                        <?= field_error('color_equipo', $errors) ?>
                    </div>
                </div>

                <div class="questionBlock">
                    <h4 class="fieldQuestion">¿Qué sistema operativo prefieres usar para trabajar o estudiar?</h4>
                    <div>
                        <select class="inputQuestion" name="sistemaOperativo" required>
                            <option value="">Selecciona un sistema operativo</option>
                            <option value="windows" <?= old_field('sistemaOperativo', $old_field) === 'windows' ? 'selected' : '' ?>>Windows</option>
                            <option value="macos" <?= old_field('sistemaOperativo', $old_field) === 'macos' ? 'selected' : '' ?>>macOS</option>
                            <option value="linux" <?= old_field('sistemaOperativo', $old_field) === 'linux' ? 'selected' : '' ?>>Linux</option>
                        </select>
                        <?= field_error('sistemaOperativo', $errors) ?>
                    </div>
                </div>

            </fieldset>

            <fieldset class="field">
                <legend>
                    <h3 class="fieldTitulo">Logística</h3>
                </legend>

                <div class="questionBlock">
                    <h4 class="fieldQuestion">¿Tienes algun comentario o observacion que quieras decir?</h4>
                    <div>
                        <textarea name="comentario" id="comentarioObservacion" placeholder="Observaciones o comentarios..."></textarea>
                    </div>
                </div>

            </fieldset>

            <button type="submit" class="enviar">Enviar</button>
        </form>

    </fieldset>
</body>
<?php
include __DIR__ . '/includes/footer.php';
?>

</html>
<style scoped>
    .inicio {
        margin: 20px;
        padding: 20px;
        border-radius: 10px;
        background-color: rgba(194, 194, 194, 0.16);
    }

    .identificacion {
        margin: 20px;
        padding: 20px;
        border: 2px solid black;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .enviar {
        margin: 20px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: rgba(9, 65, 110, 1);
        color: white;
        font-size: 16px;
        cursor: pointer;
    }

    .enviar:hover {
        background-color: rgba(93, 148, 194, 1);
    }

    .identificacionCheckbox {
        margin-right: 8px;
        margin-bottom: 10px;
    }

    .fieldTitulo {
        color: rgba(9, 65, 110, 1);
    }

    .field {
        margin: 20px;
        padding: 20px;
        border: 2px solid black;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        width: 90%;
        border-color: rgba(32, 93, 143, 1);
        background-color: rgba(179, 179, 179, 0.16);
    }

    .fieldNumber {
        width: 150px;
    }

    .fieldQuestion {
        margin: 0 0 6px 0;
        font-weight: 600;
    }

    .questionBlock {
        display: flex;
        flex-direction: column;
        gap: 6px;
        width: 100%;
    }

    .inputQuestion {
        padding: 8px 10px;
        border: 1px solid #aaa;
        border-radius: 10px;
        width: 100%;
        box-sizing: border-box;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    textarea {
        width: 100%;
    }

    .field-error {
        color: #c00;
        margin: 4px 0 0 0;
        font-size: 0.95em;
    }
</style>