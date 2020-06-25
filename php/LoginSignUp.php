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
    if(isset($_GET["Email"])&&isset($_GET["Password"])){

        $Email = $_GET["Email"];
        $Password = $_GET["Password"];
        $hashPassword = "";

        $sql = "SELECT UserID, Email, UserName, UserCategory, Password FROM user WHERE Email='$Email'";
        $result = $pdo->query($sql);
        // Checking validity of email
        if($result->rowCount() == 0){
            echo "<script>
                    alert('You have entered an invalid email or password!');
                    document.location.href='javascript:history.go(-1)';
                </script>";
        } else {
            session_start();
            while($res = $result->fetch()){
                $hashPassword = $res['Password'];
                $_SESSION['UserID']=$res['UserID'];
                $_SESSION['Email']=$res['Email'];
                $_SESSION['UserName']=$res['UserName'];
                $_SESSION['UserCategory']=$res['UserCategory'];
            }
            // Checking validity of password
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
    } else if(isset($_POST["UserName"])&&isset($_POST["Email"])&&isset($_POST["PhoneNumber"])&&isset($_POST["BirthDate"])&&isset($_POST["Address"])&&isset($_POST["UserCategory"])&&isset($_POST["Password"])) {
        //Checking whether the password and confirmpassword are matched
        if($_POST["Password"]!=$_POST["ConfirmPassword"]){
            echo "<script>
                    alert('Passwords do not match!');
                    document.location.href='javascript:history.go(-1)';
                </script>";
        } else {
            //Check if email entered already registered or not
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
                // Check if user complete the all the company detail if they register as an organiser 
                if($UserCategory=="Organiser"&&(empty($_POST["CompanyName"])||empty($_POST["CompanyAddress"])||empty($_POST["CompanyPhoneNumber"]))){
                    echo "<script>
                    alert('Please complete the company details!Please enter - if you do not belong to an organization.');
                        document.location.href='../html/SignUp.html';
                    </script>";
                } else {
                    try {
                        $pdo->beginTransaction();
                        if($UserCategory=="Organiser"){
                            //Organiser register
                            $CompanyName = $_POST["CompanyName"];
                            $CompanyAddress = $_POST["CompanyAddress"];
                            $CompanyPhoneNumber = $_POST["CompanyPhoneNumber"];
                            $sql = "INSERT INTO user(UserName,Email,PhoneNumber,BirthDate,Address,UserCategory,CompanyName,CompanyAddress,CompanyPhoneNumber,Password) VALUES ('$UserName','$Email','$PhoneNumber','$BirthDate','$Address','$UserCategory','$CompanyName','$CompanyAddress','$CompanyPhoneNumber','$Password')";
                        } else {
                            //Player register
                            $sql = "INSERT INTO user(UserName,Email,PhoneNumber,BirthDate,Address,UserCategory,Password) VALUES ('$UserName','$Email','$PhoneNumber','$BirthDate','$Address','$UserCategory','$Password')";
                        }
                        $pdo->query($sql);
                        $pdo->commit();
                        echo "<script>
                            alert('Registration Complete!');
                            document.location.href='../html/Login.html';
                        </script>";
                    } catch (PDOException $e) {
                        $pdo->rollback();
                        echo "<script>
                            alert('Registration failed!');
                            document.location.href='../html/Login.html';
                        </script>";
                    }
                } 
            }
        }
    }

    $pdo = null;
    
?>