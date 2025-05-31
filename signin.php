<?php
require_once "includes/config.php";
session_start();

if (isset($_POST['signIn'])) {
    if (!empty($_POST['uName']) && !empty($_POST['uPassword'])) {
        $uName = trim($_POST['uName']);
        $uPassword = $_POST['uPassword'];

        // Buscar usuario por nombre o email
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $uName, $uName);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            // Verificamos contraseña
            if (password_verify($uPassword, $user['password'])) {
                // Guardamos sesión
                $_SESSION['user'] = $user;

                $sql = "SELECT * FROM userPicture WHERE uid = ?";
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_bind_param($stmt, "i", $_SESSION['user']['id']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $usrImg = mysqli_fetch_assoc($result);

                $_SESSION['user']['picture'] = $usrImg;

                $sql = "SELECT al.level 
FROM accesslevel AS al 
INNER JOIN useraccesslevel AS ual 
    ON al.id = ual.lid 
WHERE ual.uid = ?;
";
                $stmt = mysqli_prepare($link, $sql);
                mysqli_stmt_bind_param($stmt, "i", $_SESSION['user']['id']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $usrLvl = mysqli_fetch_assoc($result);

                $_SESSION['user']['accessLevel'] = $usrLvl;

                header("Location: index.php");
                exit;
            } else {
                $errorMessage = "❌ Contraseña incorrecta.";
            }
        } else {
            $errorMessage = "❌ Usuario no encontrado.";
        }
    } else {
        $errorMessage = "⚠️ Completa todos los campos.";
    }
}

$section = "signin";
$title = "Signin";
require_once "views/layout.php";
