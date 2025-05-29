<?php
session_start();
require_once "includes/config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ajusta la ruta si es necesario


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user']['id'];

if (isset($_POST['verify'])) {
    $codeInput = trim($_POST['code'] ?? '');

    if (empty($codeInput)) {
        $errorMessage = "⚠️ Ingresá el código de verificación.";
    } else {
        // Obtenemos el código de la base de datos
        $sql = "SELECT code, confirmed FROM users WHERE id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if (!$user) {
            $errorMessage = "❌ Usuario no encontrado.";
        } elseif ($user['confirmed'] == 1) {
            $successMessage = "✅ Tu cuenta ya fue verificada.";
        } elseif ($codeInput === $user['code']) {
            // Confirmamos el usuario
            $updateSql = "UPDATE users SET confirmed = 1 WHERE id = ?";
            $updateStmt = mysqli_prepare($link, $updateSql);
            mysqli_stmt_bind_param($updateStmt, "i", $userId);
            mysqli_stmt_execute($updateStmt);

            $_SESSION['user']['confirmed'] = 1;
            $successMessage = "✅ ¡Cuenta verificada con éxito!";
            header("Location: index.php");
        } else {
            $errorMessage = "❌ Código incorrecto. Verifica tu correo.";
        }
    }
}

if (isset($_POST['resend'])) {
    if (isset($_SESSION['user'])) {
        $userId = $_SESSION['user']['id'];
        $uEmail = $_SESSION['user']['email'];
        $uName = $_SESSION['user']['username'];

        // Generar nuevo código
        function generarCodigoVerificacion($longitud = 6) {
            $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $codigo = '';
            for ($i = 0; $i < $longitud; $i++) {
                $codigo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
            }
            return $codigo;
        }

        $newCode = generarCodigoVerificacion();

        // Actualizar código en la base de datos
        $updateSql = "UPDATE users SET code = ? WHERE id = ?";
        $updateStmt = mysqli_prepare($link, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "si", $newCode, $userId);
        mysqli_stmt_execute($updateStmt);

        // Enviar nuevo código con PHPMailer
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tuemail@gmail.com';        // Tu correo
            $mail->Password   = 'tu-app-password';          // Contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('tuemail@gmail.com', 'Registro Web');
            $mail->addAddress($uEmail, $uName);

            $mail->isHTML(true);
            $mail->Subject = 'Nuevo código de verificación';
            $mail->Body    = "<h3>Hola $uName</h3><p>Tu nuevo código de verificación es: <strong>$newCode</strong></p>";
            $mail->AltBody = "Hola $uName\nTu nuevo código de verificación es: $newCode";

            $mail->send();
            $successMessage = "✅ Se ha enviado un nuevo código de verificación a $uEmail.";
        } catch (Exception $e) {
            $errorMessage = "⚠️ No se pudo enviar el correo de verificación: {$mail->ErrorInfo}";
        }
    } else {
        $errorMessage = "⚠️ No hay una sesión activa. Vuelve a iniciar sesión.";
    }
}
$section = "userConfirmation";
$title = "Verification";
require_once "views/layout.php";

