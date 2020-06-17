<?php
    session_start();
    include_once("../php/DatabaseConnection.php");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/phpmailer/phpmailer/src/Exception.php';
    require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require 'vendor/phpmailer/phpmailer/src/SMTP.php';

    // Include autoload.php file
    require 'vendor/autoload.php';
    // Create object of PHPMailer class
    $mail = new PHPMailer(true);

    if (isset($_GET['DisplayEmailList'])){

        $sql = "SELECT Email FROM gameregistration WHERE GameID='".$_GET['GameID']."'";
        $result = $pdo->query($sql);

        echo '
        <label for="Receipient" class="col-sm-3 col-form-label">Receipient</label>
        <div class="col-sm-9">
            <select class="selectpicker form-control inputGameData" id="email" name="email[]" multiple required>
        ';

        while($res = $result->fetch()){
            echo '<option value="'.$res['Email'].'">'.$res['Email'].'</option>';
        }

        echo '
            </select>
        </div>
        ';

    } else if(isset($_POST['email'])&&isset($_POST['subject'])&&isset($_POST['message'])){

        $subject = $_POST['subject'];
        $message = "<p>".$_POST['message']."</p>";

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            // Gmail ID which you want to use as SMTP server
            $mail->Username = 'treasureinc.2020@gmail.com';
            // Gmail Password
            $mail->Password = 'TheWebnocrats2020';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
      
            // Email ID from which you want to send the email
            $mail->setFrom('treasureinc.2020@gmail.com');

            for($i=0;$i<count($_POST['email']);$i++){
                $mail->addAddress($_POST['email'][$i]);
            }
      
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
      
            if(!$mail->send()){
                echo 'error';
            } else {
                echo 'success';
            }
            
        } catch (Exception $e) {
            echo 'error';
        }

    } else if(isset($_POST['selectAll'])&&isset($_POST['subject'])&&isset($_POST['message'])){

        $subject = $_POST['subject'];
        $message = "<p>".$_POST['message']."</p>";        

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            // Gmail ID which you want to use as SMTP server
            $mail->Username = 'treasureinc.2020@gmail.com';
            // Gmail Password
            $mail->Password = 'TheWebnocrats2020';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
      
            // Email ID from which you want to send the email
            $mail->setFrom('treasureinc.2020@gmail.com');

            $sql = "SELECT Email FROM gameregistration WHERE GameID='".$_POST['GameID']."'";
            $result = $pdo->query($sql);

            while($res = $result->fetch()){
                // Recipient Email ID where you want to receive emails
                $mail->addAddress($res['Email']);
            }
      
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
      
            if(!$mail->send()){
                echo 'error';
            } else {
                echo 'success';
            }
            
        } catch (Exception $e) {
            echo 'error';
        }
    } else if ($_POST['ForgotPassword']) {
        $success = false;
        $sql = "SELECT Email FROM user WHERE Email='".$_POST['email']."'";
        $result = $pdo->query($sql);

        if ($result->rowCount()==0) {
            echo 'Do not exist';
        } else {
            $password = "User1234";
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                $pdo->beginTransaction();
                $sql = "UPDATE user SET Password='$hashPassword' WHERE Email='".$_POST['email']."'";
                $pdo->query($sql);
                $success = true;
                $pdo->commit();
            } catch (Exception $e) {
                echo 'An error has occured. Please try again';
                $pdo->rollback();
            }
        }

        if($success){
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                // Gmail ID which you want to use as SMTP server
                $mail->Username = 'treasureinc.2020@gmail.com';
                // Gmail Password
                $mail->Password = 'TheWebnocrats2020';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
          
                // Email ID from which you want to send the email
                $mail->setFrom('treasureinc.2020@gmail.com');

                // Recipient Email ID where you want to receive emails
                $mail->addAddress($_POST['email']);

          
                $mail->isHTML(true);
                $mail->Subject = "Temporary Password";
                $mail->Body = "<p>This is your temporary password.<br><br>Password : $password<br><br>Please change your password at your profile page.</p>";
          
                if(!$mail->send()){
                    echo 'An error has occured. Please try again';
                } else {
                    echo 'Temporary password has been sent to your email. Please check your email.';
                }
                
            } catch (Exception $e) {
                echo 'An error has occured. Please try again';
            }

        }

    }

    $pdo = null;
?>