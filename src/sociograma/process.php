<?php
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$input = [
    'nombre'            => trim($_POST['nombre'] ?? ''),
    'apellidos'         => trim($_POST['apellidos'] ?? ''),
    'curso'             => trim($_POST['curso'] ?? ''),
    'correoElectronico' => trim($_POST['correoElectronico'] ?? ''),
    'fechaNacimiento'   => trim($_POST['fechaNacimiento'] ?? ''),

    'companero_trabajo_nombre'    => trim($_POST['companero_trabajo_nombre'] ?? ''),
    'companero_trabajo_apellidos' => trim($_POST['companero_trabajo_apellidos'] ?? ''),
    'companero_trabajo_curso'     => trim($_POST['companero_trabajo_curso'] ?? ''),
    'amigo_nombre'               => trim($_POST['amigo_nombre'] ?? ''),
    'amigo_apellidos'            => trim($_POST['amigo_apellidos'] ?? ''),
    'amigo_curso'                => trim($_POST['amigo_curso'] ?? ''),
    'confianza_nombre'           => trim($_POST['confianza_nombre'] ?? ''),
    'confianza_apellidos'        => trim($_POST['confianza_apellidos'] ?? ''),
    'confianza_curso'            => trim($_POST['confianza_curso'] ?? ''),
    'clase_preferida'            => $_POST['clase_preferida'] ?? '',
    
    'rol_preferido'     => $_POST['rol_preferido'] ?? '',
    'rol_main'          => $_POST['rol_main'] ?? '',
    'numeroGrado'       => trim($_POST['numeroGrado'] ?? ''),
    
    'tipo_comunicacion' => $_POST['tipo_comunicacion'] ?? '',
    'herramientas'      => $_POST['herramientas'] ?? [],
    'herramienta_otra'  => trim($_POST['herramienta_otra'] ?? ''),
    
    'gestion_tiempo'    => $_POST['gestion_tiempo'] ?? '',
    'numeroEstres'      => trim($_POST['numeroEstres'] ?? ''),
    
    'color_equipo'      => trim($_POST['color_equipo'] ?? '#ff0000'),
    'sistemaOperativo'  => trim($_POST['sistemaOperativo'] ?? ''),
    'comentario'        => trim($_POST['comentario'] ?? '')
];


$errors = [];

if (empty($input['nombre'])) {
    $errors['nombre'] = 'El nombre es obligatorio';
}

if (empty($input['apellidos'])) {
    $errors['apellidos'] = 'Los apellidos son obligatorios';
}

if (empty($input['curso'])) {
    $errors['curso'] = 'El curso es obligatorio';
}

if (!filter_var($input['correoElectronico'], FILTER_VALIDATE_EMAIL)) {
    $errors['correoElectronico'] = 'El correo electrónico no tiene un formato válido';
}

if (empty($input['fechaNacimiento'])) {
    $errors['fechaNacimiento'] = 'La fecha de nacimiento es obligatoria';
}

if (empty($input['companero_trabajo_nombre'])) {
    $errors['companero_trabajo_nombre'] = 'El nombre del compañero es obligatorio';
}
if (empty($input['companero_trabajo_apellidos'])) {
    $errors['companero_trabajo_apellidos'] = 'Los apellidos del compañero son obligatorios';
}
if (empty($input['companero_trabajo_curso'])) {
    $errors['companero_trabajo_curso'] = 'El curso del compañero es obligatorio';
}

if (empty($input['clase_preferida'])) {
    $errors['clase_preferida'] = 'Debes seleccionar una clase preferida';
}

if (empty($input['rol_preferido'])) {
    $errors['rol_preferido'] = 'Debes seleccionar un rol preferido';
}
if (empty($input['rol_main'])) {
    $errors['rol_main'] = 'Debes seleccionar un rol main';
}

if ($input['numeroGrado'] === '' || $input['numeroGrado'] < 0 || $input['numeroGrado'] > 100) {
    $errors['numeroGrado'] = 'El grado de satisfacción debe ser un número entre 0 y 100';
}

if (empty($input['tipo_comunicacion'])) {
    $errors['tipo_comunicacion'] = 'Debes seleccionar un tipo de comunicación';
}

if (empty($input['gestion_tiempo'])) {
    $errors['gestion_tiempo'] = 'Debes seleccionar tu nivel de gestión del tiempo';
}

if ($input['numeroEstres'] === '' || $input['numeroEstres'] < 1 || $input['numeroEstres'] > 5) {
    $errors['numeroEstres'] = 'El nivel de estrés debe ser un número entre 1 y 5';
}

if (empty($input['sistemaOperativo'])) {
    $errors['sistemaOperativo'] = 'Debes seleccionar un sistema operativo';
}
if (empty($input['fechaNacimiento'])) {
    $errors['fechaNacimiento'] = 'La data de naixement és obligatòria.';
}
if ($input['numeroGrado'] === '' || $input['numeroGrado'] < 0 || $input['numeroGrado'] > 100) {
    $errors['numeroGrado'] = 'La satisfacció ha de ser un número entre 0 i 100.';
}
if ($input['numeroEstres'] === '' || $input['numeroEstres'] < 1 || $input['numeroEstres'] > 5) {
    $errors['numeroEstres'] = 'El nivell d\'estrès ha de ser un número entre 1 i 5.';
}
if (empty($input['sistemaOperativo'])) {
    $errors['sistemaOperativo'] = 'Has de seleccionar un sistema operatiu.';
}

if (!empty($errors)) {
    session_start();
    $_SESSION['old_field'] = $input;
    $_SESSION['errors'] = $errors;

    header('Location: index.php');
    exit; 
}

$file_path = __DIR__ . '/data/sociograma.json';
$existing_data = load_json($file_path);

$new_record = $input;
$new_record['timestamp'] = date('Y-m-d H:i:s');

$existing_data[] = $new_record;

if (!save_json($file_path, $existing_data)) {
    http_response_code(500);
    echo "Hi ha hagut un error en guardar les dades. Intenta-ho més tard.";
    exit;
}

include __DIR__ . '/includes/header.php';
?>
<div class="confirmation-message" style="padding: 20px; text-align: center;">
    <h2>Gràcies!</h2>
    <p>La teva resposta s'ha guardat correctament.</p>
    <p>Total de respostes recollides: <strong><?= count($existing_data) ?></strong></p>
    <a href="index.php" class="button" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #337ab7; color: white; text-decoration: none; border-radius: 5px;">Tornar al formulari</a>
</div>
<?php
include __DIR__ . '/includes/footer.php';
?>