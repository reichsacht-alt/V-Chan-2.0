<h2>Verificar cuenta</h2>
<form method="post">
    <label for="code">Ingresa tu código de verificación:</label><br>
    <input type="text" name="code" placeholder="Ej: ABC123" required><br><br>
    <input type="submit" name="verify" value="Verificar">
</form>

<?php if (isset($errorMessage)) echo "<p style='color:red;'>$errorMessage</p>"; ?>
<?php if (isset($successMessage)) echo "<p style='color:green;'>$successMessage</p>"; ?>

<!-- Botón para reenviar el código -->
<form method="post">
    <input type="submit" name="resend" value="Reenviar código de verificación">
</form>
