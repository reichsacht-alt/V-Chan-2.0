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

            $allowedImageExt = ['jpg', 'jpeg', 'png', 'webp']; // No incluimos gif
            $allowedVideoExt = ['mp4'];
            $allowedAudioExt = ['mp3', 'wav']; // Agregar audio aquí si es necesario

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            // Bloquear archivos GIF y de audio (MP3, WAV)
            if ($ext === 'gif' || in_array($ext, $allowedAudioExt)) {
                $confirm = 0; // No permitir GIFs ni audios
            } elseif (!in_array($ext, array_merge($allowedImageExt, $allowedVideoExt))) {
                $confirm = 0; // Bloquear cualquier otro tipo de archivo no permitido
            } else {
                $sqlInsert = "INSERT INTO posts (uid,title, comment, up_date) VALUES (?, ?, ?, NOW())";
                $stmt = mysqli_prepare($link, $sqlInsert);
                mysqli_stmt_bind_param($stmt, "iss", $uid, $title, $comment);
                mysqli_stmt_execute($stmt);
                $postId = mysqli_insert_id($link);

                $hash = md5($postId);
                $originalName = $hash . '.' . $ext;
                $originalPath = $uploadDirOriginal . $originalName;
                $previewName = "preview-" . $hash . ".png";
                $previewPath = $uploadDirPreview . $previewName;

                if (!move_uploaded_file($file['tmp_name'], $originalPath)) {
                    mysqli_query($link, "DELETE FROM posts WHERE id=$postId");
                    $confirm = 0;
                } else {
                    $hasThumbnail = isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE;

                    if ($hasThumbnail) {
                        $thumbFile = $_FILES['thumbnail'];
                        $thumbExt = strtolower(pathinfo($thumbFile['name'], PATHINFO_EXTENSION));
                        $validThumbExt = ['jpg', 'jpeg', 'png', 'webp', 'jfif'];

                        if (in_array($thumbExt, $validThumbExt)) {
                            list($thumbWidth, $thumbHeight) = getimagesize($thumbFile['tmp_name']);
                            if ($thumbWidth < 150 || $thumbHeight < 200) {
                                $previewName = "image.png"; // fallback por tamaño insuficiente
                            } else {
                                $image = match ($thumbExt) {
                                    'jpg', 'jpeg', 'jfif' => imagecreatefromjpeg($thumbFile['tmp_name']),
                                    'png'                 => imagecreatefrompng($thumbFile['tmp_name']),
                                    'webp'                => imagecreatefromwebp($thumbFile['tmp_name']),
                                    default               => false
                                };

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
                                    imagecopy($thumbnail, $resized, 0, 0, $cropX, $cropY, $targetWidth, $targetHeight);
                                    imagepng($thumbnail, $previewPath);
                                    imagedestroy($image);
                                    imagedestroy($resized);
                                    imagedestroy($thumbnail);
                                } else {
                                    $previewName = "image.png"; // fallback por error al procesar imagen
                                }
                            }
                        }
                    } else {
                        if (in_array($ext, $allowedImageExt)) {
                            $image = match ($ext) {
                                'jpg', 'jpeg' => imagecreatefromjpeg($originalPath),
                                'png'         => imagecreatefrompng($originalPath),
                                'webp'        => imagecreatefromwebp($originalPath),
                                default       => false
                            };

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
                                imagecopy($thumbnail, $resized, 0, 0, $cropX, $cropY, $targetWidth, $targetHeight);
                                imagepng($thumbnail, $previewPath);
                                imagedestroy($image);
                                imagedestroy($resized);
                                imagedestroy($thumbnail);
                            } else {
                                $previewName = "image.png";
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
                                    imagecopy($thumbnail, $resized, 0, 0, $cropX, $cropY, $targetWidth, $targetHeight);
                                    imagepng($thumbnail, $previewPath);
                                    imagedestroy($image);
                                    imagedestroy($resized);
                                    imagedestroy($thumbnail);
                                    unlink($tempFramePath);
                                } else {
                                    $previewName = "video.png";
                                }
                            } else {
                                $previewName = "video.png";
                            }
                        } elseif (in_array($ext, $allowedAudioExt)) {
                            $previewName = "audio.png"; // Se asigna una imagen por defecto
                        }
                    }

                    $sqlUpdate = "UPDATE posts SET original=?, preview=? WHERE id=?";
                    $stmtUp = mysqli_prepare($link, $sqlUpdate);
                    mysqli_stmt_bind_param($stmtUp, "ssi", $originalName, $previewName, $postId);
                    mysqli_stmt_execute($stmtUp);

                    $confirm = 1;
                    header("Location: posts.php?pag=1&postperpage=10&tag=1");
                    exit;
                }
            }
        } else {
            $sqlInsert = "INSERT INTO posts (uid, title, comment, up_date) VALUES (?, ?, ?, NOW())";
            $stmt = mysqli_prepare($link, $sqlInsert);
            mysqli_stmt_bind_param($stmt, "iss", $uid, $title, $comment);
            if (mysqli_stmt_execute($stmt)) {
                $confirm = 1;
                header("Location: posts.php?pag=1&postperpage=10&tag=1");
                exit;
            } else {
                $confirm = 0;
            }
        }
    }
}

$section = "upload";
$title = "Upload";
require_once "views/layout.php";
