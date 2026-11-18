<?php

session_start();

require_once "conexion.php";

$errores = "";
$mensaje = "";

if (isset($_GET["registro"]) && $_GET["registro"] === "exitoso") {
    $mensaje = "Cuenta creada correctamente. Ahora podés iniciar sesión.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $errores = "Completá todos los campos.";

    } else {

        $consulta = $conexion->prepare(
            "SELECT id, nombre, apellido, email, password_hash, rol
             FROM usuarios
             WHERE email = ? AND archivado_en IS NULL"
        );

        $consulta->execute([$email]);

        $usuario = $consulta->fetch();

        if (
            $usuario &&
            password_verify(
                $password,
                $usuario["password_hash"]
            )
        ) {

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["apellido"] = $usuario["apellido"];
            $_SESSION["email"] = $usuario["email"];
            $_SESSION["rol"] = $usuario["rol"];

            if ($usuario["rol"] === "admin") {
                header("Location: admin_usuarios.php");
            } else {
                header("Location: panel.php");
            }

            exit;

        } else {

            $errores = "Email o contraseña incorrectos.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Iniciar sesión | Fiter</title>

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
<a href="registro.php">Registrarse</a>
</nav>

</div>

</header>

<main class="main">

<section class="container">

<div class="card">

<h1>Iniciar sesión</h1>

<p>
Ingresá con tu email y contraseña.
</p>

<br>

<?php if ($mensaje !== ""): ?>

<div class="alert success">
<?= htmlspecialchars($mensaje) ?>
</div>

<?php endif; ?>

<?php if ($errores !== ""): ?>

<div class="alert error">
<?= htmlspecialchars($errores) ?>
</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">

<label for="email">
Email
</label>

<input
type="email"
id="email"
name="email"
required
>

</div>

<div class="form-group">

<label for="password">
Contraseña
</label>

<input
type="password"
id="password"
name="password"
required
>

</div>

<button type="submit" class="btn">
Iniciar sesión
</button>

</form>

<br>

<p>
¿No tenés una cuenta?
<a href="registro.php">Registrate</a>
</p>

</div>

</section>

</main>

<footer class="footer">
<p>Fiter &copy; 2026 - Sistema de gestión de usuarios</p>
</footer>

</body>

</html>