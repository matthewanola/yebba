<?php
require_once('config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('PHPMailer/src/Exception.php');
require_once('PHPMailer/src/PHPMailer.php');
require_once('PHPMailer/src/SMTP.php');

$mail = new PHPMailer;

$mail->isSMTP(); 
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'astrud0905@gmail.com';
$mail->Password = 'vphrfbsjtmpagtyo';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

if(isset($_POST['type'])){
    if($_POST['type'] == 'login'){
        $email = $_POST['login_email'];
        $pass = $_POST['login_password'];

        if(!empty($email) || !empty($pass)){
            $sql = "SELECT * FROM users WHERE user_email='".$email."' AND user_password='".MD5($pass)."'";
            $result = $con->query($sql);  
            while($fetch = $result->fetch_assoc()){
                $user_id = $fetch['user_id'];
                $player_id = $fetch['player_id'];
            }
            
            if ($result->num_rows > 0) {
                session_start();
                $_SESSION['isloggedin'] = true;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['player_id'] = $player_id;
                $_SESSION['email'] = $email;

                header("location:./home.php"); 
                die();   
            }else{
                header("location:./home.php?event=invalid-login"); 
                die();   
            }

        }else{
            header("location:./home.php?event=input-login"); 
            die();   
        }
        
    }elseif($_POST['type'] == 'register'){
        $playerid = $_POST['player_id'];
        $email = $_POST['register_email'];
        $pass = $_POST['register_password'];

        if(!empty($email) || !empty($pass) || !empty($playerid)){
            // VALIDATE EMAIL
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header("location:./home.php?event=invalid-email"); 
                die();   
            }

            // VALIDATE PLAYER ID
            if(!preg_match('/^\d{12}$/', $playerid)){
                header("location:./home.php?event=invalid-player-id"); 
                die();  
            }

            $sql = "SELECT * FROM users WHERE user_email='".$email."'";
            $result = $con->query($sql);

            if($result->num_rows > 0){
                header("location:./home.php?event=email-already-in-use"); 
                die();
            }else{
                $sql = "INSERT INTO users VALUES (NULL, '".$email."', '".MD5($pass)."', '".$playerid."')";

                if($con->query($sql) === TRUE){
                    //SEND MAIL AFTER SUCCESSFUL REGISTRATION
                    $mail->setFrom('astrud0905@gmail.com', 'Pokemon Webstore');
                    $mail->addAddress($email, 'Trainer');
                    $mail->Subject = 'Account Registration Successful';
                    $mail->Body = 'Good day trainer, This is to inform you that you have been successfully registered to Pokémon Webstore!';

                    if (!$mail->send()) {
                        echo 'Message could not be sent.';
                        echo 'Mailer Error: ' . $mail->ErrorInfo;
                    } else {
                        echo 'Message has been sent';
                    }

                    header("location:./home.php?event=registration-success"); 
                    die();
                }else{
                    echo "Error: " . $sql . "<br>" . $con->error;
                }
            }
        }else{
            header("location:./home.php?event=input-register"); 
            die();   
        }

    }elseif($_POST['type'] == 'checkout'){
        $userid = $_POST['userid'];
        $playerid = $_POST['playerid'];
        $email = $_POST['email'];
        $cartid = $_POST['cartid'];
        $itemid = $_POST['itemid'];
        $price = $_POST['price'];
        $qty = $_POST['qty'];
        $gcashref = $_POST['gcashref'];
        $overalltotal = $_POST['overalltotal'];

        $sql = "INSERT INTO transactions VALUES (NULL, '".$userid."', '".$email."', '".$playerid."', '".$gcashref."', NOW())";

        if($con->query($sql) === TRUE){
            $sql = "SELECT transaction_id FROM transactions ORDER BY transaction_id DESC LIMIT 1";
            $result = $con->query($sql);
            $fetch = mysqli_fetch_array($result);
            $transid = $fetch['transaction_id'];

            $queries = array();
            for($i = 0; $i < count($itemid); $i++){
                $queries[] = "INSERT INTO transaction_details VALUES(NULL, '".$transid."', '".$itemid[$i]."', '".$price[$i]."', '".$qty[$i]."')";
                $queries[] = "DELETE FROM carts WHERE cart_id = '".$cartid[$i]."'";
            }
            $sql = implode(";", $queries);

            if ($con->multi_query($sql) === TRUE) {
                //SEND MAIL AFTER SUCCESSFUL PURCHASE
                $mail->setFrom('astrud0905@gmail.com', 'Pokemon Webstore');
                $mail->addAddress($email, 'Trainer');
                $mail->Subject = 'Transaction Receipt';
                $mail->Body = 'Good day trainer, This is to inform you that your recent transaction a total of '.$overalltotal.' to Pokémon Webstore have been confirmed with transaction id '.$transid.'. Thank you for purchasing!';

                if (!$mail->send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    echo 'Message has been sent';
                }
                header("location:./home.php?event=purchase-success"); 
                die();
            }else{
                echo "Error: " . $sql . "<br>" . $con->error;
            }

        }else{
            echo "Error: " . $sql . "<br>" . $con->error;
        }

    }elseif($_POST['type'] == 'changepass'){
        $userid = $_POST['userid'];
        $currentpass = $_POST['current_pass'];
        $newpass = $_POST['new_pass'];

        $sql = "SELECT * FROM users WHERE user_id = '".$userid."' AND user_password = '".MD5($currentpass)."'";
        $result = $con->query($sql);

        if($result->num_rows > 0){
            $sql = "UPDATE users SET user_password = '".MD5($newpass)."' WHERE user_id = '".$userid."'";

            if($con->query($sql) === true){
                header("location:./profile.php?event=pass-change-success"); 
                die();   
            }else{
                echo"Error: " .$sql. "<br>" .$con->error;
            }
        }else{
            header("location:./profile.php?event=wrong-password"); 
            die();   
        }

    }elseif($_POST['type'] == 'updateaccount'){    
        $userid = $_POST['userid'];
        $email = $_POST['email'];
        $playerid = $_POST['player_id'];

        // VALIDATE EMAIL
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("location:./profile.php?event=invalid-email"); 
            die();   
        }

        // VALIDATE PLAYER ID
        if(!preg_match('/^\d{12}$/', $playerid)){
            header("location:./profile.php?event=invalid-player-id"); 
            die();  
        }

        $sql = "UPDATE users SET user_email = '".$email."', player_id = '".$playerid."' WHERE user_id = '".$userid."'"; 

        if($con->query($sql) === true){
            header("location:./profile.php?event=update-acc-success"); 
            die();   
        }else{
            echo"Error: " .$sql. "<br>" .$con->error;
        }

    }else{
        echo "Error: " . $sql . "<br>" . $con->error;
    }

}

if(isset($_GET['type'])){
    if($_GET['type'] == 'addcart'){
        $itemid = $_GET['itemid'];
        $userid = $_GET['userid'];

        $sql = "INSERT INTO carts VALUES(NULL, '".$itemid."', '".$userid."')";

        if($con->query($sql) === true){
            header("location:./shop.php?event=add-cart-success"); 
            die();   
        }else{
            echo"Error: " .$sql. "<br>" .$con->error;
        }
    }elseif($_GET['type'] == 'delcartitem'){
        $cartid = $_GET['cartid'];

        $sql = "DELETE FROM carts WHERE cart_id = '".$cartid."'";
        
        if($con->query($sql) === true){
            header("location:./shop.php?event=del-item-success"); 
            die();   
        }else{
            echo"Error: " .$sql. "<br>" .$con->error;
        }
    }elseif($_GET['type'] == 'delaccount'){
        $userid = $_GET['user_id'];
        $email = $_GET['email'];

        $sql = "DELETE FROM users WHERE user_id = '".$userid."'";

        if($con->query($sql) === true){
            // SEND MAIL AFTER ACCOUNT DELETION
            $mail->setFrom('astrud0905@gmail.com', 'Pokemon Webstore');
                $mail->addAddress($email, 'Trainer');
                $mail->Subject = 'Account Deletion';
                $mail->Body = 'Good day trainer, This is to inform you that your account deletion is successful. We genuinely appreciate the time you spent with us and the trust you placed in our services. Should you ever reconsider, you are always welcome to rejoin our platform.';

                if (!$mail->send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    echo 'Message has been sent';
                }

            session_start();
            session_destroy();
            header("location:./home.php?event=account-del-success"); 
            die();   
        }else{
            echo"Error: " .$sql. "<br>" .$con->error;
        }
    }else if($_GET['type'] == 'logout'){
        session_start();
        session_destroy();
        header("location:./home.php"); 
        die();

    }else{
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}
?>