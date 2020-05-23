<?php
    session_start();
    if(isset($_GET['Logout'])){
        session_destroy();
        header('Location: ../html/Player.html');
    } else {
        echo "<p id='greeting'>Hi, ".$_SESSION['UserName']."</p>";
    }
?>