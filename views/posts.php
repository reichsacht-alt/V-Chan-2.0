<div style="text-align: center;">
    <div id="updateToast" class="toast"></div>
    <div style="margin: 50px 50px 50px 50px; border: 1px solid black;">
        <div style="margin: 10px 10px 10px 10px; text-align:right">
            <a href="upload.php" class="ace"><img class="alt" alternate="img/ui/add-button-alt.svg" src="img/ui/add-button.svg" alt="" style="width: 35px; margin: 10px 10px 10px 10px"></a><br><br>
            <form method="post">
                Posts per page
                <input type="numbmer" name="postPerPage" min="5" max="50" value="<?php echo $limit; ?>">
                <input type="submit" name="pag" value="Confirm">
            </form>
            <?php if(isset($_GET['error'])){echo "Please input a valid number less or equal to 50";}?>
        </div>

        <div style="margin: 10px 20px 30px 20px; border:1px solid black; display:flex; justify-content:center">
            <?php if ($empty == 0) {
                foreach ($posts as $post) {
                    $videoExts = ['mp4', 'webm', 'ogg', 'mov', 'mkv'];
                    $audioExts = ['mp3', 'wav', 'ogg', 'flac', 'aac'];
                    $imageExts = ['png', 'jpg', 'jpeg', 'tiff', 'jfif'];
                    $animaExts = ['gif', 'apng', 'webp', 'avif'];
                    $extension = strtolower(pathinfo($post['original'], PATHINFO_EXTENSION));
                    $isGif = in_array($extension, $animaExts);
                    $isVid = in_array($extension, $videoExts);
                    $isAud = in_array($extension, $audioExts);
                    $isImg = in_array($extension, $imageExts);
            ?>
                    <div style="margin: 5px 5px 5px 5px;">
                        <?php if ($post['original'] != NULL || $post['preview'] != NULL) { ?>
                            <div style="position: relative; width:150px; height:200px; overflow: hidden;">
                                <a href="post.php?post=<?php echo $post['id'] ?>">
                                    <img
                                        src="<?php echo $post['preview_path'] . $post['preview'] ?>"
                                        alt=""
                                        class="<?php echo $isGif ? 'gif-toggle ' : '';
                                                if ($post['nsfw'] == 1) {
                                                    echo "nsfw-image";
                                                } ?><?php if($isAud){echo "aud-toggle";}?>"
                                        data-gif="<?php echo $isGif ? $post['original_path'] . $post['original'] : ''; ?>"
                                        style="width:150px; height:200px; object-fit:cover">
                                    <?php if ($isVid): ?>
                                        <img src="img/ui/video.svg" alt="Video" style="position: absolute; top: 5px; left: 5px; width: 32px; height: 32px; pointer-events: none; aspect-ratio:1/1;">
                                    <?php elseif ($isAud): ?>
                                        <img src="img/ui/audio.svg" alt="Audio" style="position: absolute; top: 5px; left: 5px; width: 32px; height: 32px; pointer-events: none; aspect-ratio:1/1;">
                                    <?php endif; ?>
                                </a>
                            </div>

                        <?php } else if ($post['title'] != NULL) { ?>
                            <div style="border: solid black 1px; width:150px; height:200px;">
                                <a href="post.php?post=<?php echo $post['id'] ?>"><?php echo $post['title'] ?></a>
                            </div>
                        <?php } else if ($post['title'] == NULL && $post['comment'] != NULL) { ?>
                            <div style="border: solid black 1px; width:150px; height:200px;">
                                <p><?php echo $post['comment'] ?></p>
                                <a href="post.php?post=<?php echo $post['id'] ?>">Ver posteo</a>
                            </div>

                        <?php } else {
                            echo "You shouldn't be seeing this. Get out of here, someone might see you!";
                        } ?>
                    </div>
            <?php }
            } else {
                echo "Nothing to see here";
            }  ?>
        </div>
        <!-- Navegación -->
        <div>
            <?php if ($totalPages > 1) { ?>
                <div>
                    <p>Páginas:</p>
                    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                        <a href="?pag=<?= $i ?>&postperpage=<?php echo $limit ?>&tag=1" <?= $i === $page ? 'style="font-weight:bold;"' : '' ?>><?= $i ?></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>