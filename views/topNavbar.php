<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<div class="navtop">
  <div>
    <a href="index.php"><img class="icon" src="img/ui/V-white.svg"></a>
  </div>
  <div>
    <a href="posts.php" class="navitem ace test">Posts</a>
  </div>
  <?php if (isset($_SESSION['user'])) { ?>
    <div>
      <a href="myProfile.php" class="navitem ace test">Profile</a>
    </div>
    <div>
      <a href="signout.php" class="navitem ace">Sign Out</a>
    </div>
    <?php if ($_SESSION['user']['confirmed'] == 0) { ?>
      <div>
        <a href='userConfirmation.php' class="navitem ace">Verification</a>
      </div>
    <?php }
  } else { ?>
    <div>
      <a href="signup.php" class="navitem ace">Sign Up</a>
    </div>
    <div>
      <a href="signin.php" class="navitem ace">Sign In</a>
    </div>
  <?php } ?>
</div>