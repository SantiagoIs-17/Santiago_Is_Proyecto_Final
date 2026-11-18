<?php

require_once "conexion.php";

$errores = [];

$nombre = "";
$apellido = "";
$dni = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $apellido = trim($_POST["apellido"] ?? "");
    $dni = trim($_POST["dni"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

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

    if ($password === "") {
        $errores[] = "La contraseña es obligatoria.";
    } elseif (strlen($password) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    if (empty($errores)) {

        $consulta = $conexion->prepare(
            "SELECT id FROM usuarios WHERE dni = ? OR email = ?"
        );

        $consulta->execute([$dni, $email]);

        if ($consulta->fetch()) {
            $errores[] = "El DNI o el email ya están registrados.";
        }
    }

    if (empty($errores)) {

        $password_hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $consulta = $conexion->prepare(
            "INSERT INTO usuarios
            (nombre, apellido, dni, email, password_hash)
            VALUES (?, ?, ?, ?, ?)"
        );

        $consulta->execute([
            $nombre,
            $apellido,
            $dni,
            $email,
            $password_hash
        ]);

        header("Location: login.php?registro=exitoso");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrarse | Fiter</title>

    <link rel="stylesheet" href="styles.css">

</head>

<body>

<header class="header">

    <div class="container header-content">

        <div class="logo">
            <img src="img/logo-fiter.png" alt="Logo de Fiter">
        </div>

        <nav class="nav">
            <a href="index.php">Inicio</a>
            <a href="login.php">Iniciar sesión</a>
        </nav>

    </div>

</header>

<main class="main">

<section class="container">

<div class="card">

<h1>Crear una cuenta</h1>

<p>
Completá tus datos para registrarte en Fiter.
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

<label for="password">Contraseña</label>

<input
type="password"
id="password"
name="password"
required
>

</div>

<button type="submit" class="btn">
Crear cuenta
</button>

</form>

<br>

<p>
¿Ya tenés una cuenta?
<a href="login.php">Iniciá sesión</a>
</p>

</div>

</section>

</main>

<footer class="footer">
<p>Fiter &copy; 2026 - Sistema de gestión de usuarios</p>
</footer>

</body>

</html>