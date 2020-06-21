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
            $sql = "DELETE FROM treasurehuntgames WHERE UserID='".$_SESSION['UserID']."'";
            $pdo->query($sql);
            $sql = "DELETE FROM user WHERE Email='".$_GET['DeleteUser']."'";
            $pdo->query($sql);
            echo "success";
            $pdo->commit();
        } catch (Exception $e) {
            echo "fail";
            $pdo->rollback();
        }
    } else if (isset($_GET['ReadCompanyInfo'])){
        $sql = "SELECT CompanyName, CompanyAddress, CompanyPhoneNumber FROM user WHERE Email='".$_SESSION['Email']."'";
        $result = $pdo->query($sql);

        while ($res = $result->fetch()) {
            echo'
            <div class="row">
                <div class="col-10">
                <h3 id="CompanyName" class="font-weight-bold py-1">'.$res['CompanyName'].'</h3>
                </div>
                <div class="col-2 d-flex">
                    <button type="button" class="btn button m-auto" data-toggle="modal" data-target="#editCompanyModal" onclick="displayCompanyDetail()">Edit</button>
                </div>
                <div class="col-10">
                    <p id="CompanyAddress">'.$res['CompanyAddress'].'</p>
                    <p id="CompanyPhoneNumber">'.$res['CompanyPhoneNumber'].'</p>
                </div>
                <div class="col-2">
                </div>
            </div>
            ';
            echo"
            <script>
            function displayCompanyDetail(){
                document.getElementById('UpdateCompanyName').value = document.getElementById('CompanyName').innerHTML;
                document.getElementById('UpdateCompanyAddress').value = document.getElementById('CompanyAddress').innerHTML;
                document.getElementById('UpdateCompanyPhoneNumber').value = document.getElementById('CompanyPhoneNumber').innerHTML;
                document.getElementById('OldCompany').value = document.getElementById('CompanyName').innerHTML;
                document.getElementById('UpdateCompany').value = document.getElementById('updateEmail').innerHTML;
              }
            </script>
            ";
        }
    } else if (isset($_GET['UpdateCompany'])){

        $CompanyName = $_GET['UpdateCompanyName'];
        $CompanyAddress = $_GET['UpdateCompanyAddress'];
        $CompanyPhoneNumber = $_GET['UpdateCompanyPhoneNumber'];

        try {
            $pdo->beginTransaction();
            $sql = "UPDATE user SET CompanyName='$CompanyName', CompanyAddress='$CompanyAddress', CompanyPhoneNumber='$CompanyPhoneNumber' WHERE Email='".$_GET['UpdateCompany']."' AND CompanyName='".$_GET['OldCompany']."'";
            $pdo->query($sql);
            echo "
            <script>
            alert('Company details successfully updated!');
            document.location.href='../html/Organiser - Profile.html';
            </script>
            ";
            $pdo->commit();
        } catch (Exception $e) {
            echo "
            <script>
            alert('Company details failed to be updated!');
            document.location.href='../html/Organiser - Profile.html';
            </script>
            ";
            $pdo->rollback();
        }
    }

    $pdo = null;
?>