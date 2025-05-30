<?php if ($isVid) { ?>
    <video controls width="400">
        <source src="<?= $postData['original_path'] . $postData['original'] ?>" type="video/<?= $extension ?>">
        Tu navegador no soporta este video.
    </video>

<?php } elseif ($isAud) { ?>
    <audio controls>
        <source src="<?= $postData['original_path'] . $postData['original'] ?>" type="audio/<?= $extension ?>">
        Tu navegador no soporta este audio.
    </audio>

<?php } elseif ($isImg) { ?>
    <img
        src="<?= $postData['original_path'] . $postData['original'] ?>"
        alt="Imagen"
        style="max-width: 100%; height: auto;">
<?php } else if ($postData['title'] != NULL) { ?>
    <h1><?php echo $postData['title'] ?></h1>
    <?php if ($postData['comment'] != NULL) { ?>
        <p><?php echo $postData['comment'] ?></p>
    <?php } ?>
<?php } else if ($postData['comment'] != NULL) { ?>
    <p><?php echo $postData['comment'] ?></p>
<?php } else {
    echo "Error";
} ?>