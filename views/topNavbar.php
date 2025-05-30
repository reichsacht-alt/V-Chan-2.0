<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<div class="navtop">
  <div class="navLogo">
    <a href="index.php"><img class="icon" src="img/ui/V-white.svg"></a>
  </div>
  <div class="navLink">
    <a href="posts.php?pag=1&tag=1" class="navitem ace slide">Posts</a>
  </div>
  <?php if (isset($_SESSION['user'])) { ?>
    <div class="profile">
      <div class="dropdown">
        <button class="dropbtn"><img src="<?php echo $_SESSION['user']['picture']['directory'] . $_SESSION['user']['picture']['image'] ?>" alt="" class="dropdownBtn"></button>
        <div class="dropdown-content">
          <a href="myProfile.php" class="navitem ace dditem ddlink">View Profile</a>
          <?php if ($_SESSION['user']['confirmed'] == 0) { ?>
            <a href='userConfirmation.php' class="navitem ace dditem ddlink">Verification</a>
          <?php } ?>
          <a href="signout.php" class="navitem ace dditem ddlink">Sign Out</a>
        </div>
      </div>
    </div>



  <?php } else { ?>
    <div class="profile">
      <a href="signup.php" class="navitem ace slide">Sign Up</a>
      <a href="signin.php" class="navitem ace slide">Sign In</a>
    </div>
  <?php } ?>
</div>