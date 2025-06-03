<?php
require_once "includes/config.php";
session_start();
$postId = intval($_GET['post']);
$query = "
    SELECT * 
    FROM posts p 
    INNER JOIN users u ON p.uid = u.id 
    WHERE p.id = $postId
";

$result = mysqli_query($link, $query);
$postData = mysqli_fetch_assoc($result);

$extension = strtolower(pathinfo($postData['original'], PATHINFO_EXTENSION));

// Definir arrays de extensiones válidas para cada tipo
$imageExtensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'];
$videoExtensions = ['mp4', 'webm', 'ogg', 'mov', 'mkv'];
$audioExtensions = ['mp3', 'wav', 'ogg', 'flac', 'aac'];

// Verificar si la extensión pertenece a cada grupo
$isImg = in_array($extension, $imageExtensions);
$isGif = $extension === 'gif'; // si quieres tratar GIF aparte
$isVid = in_array($extension, $videoExtensions);
$isAud = in_array($extension, $audioExtensions);

// $query = "
//     SELECT * 
//     FROM postcomment pc 
//     WHERE pc.pid = $postId
// ";

// $result = mysqli_query($link, $query);
// $postData = mysqli_fetch_assoc($result);

$section = "post";
$title = "Post";
require_once "views/layout.php";
