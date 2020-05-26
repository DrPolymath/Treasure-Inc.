<?php
    include_once("DatabaseConnection.php");
    session_start();
    if(isset($_GET['ReadUser'])){
        $sql = "SELECT * FROM user WHERE Email='".$_SESSION['Email']."'";
        $result = $pdo->query($sql);
            
        while ($res = $result->fetch()) {
            echo "<h3 id='updateUserName' class='py-4 font-weight-bold'>".$res['UserName']."</h3>";
            echo "<p id='updateBirthDate' hidden>".$res['BirthDate']."</p>";
            $dob = new DateTime($res['BirthDate']);
            $now = new DateTime();
            $difference = $now->diff($dob);
            $age = $difference->y;
            echo "<p>".$age." years old</p>";
            echo "<p id='updateUserCategory'>".$res['UserCategory']."</p>";
            echo "<p id='updateEmail'>".$res['Email']."</p>";
            echo "<p id='updatePhoneNumber'>".$res['PhoneNumber']."</p>";
            echo "<p id='updateAddress'>".$res['Address']."</p>";
        }

        echo"
        
        ";

    } else if (isset($_POST['UpdateUser'])){
        $UserName = $_POST['UserName'];
        $Email = $_POST['Email'];
        $PhoneNumber = $_POST['PhoneNumber'];
        $BirthDate = $_POST['BirthDate'];
        $Address = $_POST['Address'];

        try {
            $pdo->beginTransaction();
            $sql = "UPDATE user SET UserName='$UserName', Email='$Email', PhoneNumber='$PhoneNumber', BirthDate='$BirthDate', Address='$Address' WHERE Email='".$_POST['UpdateUser']."'";
            $pdo->query($sql);
            $_SESSION['Email']=$_POST['Email'];
            $_SESSION['UserName']=$_POST['UserName'];
            echo "success";
            $pdo->commit();
        } catch (Exception $e) {
            echo "fail";
            $pdo->rollback();
        }

    } else if (isset($_GET['DeleteUser'])) {
        try {
            $pdo->beginTransaction();
            $sql = "DELETE FROM user WHERE Email='".$_GET['DeleteUser']."'";
            $pdo->query($sql);
            echo "success";
            $pdo->commit();
        } catch (Exception $e) {
            echo "fail";
            $pdo->rollback();
        }
    }
    $pdo = null;
?>