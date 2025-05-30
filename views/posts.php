 <a href="upload.php">Upload Media</a><br><br>
 <div style="margin: 10px 20px 0px 20px; display:flex; width:100%"><!--margin: top left bottom right-->


     <?php foreach ($posts as $post) {
            $extension = pathinfo($post['original'], PATHINFO_EXTENSION);
            $isGif = strtolower($extension) == 'gif';
        ?>

         <div>
             <?php if ($post['original'] != NULL || $post['preview'] != NULL) { ?>
                 <div style="border: solid black 1px; width:150px; height:200px;">
                     <a href="post.php?post=<?php echo $post['id']?>"><img 
                     src="<?php echo $post['preview_path'] . $post['preview'] ?>" 
                     alt="" 
                     class="<?php if ($isGif) {echo 'gif-toggle';} ?>" 
                     data-gif="<?php if ($isGif) {echo $post['original_path'] . $post['original'];} ?>">
                 </div></a>
             <?php } else if ($post['title'] != NULL) { ?>
                 <div style="border: solid black 1px; width:150px; height:200px;">
                     <a href="post.php?post=<?php echo $post['id']?>"><?php echo $post['title'] ?></a>
                 </div>
             <?php } else if ($post['title'] == NULL && $post['comment'] != NULL) { ?>
                 <div style="border: solid black 1px; width:150px; height:200px;">
                     <p><?php echo $post['comment'] ?></p>
                     <a href="post.php?post=<?php echo $post['id']?>">Ver posteo</a>
                 </div>
         </div>
     <?php } else {
                    echo "You shouldn't be seeing this mssage º~º";
                } ?>
     <a href=""></a>
 </div>
 <?php } ?>

 <!-- Navegación -->
 <div>
     <p>Páginas:</p>
     <?php for ($i = 1; $i <= $totalPages; $i++): ?>
         <a href="?pag=<?= $i ?>" <?= $i === $page ? 'style="font-weight:bold;"' : '' ?>><?= $i ?></a>
     <?php endfor; ?>
 </div>