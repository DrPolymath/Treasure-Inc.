<?php
    session_start();
    if(isset($_GET['Logout'])){
        // Logout
        session_destroy();
        header('Location: ../html/Main.html');
    } else if(!isset($_SESSION['UserName'])) {
        echo 'Not logged in!';
    } else {
        // Session logged in
        echo "<p id='greeting'>Hi, ".$_SESSION['UserName']."</p>";
    }
?>