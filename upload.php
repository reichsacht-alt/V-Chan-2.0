<?php
session_start();
require_once "includes/config.php";

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION['user'])) {
    header("Location: posts.php");
    exit;
}

$uid = intval($_SESSION['user']['id']);
$confirm = null;

if (isset($_POST['submit'])) {
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $hasFile = isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE;

    if (!$hasFile && $comment === '') {
        $confirm = 0;
    } else {
        if ($hasFile) {
            $file = $_FILES['image'];
            $uploadDirOriginal = "img/posts/original/";
            $uploadDirPreview  = "img/posts/preview/";

            $allowedImageExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $allowedVideoExt = ['mp4'];
            $allowedAudioExt = ['mp3', 'wav']; // Audio permitidos pero sin miniatura generada aquí

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            // Bloquear si la extensión NO está en los permitidos (imagen, video o audio)
            $allowedExt = array_merge($allowedImageExt, $allowedVideoExt, $allowedAudioExt);
            if (!in_array($ext, $allowedExt)) {
                $confirm = 0;
            } else {
                // Insertar post sin paths aún
                $sqlInsert = "INSERT INTO posts (uid,title, comment, up_date) VALUES (?, ?, ?, NOW())";
                $stmt = mysqli_prepare($link, $sqlInsert);
                mysqli_stmt_bind_param($stmt, "iss", $uid, $title, $comment);
                mysqli_stmt_execute($stmt);
                $postId = mysqli_insert_id($link);

                $hash = md5($postId);
                $originalName = $hash . '.' . $ext;
                $originalPath = $uploadDirOriginal . $originalName;
                $previewName = "preview-" . $hash . ".png"; // siempre png
                $previewPath = $uploadDirPreview . $previewName;

                if (!move_uploaded_file($file['tmp_name'], $originalPath)) {
                    mysqli_query($link, "DELETE FROM posts WHERE id=$postId");
                    $confirm = 0;
                } else {
                    $hasThumbnail = isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE;

                    if ($hasThumbnail) {
                        $thumbFile = $_FILES['thumbnail'];
                        $thumbExt = strtolower(pathinfo($thumbFile['name'], PATHINFO_EXTENSION));
                        $validThumbExt = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'jfif'];

                        if (in_array($thumbExt, $validThumbExt)) {
                            list($thumbWidth, $thumbHeight) = getimagesize($thumbFile['tmp_name']);
                            if ($thumbWidth < 150 || $thumbHeight < 200) {
                                $previewName = "image.png"; // fallback por tamaño insuficiente
                            } else {
                                // Crear imagen origen thumbnail según extensión
                                switch ($thumbExt) {
                                    case 'jpg':
                                    case 'jpeg':
                                    case 'jfif':
                                        $image = @imagecreatefromjpeg($thumbFile['tmp_name']);
                                        break;
                                    case 'png':
                                        $image = @imagecreatefrompng($thumbFile['tmp_name']);
                                        break;
                                    case 'webp':
                                        $image = @imagecreatefromwebp($thumbFile['tmp_name']);
                                        break;
                                    case 'gif':
                                        $image = @imagecreatefromgif($thumbFile['tmp_name']);
                                        break;
                                    default:
                                        $image = false;
                                }

                                if ($image) {
                                    $targetWidth = 150;
                                    $targetHeight = 200;
                                    $origWidth = imagesx($image);
                                    $origHeight = imagesy($image);
                                    $scale = max($targetWidth / $origWidth, $targetHeight / $origHeight);
                                    $resizedWidth = intval($origWidth * $scale);
                                    $resizedHeight = intval($origHeight * $scale);
                                    $resized = imagescale($image, $resizedWidth, $resizedHeight);
                                    $cropX = intval(($resizedWidth - $targetWidth) / 2);
                                    $cropY = intval(($resizedHeight - $targetHeight) / 2);
                                    $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
                                    // Para preservar transparencia en PNG y GIF
                                    imagealphablending($thumbnail, false);
                                    imagesavealpha($thumbnail, true);
                                    $transparent = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
                                    imagefill($thumbnail, 0, 0, $transparent);

                                    imagecopy($thumbnail, $resized, 0, 0, $cropX, $cropY, $targetWidth, $targetHeight);
                                    imagepng($thumbnail, $previewPath);
                                    imagedestroy($image);
                                    imagedestroy($resized);
                                    imagedestroy($thumbnail);
                                } else {
                                    error_log("Error al crear imagen desde thumbnail upload");
                                    $previewName = "image.png"; // fallback
                                }
                            }
                        } else {
                            $previewName = "image.png"; // extensión thumbnail inválida
                        }
                    } else {
                        // No thumbnail subido => generamos según el tipo del archivo principal
                        if (in_array($ext, $allowedImageExt)) {
                            // Crear imagen origen principal según extensión
                            switch ($ext) {
                                case 'jpg':
                                case 'jpeg':
                                case 'jfif':
                                    $image = @imagecreatefromjpeg($originalPath);
                                    break;
                                case 'png':
                                    $image = @imagecreatefrompng($originalPath);
                                    break;
                                case 'webp':
                                    $image = @imagecreatefromwebp($originalPath);
                                    break;
                                case 'gif':
                                    $image = @imagecreatefromgif($originalPath);
                                    break;
                                default:
                                    $image = false;
                            }

                            if ($image) {
                                $targetWidth = 150;
                                $targetHeight = 200;
                                $origWidth = imagesx($image);
                                $origHeight = imagesy($image);
                                $scale = max($targetWidth / $origWidth, $targetHeight / $origHeight);
                                $resizedWidth = intval($origWidth * $scale);
                                $resizedHeight = intval($origHeight * $scale);
                                $resized = imagescale($image, $resizedWidth, $resizedHeight);
                                $cropX = intval(($resizedWidth - $targetWidth) / 2);
                                $cropY = intval(($resizedHeight - $targetHeight) / 2);
                                $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
                                imagealphablending($thumbnail, false);
                                imagesavealpha($thumbnail, true);
                                $transparent = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
                                imagefill($thumbnail, 0, 0, $transparent);

                                imagecopy($thumbnail, $resized, 0, 0, $cropX, $cropY, $targetWidth, $targetHeight);
                                imagepng($thumbnail, $previewPath);
                                imagedestroy($image);
                                imagedestroy($resized);
                                imagedestroy($thumbnail);
                            } else {
                                error_log("Error al crear imagen desde archivo original");
                                $previewName = "image.png"; // fallback
                            }
                        } elseif (in_array($ext, $allowedVideoExt)) {
                            $tempFramePath = $uploadDirPreview . "temp-" . $hash . ".png";
                            $ffmpegPath = "C:\\ffmpeg\\bin\\ffmpeg.exe";
                            $cmd = escapeshellarg($ffmpegPath) . " -y -i " . escapeshellarg($originalPath) . " -ss 00:00:02 -vframes 1 -q:v 2 " . escapeshellarg($tempFramePath) . " 2>&1";
                            exec($cmd, $output, $return_var);

                            if ($return_var === 0 && file_exists($tempFramePath)) {
                                $image = @imagecreatefrompng($tempFramePath);
                                if ($image) {
                                    $targetWidth = 150;
                                    $targetHeight = 200;
                                    $origWidth = imagesx($image);
                                    $origHeight = imagesy($image);
                                    $scale = max($targetWidth / $origWidth, $targetHeight / $origHeight);
                                    $resizedWidth = intval($origWidth * $scale);
                                    $resizedHeight = intval($origHeight * $scale);
                                    $resized = imagescale($image, $resizedWidth, $resizedHeight);
                                    $cropX = intval(($resizedWidth - $targetWidth) / 2);
                                    $cropY = intval(($resizedHeight - $targetHeight) / 2);
                                    $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
                                    imagealphablending($thumbnail, false);
                                    imagesavealpha($thumbnail, true);
                                    $transparent = imagecolorallocatealpha($thumbnail, 0, 0, 0, 127);
                                    imagefill($thumbnail, 0, 0, $transparent);

                                    imagecopy($thumbnail, $resized, 0, 0, $cropX, $cropY, $targetWidth, $targetHeight);
                                    imagepng($thumbnail, $previewPath);
                                    imagedestroy($image);
                                    imagedestroy($resized);
                                    imagedestroy($thumbnail);
                                } else {
                                    error_log("No se pudo crear imagen desde frame de video");
                                    $previewName = "image.png";
                                }
                                unlink($tempFramePath);
                            } else {
                                error_log("Error extrayendo frame con ffmpeg: " . implode("\n", $output));
                                $previewName = "image.png";
                            }
                        } else {
                            // Audio o extensión no soportada para miniatura
                            $previewName = "image.png";
                        }
                    }

                    // Actualizar base de datos con rutas
                    $sqlUpdate = "UPDATE posts SET original = ?, preview = ? WHERE id = ?";
                    $stmtUpdate = mysqli_prepare($link, $sqlUpdate);
                    mysqli_stmt_bind_param($stmtUpdate, "ssi", $originalName, $previewName, $postId);
                    mysqli_stmt_execute($stmtUpdate);

                    if (mysqli_affected_rows($link) < 1) {
                        $confirm = 0;
                    } else{
                        header("Location: posts.php?pag=1&postperpage=10&tag=1");
                        exit();
                    }
                }
            }
        }
    }
}

$section = "upload";
$title = "Upload";
require_once "views/layout.php";
