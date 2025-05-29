<?php
// Carga de PHPMailer al principio del archivo
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

require_once "includes/config.php";
session_start();

if (isset($_POST['signUp'])) {
    if (
        isset($_POST['uName'], $_POST['uEmail'], $_POST['uPassword']) &&
        !empty($_POST['uName']) &&
        !empty($_POST['uEmail']) &&
        !empty($_POST['uPassword'])
    ) {
        // Saneamos entradas
        $uName = trim($_POST['uName']);
        $uEmail = filter_var(trim($_POST['uEmail']), FILTER_SANITIZE_EMAIL);
        $uPassword = password_hash($_POST['uPassword'], PASSWORD_DEFAULT);
        $pfp = 'default1.png';

        // Validar formato de email
        if (!filter_var($uEmail, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Correo electrónico no válido.";
        } else {
            // Verificar si ya existe usuario o email
            $checkSql = "SELECT id FROM users WHERE username = ? OR email = ?";
            $checkStmt = mysqli_prepare($link, $checkSql);
            mysqli_stmt_bind_param($checkStmt, "ss", $uName, $uEmail);
            mysqli_stmt_execute($checkStmt);
            mysqli_stmt_store_result($checkStmt);

            if (mysqli_stmt_num_rows($checkStmt) > 0) {
                $errorMessage = "El nombre de usuario o correo ya están registrados.";
            } else {
                // Generar código aleatorio
                function generarCodigoVerificacion($longitud = 6) {
                    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    $codigo = '';
                    for ($i = 0; $i < $longitud; $i++) {
                        $codigo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
                    }
                    return $codigo;
                }

                $code = generarCodigoVerificacion();

                // Insertar usuario
                $sql = "INSERT INTO users (username, password, email, pfp, code, confirmed, verified, cr_date) VALUES (?, ?, ?, ?, ?, 0, 0, NOW())";
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_bind_param($stmt, "sssss", $uName, $uPassword, $uEmail, $pfp, $code);
                $success = mysqli_stmt_execute($stmt);

                if ($success) {
                    $userId = mysqli_insert_id($link);

                    // Crear UID
                    $uid = "UID" . str_pad($userId, 6, "0", STR_PAD_LEFT);
                    $updateSql = "UPDATE users SET uid = ? WHERE id = ?";
                    $updateStmt = mysqli_prepare($link, $updateSql);
                    mysqli_stmt_bind_param($updateStmt, "si", $uid, $userId);
                    mysqli_stmt_execute($updateStmt);

                    // Enviar correo con PHPMailer
                    try {
                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'tuemail@gmail.com';         // ✔ tu correo
                        $mail->Password   = 'tu-app-password';           // ✔ contraseña app
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;

                        $mail->setFrom('tuemail@gmail.com', 'Registro Web');
                        $mail->addAddress($uEmail, $uName);

                        $mail->isHTML(true);
                        $mail->Subject = 'Verificación de cuenta';
                        $mail->Body    = "<h3>Hola $uName</h3><p>Tu código de verificación es: <strong>$code</strong></p>";
                        $mail->AltBody = "Hola $uName\nTu código de verificación es: $code";

                        $mail->send();
                    } catch (Exception $e) {
                        $warningMessage = "⚠️ No se pudo enviar el correo de verificación: {$mail->ErrorInfo}";
                    }

                    // Guardar usuario en sesión

                    $selectSql = "SELECT * FROM users WHERE id = ?";
                    $selectStmt = mysqli_prepare($link, $selectSql);
                    mysqli_stmt_bind_param($selectStmt, "i", $userId);
                    mysqli_stmt_execute($selectStmt);
                    $result = mysqli_stmt_get_result($selectStmt);
                    $_SESSION['user'] = mysqli_fetch_assoc($result);
                    
                    $sql = "INSERT INTO userPicture (uid, image, directory) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($link, $sql);
                    $pic="default".rand(1,6).".png";
                    $path="img/users/";
                    mysqli_stmt_bind_param($stmt, "iss", $_SESSION['user']['id'], $pic, $path);
                    $success = mysqli_stmt_execute($stmt);

                    $successMessage = "✅ Registro exitoso. Tu UID es: $uid. Se ha enviado un correo a $uEmail con tu código de verificación.";
                    header("Location: index.php?verifReq=1");
                } else {
                    $errorMessage = "❌ Error al registrar: " . mysqli_error($link);
                }
            }
        }
    } else {
        $errorMessage = "⚠️ Completa todos los campos.";
    }
}

$section = "signup";
$title = "Signup";
require_once "views/layout.php";
