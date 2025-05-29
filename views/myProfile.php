<div style="display: flex; margin-top:40px; margin-left: auto; margin-right: auto; border: solid black 5px; width:70%">
    <a href="#" class="pfpCont"><img class="pfp" src="<?php echo $_SESSION['user']['picture']['directory'] . $_SESSION['user']['picture']['image'] ?>" alt=""></a>
    <div style="margin: 50px 30px 30px 30px">
        <a><?php echo $_SESSION['user']['uid'] ?></a>
        <h1 class="ace"><?php echo $_SESSION['user']['username'] ?></h1>
    </div>
</div>