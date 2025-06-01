<div style="display: flex; margin-top:40px; margin-left: auto; margin-right: auto; border: solid black 5px; width:70%">
    <a href="#" class="pfpCont" style="width: min-content; height: min-content;"><img class="pfp" src="<?php echo $_SESSION['user']['picture']['directory'] . $_SESSION['user']['picture']['image'] ?>" alt=""></a>
    <div style="margin: 50px 30px 30px 30px">
        <a><?php echo $_SESSION['user']['uid'] ?></a>
        <h1 class="ace"><?php echo $_SESSION['user']['username'] ?>
            <b style="font-size: 40px;"><?php
                                        if ($_SESSION['user']['accessLevel']['level'] == "owner") {
                                            echo " 🜲";/*⛟⚘⛾⛿*/
                                        } else if ($_SESSION['user']['accessLevel']['level'] == "user") {
                                            echo " ✣";
                                        } else if ($_SESSION['user']['accessLevel']['level'] == "mod") {
                                            echo " ✤";
                                        } else if ($_SESSION['user']['accessLevel']['level'] == "admin") {
                                            echo " ✥";
                                        }
                                        ?>
            </b>
        </h1>
        <h3 class="ace" style="color: #676767;"><?php echo "[ " . $_SESSION['user']['accessLevel']['level'] . " ]" ?></h3>
    </div>
</div>
<!-- <div style="display: flex; margin-top:40px; margin-left: auto; margin-right: auto; border: solid black 5px; width:70%">
    <?php if ($empty == 0) {
        foreach ($myPosts as $post) {
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
                                        } ?><?php if ($isAud) {
                                                        echo "aud-toggle";
                                                    } ?>"
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
</div> -->