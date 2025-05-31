<?php
require_once "includes/config.php";
session_start();

$empty;
// Paginación
$page = isset($_GET['pag']) && is_numeric($_GET['pag']) ? intval($_GET['pag']) : 1;
if (isset($_POST['postPerPage']) && $_POST['postPerPage'] != '') {
    // Asignar el valor de postPerPage a una variable para validarlo correctamente
    $postPerPage = $_POST['postPerPage'];

    // Validar si el valor es un entero válido y está en el rango adecuado
    if (filter_var($postPerPage, FILTER_VALIDATE_INT) !== false && $postPerPage > 0 && $postPerPage < 51) {
        // Si es válido, redirigir con el parámetro correcto
        header("Location: posts.php?pag=1&postperpage=" . $postPerPage . "&tag=1");
        exit; // Importante para evitar continuar ejecutando el script después de la redirección
    } else {
        // Si no es válido, redirigir con el valor por defecto y el error
        header("Location: posts.php?pag=1&postperpage=10&tag=1&error=lolLmao");
        exit; // Importante aquí también
    }
}
$limit = $_GET['postperpage'];
$offset = ($page - 1) * $limit;

// Obtener posts paginados que no estén "eliminados" (dl_date IS NULL)
$posts = [];
$sql = "SELECT * FROM posts WHERE dl_date IS NULL ORDER BY up_date DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($link, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $empty=0;
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
}else{
$empty=1;
}

// Obtener cantidad total de posts activos para paginación
$totalResult = mysqli_query($link, "SELECT COUNT(*) AS total FROM posts WHERE dl_date IS NULL");
$totalRow = mysqli_fetch_assoc($totalResult);
$totalPosts = intval($totalRow['total']);
$totalPages = ceil($totalPosts / $limit);

// Variables para layout
$section = "posts";
$title = "Posts";
require_once "views/layout.php";
