<div style="display: flex; margin-top:40px; margin-left: auto; margin-right: auto; border: solid black 5px; width:70%">
    <a href="#" class="pfpCont"><img class="pfp" src="<?php echo $_SESSION['user']['picture']['directory'] . $_SESSION['user']['picture']['image'] ?>" alt=""></a>
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
<img src="c:\Users\Santiago\Desktop\output\113_pan.gif" alt="">