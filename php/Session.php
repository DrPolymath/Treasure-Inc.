<?php
    session_start();
        echo "<p id='greeting'>Hi, ".$_SESSION['UserName']."</p>";
    
?>