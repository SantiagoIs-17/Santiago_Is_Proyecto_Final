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

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: usuarios_archivados.php");
    exit;
}

$id = $_POST["id"] ?? "";

if ($id === "" || !ctype_digit($id)) {
    header("Location: usuarios_archivados.php");
    exit;
}

$consulta = $conexion->prepare(
    "UPDATE usuarios
     SET archivado_en = NULL
     WHERE id = ?"
);

$consulta->execute([$id]);

header("Location: usuarios_archivados.php");

exit;

?>