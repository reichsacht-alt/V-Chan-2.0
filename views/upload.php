<form action="upload.php" method="POST" enctype="multipart/form-data">
    <label>
        <span>Title:</span>
        <input type="text" name="title"><br><br>
        <span>File:</span>
        <input type="file" name="image" accept="image/*,video/*,audio/*" title="Supported file types: mp3, mp4, gif, jpg, jpeg, png, jfif, webp. Any other type could not work properly."><br><br>

        <span>Comment (required if no file):</span><br>
        <textarea name="comment" rows="4" cols="50" placeholder="Write something..."></textarea><br><br>

        <p style="font-family:Verdana, Geneva, Tahoma, sans-serif; font-size:10px">
            Supported types:<br>
            &nbsp;&nbsp;-Images: any<br>
            &nbsp;&nbsp;-Video: mp4<br>
            &nbsp;&nbsp;-Audio: mp3
        </p>
    </label>
    <br>
    <input type="submit" name="submit" value="Upload file or comment">
</form>

<?php
if($confirm === 1){
    echo "<p>El archivo o comentario se ha subido correctamente</p>";
}else if($confirm === 0){
    echo "<p style='color:red;'>Error: Debe subir un archivo o ingresar un comentario válido.</p>";
}
?>
