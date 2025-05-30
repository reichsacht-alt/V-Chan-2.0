<?php
require_once "includes/config.php";
session_start();

// Paginación
$page = isset($_GET['pag']) && is_numeric($_GET['pag']) ? intval($_GET['pag']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Obtener posts paginados que no estén "eliminados" (dl_date IS NULL)
$posts = [];
$sql = "SELECT * FROM posts WHERE dl_date IS NULL ORDER BY up_date DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($link, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
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
?>
