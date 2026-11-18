<?php

session_start();

require_once "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["rol"] !== "admin") {
    header("Location: panel.php");
    exit;
}

$id = $_GET["id"] ?? "";

if ($id === "" || !ctype_digit($id)) {
    header("Location: admin_usuarios.php");
    exit;
}

$consulta = $conexion->prepare(
    "SELECT id, nombre, apellido, dni, email, rol
     FROM usuarios
     WHERE id = ? AND archivado_en IS NULL"
);

$consulta->execute([$id]);

$usuario = $consulta->fetch();

if (!$usuario) {
    header("Location: admin_usuarios.php");
    exit;
}

$errores = [];

$nombre = $usuario["nombre"];
$apellido = $usuario["apellido"];
$dni = $usuario["dni"];
$email = $usuario["email"];
$rol = $usuario["rol"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $apellido = trim($_POST["apellido"] ?? "");
    $dni = trim($_POST["dni"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $rol = $_POST["rol"] ?? "usuario";

    if ($nombre === "") {
        $errores[] = "El nombre es obligatorio.";
    }

    if ($apellido === "") {
        $errores[] = "El apellido es obligatorio.";
    }

    if ($dni === "") {
        $errores[] = "El DNI es obligatorio.";
    }

    if ($email === "") {
        $errores[] = "El email es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El email no es válido.";
    }

    if ($rol !== "usuario" && $rol !== "admin") {
        $errores[] = "El rol seleccionado no es válido.";
    }

    if (empty($errores)) {

        $consulta = $conexion->prepare(
            "SELECT id
             FROM usuarios
             WHERE (dni = ? OR email = ?)
             AND id != ?"
        );

        $consulta->execute([
            $dni,
            $email,
            $id
        ]);

        if ($consulta->fetch()) {
            $errores[] = "El DNI o el email ya pertenecen a otro usuario.";
        }
    }

    if (empty($errores)) {

        $consulta = $conexion->prepare(
            "UPDATE usuarios
             SET nombre = ?,
                 apellido = ?,
                 dni = ?,
                 email = ?,
                 rol = ?
             WHERE id = ?"
        );

        $consulta->execute([
            $nombre,
            $apellido,
            $dni,
            $email,
            $rol,
            $id
        ]);

        header("Location: admin_usuarios.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Editar usuario | Fiter</title>

<link rel="stylesheet" href="styles.css">

</head>

<body>

<header class="header">

<div class="container header-content">

<div class="logo">
<img src="img/logo-fiter.png" alt="Logo de Fiter">
</div>

<nav class="nav">
<a href="admin_usuarios.php">Usuarios</a>
<a href="cerrar_sesion.php">Cerrar sesión</a>
</nav>

</div>

</header>

<main class="main">

<section class="container">

<div class="card">

<h1>Editar usuario</h1>

<p>
Modificá los datos del usuario seleccionado.
</p>

<br>

<?php if (!empty($errores)): ?>

<div class="alert error">

<?php foreach ($errores as $error): ?>

<p>
<?= htmlspecialchars($error) ?>
</p>

<?php endforeach; ?>

</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">

<label for="nombre">Nombre</label>

<input
type="text"
id="nombre"
name="nombre"
value="<?= htmlspecialchars($nombre) ?>"
required
>

</div>

<div class="form-group">

<label for="apellido">Apellido</label>

<input
type="text"
id="apellido"
name="apellido"
value="<?= htmlspecialchars($apellido) ?>"
required
>

</div>

<div class="form-group">

<label for="dni">DNI</label>

<input
type="text"
id="dni"
name="dni"
value="<?= htmlspecialchars($dni) ?>"
required
>

</div>

<div class="form-group">

<label for="email">Email</label>

<input
type="email"
id="email"
name="email"
value="<?= htmlspecialchars($email) ?>"
required
>

</div>

<div class="form-group">

<label for="rol">Rol</label>

<select id="rol" name="rol">

<option
value="usuario"
<?= $rol === "usuario" ? "selected" : "" ?>
>
Usuario
</option>

<option
value="admin"
<?= $rol === "admin" ? "selected" : "" ?>
>
Administrador
</option>

</select>

</div>

<button type="submit" class="btn">
Guardar cambios
</button>

<a href="admin_usuarios.php" class="btn">
Cancelar
</a>

</form>

</div>

</section>

</main>

<footer class="footer">
<p>Fiter &copy; 2026 - Sistema de gestión de usuarios</p>
</footer>

</body>

</html>