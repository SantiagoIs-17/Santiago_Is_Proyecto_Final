<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Mi panel | Fiter</title>

<link rel="stylesheet" href="styles.css">

</head>

<body>

<header class="header">

<div class="container header-content">

<div class="logo">
<img src="img/logo-fiter.png" alt="Logo de Fiter">
</div>

<nav class="nav">
<a href="cerrar_sesion.php">Cerrar sesión</a>
</nav>

</div>

</header>

<main class="main">

<section class="container">

<div class="card">

<h1>
Bienvenido, <?= htmlspecialchars($_SESSION["nombre"]) ?>
</h1>

<p>
Este es tu panel personal de Fiter.
</p>

<br>

<h2>Mis datos</h2>

<br>

<p>
<strong>Nombre:</strong>
<?= htmlspecialchars($_SESSION["nombre"]) ?>
</p>

<p>
<strong>Apellido:</strong>
<?= htmlspecialchars($_SESSION["apellido"]) ?>
</p>

<p>
<strong>Email:</strong>
<?= htmlspecialchars($_SESSION["email"]) ?>
</p>

<br>

<a href="cerrar_sesion.php" class="btn">
Cerrar sesión
</a>

</div>

</section>

</main>

<footer class="footer">
<p>Fiter &copy; 2026 - Sistema de gestión de usuarios</p>
</footer>

</body>

</html>