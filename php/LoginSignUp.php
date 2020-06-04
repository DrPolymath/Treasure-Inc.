<?php

    include_once("DatabaseConnection.php");

//                                                                  FUNCTION

    function emailRegistered($Email){
        global $pdo;
        $sql = "SELECT Email FROM user WHERE Email='$Email'";
        $result = $pdo->query($sql);
        if($result->rowCount() == 0){
            return false;
        } else {
            return true;
        }
    }

//                                                                  MAIN

    //LOGIN
    if(isset($_GET["Email"])){

        $Email = $_GET["Email"];
        $Password = $_GET["Password"];
        $hashPassword = "";

        $sql = "SELECT Email, UserName, UserCategory, Password FROM user WHERE Email='$Email'";
        $result = $pdo->query($sql);

        if($result->rowCount() == 0){
            echo "<script>
                    alert('You have entered an invalid email or password!');
                    document.location.href='javascript:history.go(-1)';
                </script>";
        } else {
            session_start();
            while($res = $result->fetch()){
                $hashPassword = $res['Password'];
                $_SESSION['Email']=$res['Email'];
                $_SESSION['UserName']=$res['UserName'];
                $_SESSION['UserCategory']=$res['UserCategory'];
            }
            if(password_verify($Password,$hashPassword)){
                if($_SESSION['UserCategory']=="Player"){
                    header('Location: ../html/Player.html');
                } else {
                    header('Location: ../html/Organiser.html');
                }
            } else {
                echo "<script>
                    alert('You have entered an invalid email or password!');
                    document.location.href='javascript:history.go(-1)';
                </script>";
            }
        }

    //SIGN UP
    } else if(isset($_POST["Email"])) {
        if($_POST["Password"]!=$_POST["ConfirmPassword"]){
            echo "<script>
                    alert('Passwords do not match!');
                    document.location.href='javascript:history.go(-1)';
                </script>";
        } else {

            if(emailRegistered($_POST["Email"])){

                echo "<script>
                    alert('Email already registered!');
                    document.location.href='javascript:history.go(-1)';
                </script>";

            } else {

                $UserName = $_POST["UserName"];
                $Email = $_POST["Email"];
                $PhoneNumber = $_POST["PhoneNumber"];
                $BirthDate = $_POST["BirthDate"];
                $Address = $_POST["Address"];
                $UserCategory = $_POST["UserCategory"];
                $Password = password_hash($_POST["Password"], PASSWORD_DEFAULT);

                try {
                    $pdo->beginTransaction();
                    $sql = "INSERT INTO user(UserName,Email,PhoneNumber,BirthDate,Address,UserCategory,Password) VALUES ('$UserName','$Email','$PhoneNumber','$BirthDate','$Address','$UserCategory','$Password')";
                    $pdo->query($sql);
                    $pdo->commit();
                    echo "<script>
                        alert('Registration Complete!');
                        document.location.href='../html/SignUp.html';
                    </script>";
                } catch (PDOException $e) {
                    $pdo->rollback();
                }

                $pdo = null;

            }
        }
    }
    
?>