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

// Procesar si se envió el formulario
if (isset($_POST['submit'])) {
    // Comentario enviado en el form (puede estar vacío)
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';

    // Verificar si se subió archivo (UPLOAD_ERR_NO_FILE = 4)
    $hasFile = isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE;

    // Si NO hay archivo y el comentario está vacío => error
    if (!$hasFile && $comment === '') {
        $confirm = 0;
    } else {
        if ($hasFile) {
            $file = $_FILES['image'];

            $uploadDirOriginal = "img/posts/original/";
            $uploadDirPreview  = "img/posts/preview/";

            $allowedImageExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $allowedVideoExt = ['mp4'];
            $allowedAudioExt = ['mp3'];

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, array_merge($allowedImageExt, $allowedVideoExt, $allowedAudioExt))) {
                $confirm = 0;
            } else {
                // Insertar registro con uid, comentario y fecha
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

                // Mover archivo original
                if (!move_uploaded_file($file['tmp_name'], $originalPath)) {
                    mysqli_query($link, "DELETE FROM posts WHERE id=$postId");
                    $confirm = 0;
                } else {
                    // Generar preview
                    if (in_array($ext, $allowedImageExt)) {
                        $image = match ($ext) {
                            'jpg', 'jpeg' => imagecreatefromjpeg($originalPath),
                            'png'         => imagecreatefrompng($originalPath),
                            'gif'         => imagecreatefromgif($originalPath),
                            'webp'        => imagecreatefromwebp($originalPath),
                            default       => false
                        };

                        if ($image) {
                            $targetWidth = 150;
                            // $thumbHeight = intval(imagesy($image) * $thumbWidth / imagesx($image));
                            $targetHeight = 200;
                            // Tamaño original
                            $origWidth = imagesx($image);
                            $origHeight = imagesy($image);

                            // Calcular escala para cubrir el área sin deformar
                            $scale = max($targetWidth / $origWidth, $targetHeight / $origHeight);
                            $resizedWidth = intval($origWidth * $scale);
                            $resizedHeight = intval($origHeight * $scale);

                            // Crear imagen redimensionada
                            $resized = imagescale($image, $resizedWidth, $resizedHeight);

                            // Calcular posición del recorte (centrado)
                            $cropX = intval(($resizedWidth - $targetWidth) / 2);
                            $cropY = intval(($resizedHeight - $targetHeight) / 2);

                            // Crear imagen final (150x250) y copiar parte centrada
                            $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
                            imagecopy($thumbnail, $resized, 0, 0, $cropX, $cropY, $targetWidth, $targetHeight);

                            // Guardar y liberar memoria
                            imagepng($thumbnail, $previewPath);
                            imagedestroy($image);
                            imagedestroy($resized);
                            imagedestroy($thumbnail);
                        } else {
                            $previewName = "image.png"; // fallback
                        }
                    } elseif (in_array($ext, $allowedVideoExt)) {
                        $previewName = "preview-" . $hash . ".png";
                        $previewPath = $uploadDirPreview . $previewName;
                        $tempFramePath = $uploadDirPreview . "temp-" . $hash . ".png";

                        // Extraer frame con ffmpeg
                        $ffmpegPath = "C:\\ffmpeg\\bin\\ffmpeg.exe"; // doble barra invertida en PHP para escapar la ruta
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
                                unlink($tempFramePath); // Limpia temporal
                            } else {
                                $previewName = "video.png"; // fallback si GD falla
                            }
                        } else {
                            $previewName = "video.png"; // fallback si ffmpeg falla
                        }
                    } elseif (in_array($ext, $allowedAudioExt)) {
                        $previewName = "audio.png";
                    }

                    // Guardar nombres en DB
                    $sqlUpdate = "UPDATE posts SET original=?, preview=? WHERE id=?";
                    $stmtUp = mysqli_prepare($link, $sqlUpdate);
                    mysqli_stmt_bind_param($stmtUp, "ssi", $originalName, $previewName, $postId);
                    mysqli_stmt_execute($stmtUp);

                    $confirm = 1;
                    header("Location: posts.php?pag=1&tag=1");
                    exit;
                }
            }
        } else {
            // No hay archivo, pero puede haber título y/o comentario
            $sqlInsert = "INSERT INTO posts (uid, title, comment, up_date) VALUES (?, ?, ?, NOW())";
            $stmt = mysqli_prepare($link, $sqlInsert);
            mysqli_stmt_bind_param($stmt, "iss", $uid, $title, $comment);
            if (mysqli_stmt_execute($stmt)) {
                $confirm = 1;
                header("Location: posts.php?pag=1&tag=1");
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
