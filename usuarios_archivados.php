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

$consulta = $conexion->prepare(
    "SELECT id, nombre, apellido, dni, email, rol, creado_en, archivado_en
     FROM usuarios
     WHERE archivado_en IS NOT NULL
     ORDER BY archivado_en DESC"
);

$consulta->execute();

$usuarios = $consulta->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Usuarios archivados | Fiter</title>

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

<h1>Usuarios archivados</h1>

<p>
Acá aparecen los usuarios que fueron archivados.
</p>

<br>

<?php if (empty($usuarios)): ?>

<div class="alert success">
No hay usuarios archivados.
</div>

<?php else: ?>

<div class="table-container">

<table>

<thead>

<tr>

<th>ID</th>
<th>Nombre</th>
<th>Apellido</th>
<th>DNI</th>
<th>Email</th>
<th>Rol</th>
<th>Acción</th>

</tr>

</thead>

<tbody>

<?php foreach ($usuarios as $usuario): ?>

<tr>

<td>
<?= htmlspecialchars($usuario["id"]) ?>
</td>

<td>
<?= htmlspecialchars($usuario["nombre"]) ?>
</td>

<td>
<?= htmlspecialchars($usuario["apellido"]) ?>
</td>

<td>
<?= htmlspecialchars($usuario["dni"]) ?>
</td>

<td>
<?= htmlspecialchars($usuario["email"]) ?>
</td>

<td>
<?= htmlspecialchars($usuario["rol"]) ?>
</td>

<td>

<form
method="POST"
action="reactivar_usuario.php"
>

<input
type="hidden"
name="id"
value="<?= $usuario["id"] ?>"
>

<button type="submit" class="btn">
Reactivar
</button>

</form>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

<br>

<a href="admin_usuarios.php" class="btn">
Volver
</a>

</div>

</section>

</main>

<footer class="footer">
<p>Fiter &copy; 2026 - Sistema de gestión de usuarios</p>
</footer>

</body>

</html>