<?php
session_start();
define('TITLE',"Contact Us | YourWebsiteName");
?>  
<link rel="stylesheet" href="assets\css\contact-main.css">
<link rel="stylesheet" href="assets\css\styles.css">
<link rel="stylesheet" href="assets\css\contact-util.css">
<style>
    .error {
  color: #ff6a6a;
  font-size: 1rem;
  font-weight: bold;
  margin-bottom: 15px;
}

.button {
  display: inline-block;
  padding: 12px 20px;
  font-size: 16px;
  font-weight: 600;
  color: #fff;
  background: linear-gradient(135deg, #00c6ff, #0072ff);
  border: none;
  border-radius: 25px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.button:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 15px rgba(0, 114, 255, 0.3);
}

.container-contact100 {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 30px;
  min-height: 100vh;
  font-family: 'Roboto', sans-serif;
}

.wrap-contact100 {
  width: 100%;
  max-width: 700px;
  background: #ffffff;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  overflow: hidden;
}

.contact100-form {
  padding: 30px;
}

.contact100-form-title {
  font-size: 2rem;
  font-weight: bold;
  text-align: center;
  color: #0072ff;
  margin-bottom: 20px;
}

.label-input100 {
  font-size: 14px;
  color: #333;
  font-weight: 600;
  margin-bottom: 5px;
  display: inline-block;
}

.wrap-input100 {
  margin-bottom: 20px;
}

.rs1-wrap-input100,
.rs2-wrap-input100 {
  width: 100%;
}

.input100 {
  width: 100%;
  padding: 12px 15px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 8px;
  outline: none;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.input100:focus {
  border-color: #0072ff;
  box-shadow: 0 0 8px rgba(0, 114, 255, 0.2);
}

.validate-input .focus-input100 {
  color: #0072ff;
}

.checkbox-animated .check {
  display: inline-block;
  width: 16px;
  height: 16px;
  background: #0072ff;
  border-radius: 50%;
  margin-right: 10px;
  transform: scale(0.9);
  transition: transform 0.3s ease;
}

.checkbox-animated .check:hover {
  transform: scale(1.2);
}

.my-4 {
  margin: 20px 0;
}

.box {
  padding: 20px;
  background: rgba(0, 114, 255, 0.1);
  border-radius: 12px;
}

.container-contact100-form-btn {
  display: flex;
  justify-content: center;
  margin-top: 30px;
}

.contact100-form-btn {
  display: inline-block;
  padding: 12px 30px;
  font-size: 16px;
  font-weight: 600;
  color: #fff;
  background: linear-gradient(135deg, #ff416c, #ff4b2b);
  border: none;
  border-radius: 25px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.contact100-form-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 15px rgba(255, 65, 108, 0.3);
}

.contact100-more {
  font

</style>
</head>

<body>
<?php
if (isset($_SESSION['userId'])) {
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function has_header_injection($str){
    return preg_match('/[\r\n]/', $str);
}
if (isset($_POST['contact_submit'])){
    if (!isset($_SESSION['userId'])) {
        $email = trim($_POST['email']);
        $name = trim($_POST['first-name']) . ' ' . trim($_POST['last-name']);
    } else {
        $email = trim($_SESSION['emailUsers']);
        $name = 'User: ' . $_SESSION['userUid'];
    }
    $msg = $_POST['message'];

    if (has_header_injection($name) || has_header_injection($email)){
        die("Invalid form submission.");
    }
    if (!$name || !$email || !$msg){
        echo '<h4 class="error">All Fields Required.</h4>'
            . '<a href="contact_us.php" class="button">Go back and try again</a>';
        exit;
    }
    $to = $email;
    $subject = "$name sent you a message via your contact form";
    $message = "<strong>Name:</strong> $name<br>" 
        . "<strong>Email:</strong> <i>$email</i><br><br>"
        . "<strong>Message:</strong><br>$msg";
    if (isset($_POST['subscribe'])) {
        $message .= "<br><strong>IMPORTANT:</strong> Please add <i>$email</i> "
            . "to your mailing list.<br>";
    }
    $mail = new PHPMailer(true);            
    try {
        $mail->isSMTP();                                      
        $mail->Host = 'smtp.gmail.com';                      
        $mail->SMTPAuth = true;                              
        $mail->Username = $SMTPuser;                              
        $mail->Password = $SMTPpwd;             
        $mail->SMTPSecure = 'tls';                           
        $mail->Port = 587;                                    
        $mail->setFrom($to, $SMTPtitle);
        $mail->addAddress($SMTPuser, $SMTPtitle);    
        $mail->isHTML(true);                                  
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->send();
    } catch (Exception $e) {
        echo '<h4 class="error">Message could not be sent. Mailer Error: '. $mail->ErrorInfo . '</h4>';
    }
}
?>
<div class="container-contact100">
    <div class="wrap-contact100">
        <form class="contact100-form validate-form" method="post" action="">
            <span class="contact100-form-title">Send Us A Message</span>

            <?php if (!isset($_SESSION['userId'])) { ?>
                <label class="label-input100" for="first-name">Tell us your name *</label>
                <div class="wrap-input100 rs1-wrap-input100 validate-input" data-validate="Type first name">
                    <input id="first-name" class="input100" type="text" name="first-name" placeholder="First name">
                    <span class="focus-input100"></span>
                </div>
                <div class="wrap-input100 rs2-wrap-input100 validate-input" data-validate="Type last name">
                    <input class="input100" type="text" name="last-name" placeholder="Last name">
                    <span class="focus-input100"></span>
                </div>
                <label class="label-input100" for="email">Enter your email *</label>
                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                    <input id="email" class="input100" type="text" name="email" placeholder="Eg. example@email.com">
                    <span class="focus-input100"></span>
                </div>
            <?php } ?>
            <div class="checkbox-animated my-4">
                <input id="checkbox_animated_1" type="checkbox" name="subscribe" value="subscribe">
                <label for="checkbox_animated_1">
                    <span class="check"></span>
                    <span class="box"></span>
                    Subscribe for Updates
                </label>
            </div>
            <label class="label-input100" for="message">Message *</label>
            <div class="wrap-input100 validate-input" data-validate="Message is required">
                <textarea id="message" class="input100" name="message" rows="8" placeholder="Write us a message"></textarea>
                <span class="focus-input100"></span>
            </div>               
            <div class="container-contact100-form-btn">
                <input type="submit" class="contact100-form-btn" name="contact_submit" value="Send Message">
            </div>
        </form>
        <div class="contact100-more flex-col-c-m" style="background-image: url('img/contact.jpg');">
            <div class="flex-w size1 p-b-47">
                <div class="txt1 p-r-25">
                    <span class="lnr lnr-map-marker"></span>
                </div>
                <div class="flex-col size2">
                    <span class="txt1 p-b-20">About Us</span>
                    <span class="txt2">
                        University Students stumbling onto new ambitions<br>
                        NUST, Islamabad Pakistan
                    </span>
                </div>
            </div>
            <div class="dis-flex size1 p-b-47">
                <div class="txt1 p-r-25">
                    <span class="lnr lnr-phone-handset"></span>
                </div>
                <div class="flex-col size2">
                    <span class="txt1 p-b-20">Star My EMS project</span>
                    <span class="txt3">
                        github.com/Dr_Atif_awan/ VU Pakistan
                    </span>
                </div>
            </div>
            <div class="dis-flex size1 p-b-47">
                <div class="txt1 p-r-25"><span class="lnr lnr-envelope"></span></div>
                <div class="flex-col size2">
                    <span class="txt1 p-b-20">General Support</span>
                    <span class="txt3">BC 230213553 Muhammad Atif</span>
                </div>
            </div>
            <?php if (!isset($_SESSION['userId'])) {
                echo '<a class="contact100-form-btn" href="login.php">Login Page</a>';
            } ?>
        </div>
    </div>
</div>
<script src="js/contact-main.js"></script> 
<script src=js/assets\js\contact-main.js> </script>

