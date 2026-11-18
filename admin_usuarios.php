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

$busqueda = trim($_GET["busqueda"] ?? "");

if ($busqueda !== "") {

    $consulta = $conexion->prepare(
        "SELECT id, nombre, apellido, dni, email, rol, creado_en
         FROM usuarios
         WHERE archivado_en IS NULL
         AND (
             nombre LIKE ?
             OR apellido LIKE ?
             OR dni LIKE ?
             OR email LIKE ?
         )
         ORDER BY id DESC"
    );

    $texto = "%" . $busqueda . "%";

    $consulta->execute([
        $texto,
        $texto,
        $texto,
        $texto
    ]);

} else {

    $consulta = $conexion->prepare(
        "SELECT id, nombre, apellido, dni, email, rol, creado_en
         FROM usuarios
         WHERE archivado_en IS NULL
         ORDER BY id DESC"
    );

    $consulta->execute();
}

$usuarios = $consulta->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Administrar usuarios | Fiter</title>

<link rel="stylesheet" href="styles.css">

</head>

<body>

<header class="header">

<div class="container header-content">

<div class="logo">
<img src="img/logo-fiter.png" alt="Logo de Fiter">
</div>

<nav class="nav">
<a href="panel.php">Mi panel</a>
<a href="cerrar_sesion.php">Cerrar sesión</a>
</nav>

</div>

</header>

<main class="main">

<section class="container">

<div class="card">

<h1>Administración de usuarios</h1>

<p>
Desde este panel podés consultar y administrar
los usuarios registrados en Fiter.
</p>

<br>

<form method="GET">

<div class="form-group">

<label for="busqueda">
Buscar usuario
</label>

<input
type="text"
id="busqueda"
name="busqueda"
placeholder="Nombre, apellido, DNI o email"
value="<?= htmlspecialchars($busqueda) ?>"
>

</div>

<button type="submit" class="btn">
Buscar
</button>

<a href="admin_usuarios.php" class="btn">
Ver todos
</a>

</form>

<br>

<?php if (empty($usuarios)): ?>

<div class="alert error">
No se encontraron usuarios.
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
<th>Acciones</th>

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

<a
href="editar_usuario.php?id=<?= $usuario["id"] ?>"
class="btn"
>
Editar
</a>

<form
method="POST"
action="archivar_usuario.php"
style="display:inline;"
>

<input
type="hidden"
name="id"
value="<?= $usuario["id"] ?>"
>

<button type="submit" class="btn">
Archivar
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

<a href="usuarios_archivados.php" class="btn">
Ver usuarios archivados
</a>

</div>

</section>

</main>

<footer class="footer">
<p>Fiter &copy; 2026 - Sistema de gestión de usuarios</p>
</footer>

</body>

</html>